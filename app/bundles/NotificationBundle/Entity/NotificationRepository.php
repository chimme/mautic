<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\NotificationBundle\Entity;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class NotificationRepository.
 */
class NotificationRepository extends CommonRepository
{
    /**
     * Get a list of entities.
     *
     * @param array $args
     *
     * @return Paginator
     */
    public function getEntities($args = [])
    {
        $q = $this->_em
            ->createQueryBuilder()
            ->select('e')
            ->from('MauticNotificationBundle:Notification', 'e', 'e.id');
        if (empty($args['iterator_mode'])) {
            $q->leftJoin('e.category', 'c');
        }

        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * Get amounts of sent and read notifications.
     *
     * @return array
     */
    public function getSentReadCount()
    {
        $q = $this->_em->createQueryBuilder();
        $q->select('SUM(e.sentCount) as sent_count, SUM(e.readCount) as read_count')
            ->from('MauticNotificationBundle:Notification', 'e');
        $results = $q->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

        if (!isset($results['sent_count'])) {
            $results['sent_count'] = 0;
        }
        if (!isset($results['read_count'])) {
            $results['read_count'] = 0;
        }

        return $results;
    }

    /**
     * @param QueryBuilder $q
     * @param              $filter
     *
     * @return array
     */
    protected function addSearchCommandWhereClause(&$q, $filter)
    {
        $command         = $filter->command;
        $unique          = $this->generateRandomParameterName();
        $returnParameter = true; //returning a parameter that is not used will lead to a Doctrine error
        $expr            = false;
        switch ($command) {
            case $this->translator->trans('mautic.core.searchcommand.ispublished'):
                $expr            = $q->expr()->eq('e.isPublished', ":$unique");
                $forceParameters = [$unique => true];
                break;
            case $this->translator->trans('mautic.core.searchcommand.isunpublished'):
                $expr            = $q->expr()->eq('e.isPublished', ":$unique");
                $forceParameters = [$unique => true];
                break;
            case $this->translator->trans('mautic.core.searchcommand.isuncategorized'):
                $expr = $q->expr()->orX(
                    $q->expr()->isNull('e.category'),
                    $q->expr()->eq('e.category', $q->expr()->literal(''))
                );
                $returnParameter = false;
                break;
            case $this->translator->trans('mautic.core.searchcommand.ismine'):
                $expr            = $q->expr()->eq('IDENTITY(e.createdBy)', $this->currentUser->getId());
                $returnParameter = false;
                break;
            case $this->translator->trans('mautic.core.searchcommand.category'):
                $expr           = $q->expr()->like('e.alias', ":$unique");
                $filter->strict = true;
                break;
            case $this->translator->trans('mautic.core.searchcommand.lang'):
                $langUnique      = $this->generateRandomParameterName();
                $langValue       = $filter->string.'_%';
                $forceParameters = [
                    $langUnique => $langValue,
                    $unique     => $filter->string,
                ];
                $expr = $q->expr()->orX(
                    $q->expr()->eq('e.language', ":$unique"),
                    $q->expr()->like('e.language', ":$langUnique")
                );
                break;
        }

        if ($expr && $filter->not) {
            $expr = $q->expr()->not($expr);
        }

        if (!empty($forceParameters)) {
            $parameters = $forceParameters;
        } elseif (!$returnParameter) {
            $parameters = [];
        } else {
            $string     = ($filter->strict) ? $filter->string : "%{$filter->string}%";
            $parameters = ["$unique" => $string];
        }

        return [$expr, $parameters];
    }

    /**
     * @return array
     */
    public function getSearchCommands()
    {
        return [
            'mautic.core.searchcommand.ispublished',
            'mautic.core.searchcommand.isunpublished',
            'mautic.core.searchcommand.isuncategorized',
            'mautic.core.searchcommand.ismine',
            'mautic.core.searchcommand.category',
            'mautic.core.searchcommand.lang',
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultOrder()
    {
        return [
            ['e.name', 'ASC'],
        ];
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'e';
    }

    /**
     * Up the click/sent counts.
     *
     * @param        $id
     * @param string $type
     * @param int    $increaseBy
     */
    public function upCount($id, $type = 'sent', $increaseBy = 1)
    {
        try {
            $q = $this->_em->getConnection()->createQueryBuilder();

            $q->update(MAUTIC_TABLE_PREFIX.'push_notifications')
                ->set($type.'_count', $type.'_count + '.(int) $increaseBy)
                ->where('id = '.(int) $id);

            $q->execute();
        } catch (\Exception $exception) {
            // not important
        }
    }

    /**
     * @param string $search
     * @param int    $limit
     * @param int    $start
     * @param bool   $viewOther
     * @param string $notificationType
     *
     * @return array
     */
    public function getNotificationList($search = '', $limit = 10, $start = 0, $viewOther = false, $notificationType = null)
    {
        $q = $this->createQueryBuilder('e');
        $q->select('partial e.{id, name, language}');

        if (!empty($search)) {
            $q->andWhere($q->expr()->like('e.name', ':search'))
                ->setParameter('search', "{$search}%");
        }

        if (!$viewOther) {
            $q->andWhere($q->expr()->eq('e.createdBy', ':id'))
                ->setParameter('id', $this->currentUser->getId());
        }

        if (!empty($notificationType)) {
            $q->andWhere(
                $q->expr()->eq('e.notificationType', $q->expr()->literal($notificationType))
            );
        }

        $q->orderBy('e.name');

        if (!empty($limit)) {
            $q->setFirstResult($start)
                ->setMaxResults($limit);
        }

        return $q->getQuery()->getArrayResult();
    }
}
