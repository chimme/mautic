<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Controller;

use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceSubtypes;
use Mautic\CoreBundle\Controller\FormController;

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
        $modal     = $this->get('hubs.fbads.model.customaudiance');
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
            \FacebookAds\Object\Fields\CustomAudienceFields::DELIVERY_STATUS,
        ];
        $adAccount = new \FacebookAds\Object\AdAccount($adAccountId);
        try {
            $result = $adAccount->getCustomAudiences($fields);
        } catch (\Exception $ex) {
            return $this->renderFacebookAdsException();
        }
        $customAudianceArray = $result->getObjects();
        $apiData             = [];
        foreach ($customAudianceArray as $tempData) {
            $tmpData                 = $tempData->getData();
            $apiData[$tmpData['id']] = $tempData->getData();
        }
        $customAudiance = $modal->getEntities();
        $viewParameters = [
            'route'          => 'hubs_fb_ca_action',
            'apiData'        => $apiData,
            'customAudiance' => $customAudiance,
            'permissions'    => $permissions,
            'security'       => $this->get('mautic.security'),
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
        $action         = $this->generateUrl('hubs_fb_ca_action', ['objectAction' => 'new']);
        $model          = $this->get('hubs.fbads.model.customaudiance');
        $CustomAudience = $model->getEntity();
        //get the user form factory
        $form = $model->createForm($CustomAudience, $this->get('form.factory'), $action);
        ///Check for a submitted form and process it
        if ($this->request->getMethod() == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    $listId    = $CustomAudience->getList()->getId();
                    $listObj   = $CustomAudience->getList();
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
                        CustomAUdienceFields::DESCRIPTION => $CustomAudience->getDescription() ?? $listObj->getDescription(),
                    ]);

                    try {
                        $data = $audience->create();
                    } catch (\Exception $ex) {
                        return $this->renderFacebookAdsException();
                    }
                    $CustomAudience->setName($listObj->getName());
                    $CustomAudience->setCustomAudienceId($audience->__get('id'));
                    $model->saveEntity($CustomAudience);
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
            $modal          = $this->get('hubs.fbads.model.customaudiance');
            $customAudience = $modal->getEntity($objectId);
            $apiHelper      = $this->get('hubs.fbads.helper');
            $session        = $apiHelper->getApi()->getSession();
            if (!$session->isValidSession()) {
                return $this->notFound();
            }
            $audience = new CustomAudience($customAudience->getCustomAudienceId());
            try {
                $audience->deleteSelf();
                $em = $this->getDoctrine()->getManager();
                $em->remove($customAudience);
                $em->flush();
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
