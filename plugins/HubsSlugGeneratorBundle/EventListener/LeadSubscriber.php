<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use MauticPlugin\HubsSlugGeneratorBundle\Helper\SlugGeneratorHelper;

class LeadSubscriber extends CommonSubscriber
{
    private $helper;

    public function __construct(SlugGeneratorHelper $helper)
    {
        $this->helper = $helper;
    }

    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_PRE_SAVE => ['onContactSave', 0],
        ];
    }

    public function onContactSave(LeadEvent $event)
    {
        $lead         = $event->getLead();
        $customFields = $lead->getFields('core');
        $label        = $this->helper->getSlugFieldName();
        if (!$label) {
            return;
        }
        if (!isset($customFields[$label])) {
            return;
        }
        if ($lead->getFieldValue($label)) {
            return;
        }
        $firstname = $lead->getFieldValue('firstname');
        $lastname  = $lead->getFieldValue('lastname');
        if (!$firstname || !$lastname) {
            return false;
        }
        $slugText = $this->helper->getSlugText($firstname, $lastname);
        $lead->__set($label, $slugText);
    }
}
