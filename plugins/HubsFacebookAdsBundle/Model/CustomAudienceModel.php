<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Model;

use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\CoreBundle\Helper\ProgressBarHelper;
use Mautic\CoreBundle\Model\FormModel;
use MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceChangeEvent;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceEvent;
use MauticPlugin\HubsFacebookAdsBundle\Event\CustomAudianceEvents;
use MauticPlugin\HubsFacebookAdsBundle\Form\AddCustomAudienceType;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class CustomAudienceModel
 * {@inheritdoc}
 */
class CustomAudienceModel extends FormModel
{
    /**
     * @var CoreParametersHelper
     */
    protected $coreParametersHelper;

    /**
     * @var CoreParametersHelper
     */
    protected $customAudienceLists;

    /**
     * ListModel constructor.
     *
     * @param CoreParametersHelper $coreParametersHelper
     */
    public function __construct(CoreParametersHelper $coreParametersHelper)
    {
        $this->coreParametersHelper = $coreParametersHelper;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Mautic\LeadBundle\Entity\LeadListRepository
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function getRepository()
    {
        /** @var \MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudienceRepository $repo */
        $repo = $this->em->getRepository('HubsFacebookAdsBundle:CustomAudience');

        return $repo;
    }

    /**
     * Returns the repository for the table that houses the leads associated with a list.
     *
     * @return \MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudienceRepository
     */
    public function getListLeadCustomAudienceRepository()
    {
        return $this->em->getRepository('HubsFacebookAdsBundle:ListLeadCustomAudience');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPermissionBase()
    {
        return 'facebookAds:addvertising';
    }

    /**
     * {@inheritdoc}
     *
     * @param      $entity
     * @param bool $unlock
     *
     * @return mixed|void
     */
    public function saveEntity($entity, $unlock = true)
    {
        $repo = $this->getRepository();
        $repo->saveEntity($entity, true);
    }

    /**
     * {@inheritdoc}
     *
     * @param       $entity
     * @param       $formFactory
     * @param null  $action
     * @param array $options
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof CustomAudience) {
            throw new MethodNotAllowedHttpException(['CustomAudience'], 'Entity must be of class CustomAudience()');
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(AddCustomAudienceType::class, $entity, $options);
    }

    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return null|object
     */
    public function getEntity($id = null)
    {
        if ($id === null) {
            return new CustomAudience();
        }

        $entity = parent::getEntity($id);

        return $entity;
    }

    /**
     * Rebuild lead lists.
     *
     * @param LeadList        $entity
     * @param int             $limit
     * @param OutputInterface $output
     *
     * @return int
     */
    public function rebuildCustomAudianceListLeads(CustomAudience $entity, $limit = 1000, OutputInterface $output = null)
    {
        $listId = $entity->getId();

        // Get a count of leads to add
        $leadData = $this->getCustomAudianceLeadsList(
                $listId, ['countOnly' => true, 'newOnly' => true]
        );

        // Number of total leads to process
        $leadCount = (int) $leadData[$listId]['count'];

        if ($output) {
            $output->writeln($this->translator->trans('hubs.customaudiance.list.rebuild.to_be_added', ['%leads%' => $leadCount, '%batch%' => $limit]));
        }
        // Handle by batches
        $start = $lastRoundPercentage = $leadsProcessed = 0;

        // Try to save some memory
        gc_enable();

        if ($leadCount) {
            $maxCount = $leadCount;

            if ($output) {
                $progress = ProgressBarHelper::init($output, $maxCount);
                $progress->start();
            }
            // Add leads
            while ($start < $leadCount) {
                // Keep CPU down for large lists; sleep per $limit batch
                $this->batchSleep();

                $newCAList = $this->getCustomAudianceLeadsList(
                        $listId, [
                    'newOnly' => true,
                    'limit'   => $limit,
                        ]
                );
                if (empty($newCAList[$listId])) {
                    // Somehow ran out of leads so break out
                    break;
                }

                foreach ($newCAList[$listId] as $l) {
                    $this->addLeadsToCustomAudience($l, $entity);

                    unset($l);

                    ++$leadsProcessed;
                    if ($output && $leadsProcessed < $maxCount) {
                        $progress->setProgress($leadsProcessed);
                    }
                }

                $start += $limit;

                // Dispatch batch event
                if ($this->dispatcher->hasListeners(CustomAudianceEvents::CUSTOM_AUDIENCE_ADD)) {
                    $event = new CustomAudianceChangeEvent($newCAList[$listId], $entity, true);
                    $this->dispatcher->dispatch(CustomAudianceEvents::CUSTOM_AUDIENCE_ADD, $event);

                    unset($event);
                }

                unset($newCAList);

                // Free some memory
                gc_collect_cycles();
            }

            if ($output) {
                $progress->finish();
                $output->writeln('');
            }
        }
        // Get a count of leads to be removed
        $removeLeadCount = $this->getCustomAudianceLeadsList(
                $listId, ['countOnly' => true, 'membersToRemove' => true]
        );

        // Restart batching
        $start     = $lastRoundPercentage     = 0;
        $leadCount = $removeLeadCount[$listId]['count'];

        if ($output) {
            $output->writeln($this->translator->trans('mautic.lead.list.rebuild.to_be_removed', ['%leads%' => $leadCount, '%batch%' => $limit]));
        }

        if ($leadCount) {
            $maxCount = $leadCount;

            if ($output) {
                $progress = ProgressBarHelper::init($output, $maxCount);
                $progress->start();
            }

            // Remove leads
            while ($start < $leadCount) {
                // Keep CPU down for large lists; sleep per $limit batch
                $this->batchSleep();

                $removeLeadList = $this->getCustomAudianceLeadsList(
                        $listId, [
                    // No start because the items are deleted so always 0
                    'limit'           => $limit,
                    'membersToRemove' => true,
                        ]
                );

                if (empty($removeLeadList[$listId])) {
                    // Somehow ran out of leads so break out
                    break;
                }

                foreach ($removeLeadList[$listId] as $l) {
                    $this->removeLeadsFromCustomAudience($l, $entity);

                    ++$leadsProcessed;
                    if ($output && $leadsProcessed < $maxCount) {
                        $progress->setProgress($leadsProcessed);
                    }
                }

                // Dispatch batch event
                if ($this->dispatcher->hasListeners(CustomAudianceEvents::CUSTOM_AUDIENCE_REMOVE)) {
                    $event = new CustomAudianceChangeEvent($removeLeadList[$listId], $entity, false);
                    $this->dispatcher->dispatch(CustomAudianceEvents::CUSTOM_AUDIENCE_REMOVE, $event);

                    unset($event);
                }

                $start += $limit;

                unset($removeLeadList);

                // Free some memory
                gc_collect_cycles();
            }

            if ($output) {
                $progress->finish();
                $output->writeln('');
            }
        }

        return $leadsProcessed;
    }

    /**
     * Add lead to customAudience.
     *
     * @param array|Lead           $lead
     * @param array|CustomAudience $customAudience
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function addLeadsToCustomAudience($lead, $customAudience)
    {
        if (!$lead instanceof \Mautic\LeadBundle\Entity\Lead) {
            $leadId    = (is_array($lead) && isset($lead['id'])) ? $lead['id'] : $lead;
            $leadEmail = (is_array($lead) && isset($lead['email'])) ? $lead['email'] : null;
            $lead      = $this->em->getReference('MauticLeadBundle:Lead', $leadId);
        } else {
            $leadId = $lead->getId();
        }

        if (!$customAudience instanceof CustomAudience) {
            //make sure they are ints
            $searchForLists = [];
            foreach ($customAudience as $k => &$l) {
                $l = (int) $l;
                if (!isset($this->customAudienceLists[$l])) {
                    $searchForLists[] = $l;
                }
            }

            if (!empty($searchForLists)) {
                $listEntities = $this->getEntities([
                    'filter' => [
                        'force' => [
                            [
                                'column' => 'l.id',
                                'expr'   => 'in',
                                'value'  => $searchForLists,
                            ],
                        ],
                    ],
                ]);

                foreach ($listEntities as $list) {
                    $this->customAudienceLists[$list->getId()] = $list;
                }
            }

            unset($listEntities, $searchForLists);
        } else {
            $this->customAudienceLists[$customAudience->getId()] = $customAudience;

            $customAudience = [$customAudience->getId()];
        }

        if (!is_array($customAudience)) {
            $customAudience = [$customAudience];
        }

        $persistLists   = [];
        $dispatchEvents = [];

        foreach ($customAudience as $customAudienceId) {
            if (!isset($this->customAudienceLists[$customAudienceId])) {
                // List no longer exists in the DB so continue to the next
                continue;
            }

            $customAudienceList = new \MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudience();
            $customAudienceList->setCustomAudience($this->customAudienceLists[$customAudienceId]);
            $customAudienceList->setLead($lead);
            $customAudienceList->setDateAdded(new \DateTime());
            $customAudienceList->setEmail($leadEmail);
            $customAudienceList->setIsRemoved(false);

            $persistLists[]   = $customAudienceList;
            $dispatchEvents[] = $customAudienceId;
        }

        if (!empty($persistLists)) {
            $this->getListLeadCustomAudienceRepository()->saveEntities($persistLists);
        }

        // Clear ListLead entities from Doctrine memory
        $this->em->clear('MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudience');

        unset($lead, $persistLists, $customAudience);
    }

    /**
     * Remove a lead from customAudience.
     *
     * @param   $leadList
     * @param   $customAudience
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeLeadsFromCustomAudience($leadList, $customAudience)
    {
        if (!$leadList instanceof \MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudience) {
            $leadListId = (is_array($leadList) && isset($leadList['id'])) ? $leadList['id'] : $leadList;
        } else {
            $leadListId = $leadList->getId();
        }

        if (!$customAudience instanceof CustomAudience) {
            //make sure they are ints
            $searchForLists = [];
            foreach ($customAudience as $k => &$l) {
                $l = (int) $l;
                if (!isset($this->customAudienceLists[$l])) {
                    $searchForLists[] = $l;
                }
            }

            if (!empty($searchForLists)) {
                $listEntities = $this->getEntities([
                    'filter' => [
                        'force' => [
                            [
                                'column' => 'l.id',
                                'expr'   => 'in',
                                'value'  => $searchForLists,
                            ],
                        ],
                    ],
                ]);

                foreach ($listEntities as $list) {
                    $this->customAudienceLists[$list->getId()] = $list;
                }
            }

            unset($listEntities, $searchForLists);
        } else {
            $this->customAudienceLists[$customAudience->getId()] = $customAudience;

            $customAudience = [$customAudience->getId()];
        }

        if (!is_array($customAudience)) {
            $customAudience = [$customAudience];
        }

        $deleteLists = [];

        foreach ($customAudience as $customAudienceId) {
            if (!isset($this->customAudienceLists[$customAudienceId])) {
                // List no longer exists in the DB so continue to the next
                continue;
            }

            $listLead = $this->getListLeadCustomAudienceRepository()->findOneById($leadListId);

            if ($listLead == null) {
                // Lead is not part of this list
                continue;
            }

            $deleteLists[] = $listLead;

            unset($listLead);
        }

        if (!empty($deleteLists)) {
            $this->getListLeadCustomAudienceRepository()->deleteEntities($deleteLists);
        }

        // Clear ListLead entities from Doctrine memory
        $this->em->clear('MauticPlugin\HubsFacebookAdsBundle\Entity\ListLeadCustomAudience');

        unset($leadList, $deleteLists);
    }

    /**
     * @param       $lists
     * @param bool  $idOnly
     * @param array $args
     *
     * @return mixed
     */
    public function getLeadsByList($lists, $idOnly = false, $args = [])
    {
        $args['idOnly'] = $idOnly;

        return $this->em->getRepository('MauticLeadBundle:LeadList')->getLeadsByList($lists, $args);
    }

    public function getCustomAudianceLeadsList($listid, $arg = [])
    {
        return $this->getListLeadCustomAudienceRepository()->getCustomAudianceLeadsList($listid, $arg);
    }

    /**
     * Batch sleep according to settings.
     */
    protected function batchSleep()
    {
        $leadSleepTime = $this->coreParametersHelper->getParameter('batch_lead_sleep_time', false);
        if ($leadSleepTime === false) {
            $leadSleepTime = $this->coreParametersHelper->getParameter('batch_sleep_time', 1);
        }

        if (empty($leadSleepTime)) {
            return;
        }

        if ($leadSleepTime < 1) {
            usleep($leadSleepTime * 1000000);
        } else {
            sleep($leadSleepTime);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param $action
     * @param $entity
     * @param $isNew
     * @param $event
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    protected function dispatchEvent($action, &$entity, $isNew = false, Event $event = null)
    {
        if (!$entity instanceof CustomAudience) {
            throw new MethodNotAllowedHttpException(['CustomAudiance'], 'Entity must be of class CustomAudiance()');
        }

        switch ($action) {
            case 'pre_delete':
                $name = CustomAudianceEvents::CUSTOM_AUDIENCE_PRE_DELETE;
                break;
            case 'post_delete':
                $name = CustomAudianceEvents::CUSTOM_AUDIENCE_POST_DELETE;
                break;
            default:
                return null;
        }

        if ($this->dispatcher->hasListeners($name)) {
            if (empty($event)) {
                $event = new CustomAudianceEvent($entity, $isNew);
            }
            $this->dispatcher->dispatch($name, $event);

            return $event;
        } else {
            return null;
        }
    }

    public function updateCustomAudianceToRemove($listId = false, $leadId = false)
    {
        return $this->getListLeadCustomAudienceRepository()->updateCustomAudianceToRemove($listId, $leadId);
    }
}
