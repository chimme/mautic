<?php

namespace MauticPlugin\HubsFacebookAdsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\Event\ListChangeEvent;
use Mautic\LeadBundle\LeadEvents;

class LeadSubscriber extends CommonSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_LIST_CHANGE => ['onLeadListChange'],
            LeadEvents::LEAD_POST_DELETE => ['onLeadDelete'],
        ];
    }

    public function onLeadListChange(ListChangeEvent $event)
    {
        $lead = $event->getLead();
        $list = $event->getList();
        if ($event->wasAdded()) {
            return;
        }
        $listId = (is_object($list)) ? $list->getId() : $list;
        $leadId = (is_object($lead)) ? $lead->getId() : $lead;
        $this->em->getRepository('HubsFacebookAdsBundle:ListLeadCustomAudience')
                ->updateCustomAudianceToRemove($listId, $leadId);

        return;
    }

    public function onLeadDelete(LeadEvent $event)
    {
        $lead = $event->getLead();
        $this->em->getRepository('HubsFacebookAdsBundle:ListLeadCustomAudience')
                ->updateCustomAudianceToRemove(false, $lead->deletedId);

        return;
    }
}
