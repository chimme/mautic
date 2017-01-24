<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Controller;

use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceSubtypes;
use FacebookAds\Object\Values\CustomAudienceTypes;
use Mautic\CoreBundle\Controller\FormController;
use MauticPlugin\HubsFacebookAdsBundle\Form\AddCustomAudienceType;

/**
 * Description of TestController.
 *
 * @author arul
 */
class CustomAudienceController extends FormController
{
    public function indexAction()
    {
        $permissions = $this->get('mautic.security')->isGranted(
                [
            'facebookAds:addvertising:view',
            'facebookAds:addvertising:create',
            'facebookAds:addvertising:edit',
            'facebookAds:addvertising:delete',
                ], 'RETURN_ARRAY'
        );
        if (!$permissions['facebookAds:addvertising:view']) {
            return $this->accessDenied();
        }
        $apiHelper = $this->get('hubs.fbads.helper');
        $session   = $apiHelper->getApi()->getSession();
        if (!$session->isValidSession()) {
            return $this->notFound();
        }
        $adAccountId = 'act_'.$session->getAdAccountId();
        $fields      = [
            \FacebookAds\Object\Fields\CustomAudienceFields::ID,
            \FacebookAds\Object\Fields\CustomAudienceFields::NAME,
            \FacebookAds\Object\Fields\CustomAudienceFields::TIME_CREATED,
            \FacebookAds\Object\Fields\CustomAudienceFields::TIME_UPDATED,
        ];
        $adAccount = new \FacebookAds\Object\AdAccount($adAccountId);
        try {
            $result = $adAccount->getCustomAudiences($fields);
        } catch (\Exception $ex) {
            return $this->renderFacebookAdsException();
        }
        $customAudianceArray = $result->getObjects();
        foreach ($customAudianceArray as $tempData) {
            $data[] = $tempData->getData();
        }
        $viewParameters = [
            'route'       => 'hubs_fb_ca_action',
            'items'       => $data,
            'permissions' => $permissions,
            'security'    => $this->get('mautic.security'),
        ];

        return $this->delegateView(
                        [
                            'viewParameters'  => $viewParameters,
                            'contentTemplate' => 'HubsFacebookAdsBundle:Ads:index.html.php',
                            'passthroughVars' => [
                                'activeLink'    => '#hubs_fb_ca_index',
                                'mauticContent' => 'Custom Audience',
                                'route'         => $this->generateUrl('hubs_fb_ca_index'),
                            ],
                        ]
        );
    }

    public function newAction()
    {
        if (!$this->get('mautic.security')->isGranted('facebookAds:addvertising:manage')) {
            return $this->accessDenied();
        }
        $data   = [];
        $action = $this->generateUrl('hubs_fb_ca_action', ['objectAction' => 'new']);
        $form   = $this->createForm(AddCustomAudienceType::class, $data, [
            'action' => $action,
        ]);
        ///Check for a submitted form and process it
        if ($this->request->getMethod() == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    $listModal = $this->get('mautic.lead.model.list');
                    $listId    = $form['addToLists']->getData();
                    $listObj   = $listModal->getEntity($listId);
                    $apiHelper = $this->get('hubs.fbads.helper');
                    $session   = $apiHelper->getApi()->getSession();
                    if (!$session->isValidSession()) {
                        return $this->notFound();
                    }
                    $adAccountId = 'act_'.$session->getAdAccountId();
                    $audience    = new CustomAudience(null, $adAccountId);
                    $audience->setData([
                        CustomAudienceFields::NAME        => $listObj->getName(),
                        CustomAudienceFields::SUBTYPE     => CustomAudienceSubtypes::CUSTOM,
                        CustomAUdienceFields::DESCRIPTION => $listObj->getDescription(),
                    ]);

                    try {
                        $data = $audience->create();
                    } catch (\Exception $ex) {
                        return $this->renderFacebookAdsException();
                    }

                    $list       = $listModal->getLeadsByList($listId);
                    $leadsEmail = [];
                    foreach ($list[$listId] as $leads) {
                        if ($leads['email']) {
                            $leadsEmail[] = $leads['email'];
                        }
                    }
                    $audience->addUsers($leadsEmail, CustomAudienceTypes::EMAIL);
                    $returnUrl = $this->generateUrl('hubs_fb_ca_index');
                    $template  = 'HubsFacebookAdsBundle:CustomAudience:index';
                }
            } else {
                $returnUrl = $this->generateUrl('hubs_fb_ca_index');
                $template  = 'HubsFacebookAdsBundle:CustomAudience:index';
            }

            if ($cancelled || ($valid && ($form->get('buttons')->get('save')->isClicked() || $form->get('buttons')->get('apply')->isClicked()))) {
                //clear temporary fields
                return $this->postActionRedirect(
                                [
                                    'returnUrl'       => $returnUrl,
                                    'viewParameters'  => [],
                                    'contentTemplate' => $template,
                                    'passthroughVars' => [
                                        'activeLink'    => '#hubs_fb_ca_index',
                                        'mauticContent' => 'form',
                                    ],
                                ]
                );
            }
        }

        return $this->delegateView(
                        [
                            'viewParameters' => [
                                'form' => $form->createView(),
                            ],
                            'contentTemplate' => 'HubsFacebookAdsBundle:Ads:new.html.php',
                            'passthroughVars' => [
                                'activeLink'    => '#hubs_fb_ca_index',
                                'mauticContent' => 'Custom Audience',
                                'route'         => $action,
                            ],
                        ]
        );
    }

    public function deleteAction($objectId)
    {
        if (!$this->get('mautic.security')->isGranted('facebookAds:addvertising:delete')) {
            return $this->accessDenied();
        }

        $returnUrl = $this->generateUrl('hubs_fb_ca_index');
        $flashes   = [];

        $postActionVars = [
            'returnUrl'       => $returnUrl,
            'viewParameters'  => [],
            'contentTemplate' => 'HubsFacebookAdsBundle:CustomAudience:index',
            'passthroughVars' => [
                'activeLink'    => '#hubs_fb_ca_index',
                'mauticContent' => 'Custom Audience',
            ],
        ];

        if ($this->request->getMethod() == 'POST') {
            $apiHelper = $this->get('hubs.fbads.helper');
            $session   = $apiHelper->getApi()->getSession();
            if (!$session->isValidSession()) {
                return $this->notFound();
            }
            $audience = new CustomAudience($objectId);
            try {
                $audience->deleteSelf();
            } catch (\Exception $ex) {
                return $this->renderFacebookAdsException();
            }
            $flashes[] = [
                'type'    => 'notice',
                'msg'     => 'mautic.core.notice.deleted',
                'msgVars' => [
                    '%name%' => 'Custom audiance',
                    '%id%'   => $objectId,
                ],
            ];
        }

        return $this->postActionRedirect(
                        array_merge(
                                $postActionVars, [
                    'flashes' => $flashes,
                                ]
                        )
        );
    }

    public function renderFacebookAdsException()
    {
        return $this->delegateView(
                        [
                            'viewParameters' => [
                                'error' => $this->get('translator')->trans('hubs.facebookAds.api.error'),
                            ],
                            'contentTemplate' => 'HubsFacebookAdsBundle:Ads:error.html.php',
                            'passthroughVars' => [
                                'activeLink'    => '#hubs_fb_ca_index',
                                'mauticContent' => 'Custom Audience',
                                'route'         => $this->generateUrl('hubs_fb_ca_index'),
                            ],
                        ]
        );
    }
}
