<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;

class SlugGeneratorController extends CommonController
{
    public function generateAction()
    {
        if (!$this->get('mautic.security')->isGranted(['lead:leads:editown', 'lead:leads:editother'])) {
            return $this->accessDenied();
        }
        $generator = $this->get('hubs.helper.slug.generator');
        $generator->generateAllContactSlugs();
        $returnUrl = $this->generateUrl('mautic_contact_index');

        return $this->postActionRedirect(
                        [
                            'returnUrl'       => $returnUrl,
                            'viewParameters'  => [],
                            'contentTemplate' => 'MauticLeadBundle:Lead:index',
                            'passthroughVars' => [
                                'activeLink'    => '#mautic_contact_index',
                                'mauticContent' => 'contact',
                            ],
                            'flashes' => [
                                [
                                    'msg' => 'hubs.slug.generated',
                                ],
                            ],
                        ]
        );
    }
}
