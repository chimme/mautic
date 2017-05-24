<?php

namespace MauticPlugin\BeeEditorBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;

class IndexController extends CommonController
{
    public function generateAction()
    {
        if (!$this->get('mautic.security')->isGranted('email:emails:create')) {
            return $this->accessDenied();
        }
        if (!$this->has('mautic.helper.bee.auth.helper')) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(['message' => 'BEE editor config failed.']);
        } else {
            $beeToken = $this->get('mautic.helper.bee.auth.helper')->getToken();

            return new \Symfony\Component\HttpFoundation\JsonResponse(['tokens' => $beeToken, 'message' => 'success']);
        }
    }
}
