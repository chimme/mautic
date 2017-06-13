<?php

namespace MauticPlugin\HubsFacebookAdsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\Event\LeadListEvent;
use Mautic\LeadBundle\Event\ListChangeEvent;
use Mautic\LeadBundle\LeadEvents;
use MauticPlugin\HubsFacebookAdsBundle\Model\CustomAudienceModel;

class LeadSubscriber extends CommonSubscriber
{
    protected $customAudienceModel;

    public function __construct(CustomAudienceModel $customAudienceModel)
    {
        $this->customAudienceModel = $customAudienceModel;
        parent::__construct();
    }

    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_LIST_CHANGE => ['onLeadListChange'],
            LeadEvents::LEAD_POST_DELETE => ['onLeadDelete'],
            LeadEvents::LIST_PRE_DELETE  => ['onLeadListDelete'],
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
        $this->customAudienceModel->updateCustomAudianceToRemove($listId, $leadId);

        return;
    }

    public function onLeadDelete(LeadEvent $event)
    {
        $lead = $event->getLead();
        $this->customAudienceModel->updateCustomAudianceToRemove(false, $lead->deletedId);

        return;
    }

    public function onLeadListDelete(LeadListEvent $event)
    {
        $list           = $event->getList();
        $customAudience = $this->customAudienceModel->getRepository()->findOneByList($list);
        $this->customAudienceModel->deleteEntity($customAudience);
    }
}
