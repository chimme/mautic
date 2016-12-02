<?php

namespace MauticPlugin\HubsCoreBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;

/**
 * Class BuilderSubscriber.
 */
class BuilderSubscriber extends CommonSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EmailEvents::EMAIL_ON_BUILD => ['onEmailBuild', -1],
        ];
    }

    /**
     * @param EmailBuilderEvent $event
     */
    public function onEmailBuild(EmailBuilderEvent $event)
    {
        if ($event->slotTypesRequested()) {
            $event->addSlotType(
                'wkzpost',
                'WKZ-Post',
                'image',
                'HubsCoreBundle:Slots:wkz-post.html.php',
                'slot',
                600
            );
        }

        var_dump($event);
    }
}
