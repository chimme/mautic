<?php

namespace MauticPlugin\HubsCoreBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Helper\ThemeHelper;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;

/**
 * Class BuilderSubscriber.
 */
class BuilderSubscriber extends CommonSubscriber
{
    /**
     * @var ThemeHelper
     */
    protected $themeHelper;

    public function __construct(ThemeHelper $themeHelper)
    {
        $this->themeHelper = $themeHelper;
    }

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

        // Figure out if at least one WKZ Theme is installed
        $addSlot = false;
        foreach ($this->themeHelper->getInstalledThemes() as $themeName => $themeDescription) {
            if ((strrpos($themeName, 'wkz') === 0)) {
                $addSlot = true;
            }
        }

        // Render the custom slot only if at least one WKZ Theme is installed
        if ($addSlot && $event->slotTypesRequested()) {
            $event->addSlotType(
                'wkzpost',
                'WKZ-Post',
                'image',
                'HubsCoreBundle:Slots:wkz-post.html.php',
                'slot',
                600
            );
        }
    }
}
