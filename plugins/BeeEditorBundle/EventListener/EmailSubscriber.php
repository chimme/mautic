<?php

namespace MauticPlugin\BeeEditorBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailEvent;

class EmailSubscriber extends CommonSubscriber
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            EmailEvents::EMAIL_PRE_SAVE => ['onEmailSave', 0],
        ];
    }

    public function onEmailSave(EmailEvent $event)
    {
        $email       = $event->getEmail();
        $beeTemplate = $email->getBeeTemplate();
        $beeTemplate = !empty($beeTemplate) ? base64_encode($beeTemplate) : '';
        $email->setBeeTemplate($beeTemplate);
    }
}
