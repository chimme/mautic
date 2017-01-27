<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\HubsFacebookAdsBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class ListLeadRepository.
 */
class ListLeadCustomAudienceRepository extends CommonRepository
{
    public function getCustomAudianceLeadsList($lists, $args)
    {
        $countOnly       = (!array_key_exists('countOnly', $args)) ? false : $args['countOnly'];
        $idOnly          = (!array_key_exists('idOnly', $args)) ? false : $args['idOnly'];
        $limit           = (!array_key_exists('limit', $args)) ? false : $args['limit'];
        $newOnly         = (!array_key_exists('newOnly', $args)) ? false : $args['newOnly'];
        $includeEmail    = (!array_key_exists('includeEmail', $args)) ? false : $args['includeEmail'];
        $membersToRemove = (!array_key_exists('membersToRemove', $args)) ? false : $args['membersToRemove'];
        if (!$lists instanceof PersistentCollection && !is_array($lists) || isset($lists['id'])) {
            $lists = [$lists];
        }
        $return = [];
        foreach ($lists as $l) {
            $leads = ($countOnly) ? 0 : [];
            if ($l instanceof CustomAudience) {
                $id = $l->getId();
            } elseif (is_array($l)) {
                $id = $l['id'];
            } else {
                $id = $l;
            }
            $query = $this->_em->getConnection()->createQueryBuilder();
            if ($countOnly) {
                $select = 'count(l.id) as leadCount, max(l.id) as max_id';
            } elseif ($idOnly) {
                $select = 'l.id';
                if ($includeEmail) {
                    $select .= ', l.email';
                }
            } else {
                $select = 'l.*';
            }
            $query->select($select)
            ;
            if ($newOnly) {
                $query->from(MAUTIC_TABLE_PREFIX.'leads', 'l')
                        ->join('l', MAUTIC_TABLE_PREFIX.'lead_lists_leads', 'll', $query->expr()->eq('ll.lead_id', 'l.id'))
                        ->join('l', MAUTIC_TABLE_PREFIX.'custom_audience', 'ca', $query->expr()->eq('ca.leadlist_id', 'll.leadlist_id'));
                $caExpr = $query->expr()->andX(
                        $query->expr()->eq('cs.custom_audience_id', 'ca.id'), $query->expr()->eq('cs.lead_id', 'l.id')
                );
                $query->leftJoin('l', MAUTIC_TABLE_PREFIX.'custom_audience_lead_list', 'cs', $caExpr);
                $expr = $query->expr()->andX(
                        $query->expr()->eq('ca.id', ':customAudienceId'), $query->expr()->isNull('cs.lead_id')
                );
                $query->setParameter('customAudienceId', (int) $id)
                        ->setParameter('false', false, 'boolean');
                $query->where($expr);
                if (!empty($limit)) {
                    $query->setMaxResults($limit);
                }
                $results = $query->execute()->fetchAll();
            } elseif ($membersToRemove) {
                $query->from(MAUTIC_TABLE_PREFIX.'custom_audience_lead_list', 'l')
                        ->join('l', MAUTIC_TABLE_PREFIX.'custom_audience', 'ca', $query->expr()->eq('ca.id', 'l.custom_audience_id'));
                $expr = $query->expr()->andX(
                        $query->expr()->eq('ca.id', ':customAudienceId'), $query->expr()->eq('cs.is_removed', 1)
                );
                $query->setParameter('customAudienceId', (int) $id);
                $query->where($expr);
                if (!empty($limit)) {
                    $query->setMaxResults($limit);
                }
                $results = $query->execute()->fetchAll();
            }
            foreach ($results as $r) {
                if ($countOnly) {
                    $leads = [
                        'count' => $r['leadCount'],
                        'maxId' => $r['max_id'],
                    ];
                } elseif ($idOnly) {
                    $leads[] = $r['id'];
                } else {
                    $leads[] = $r;
                }
            }
            $return[$id] = $leads;
            unset($query, $expr, $results, $leads);
        }

        return $return;
    }

    public function updateCustomAudianceToRemove($listId = false, $leadId = false)
    {
        if (!$listId && !$leadId) {
            return;
        }
        if ($listId) {
            $customAudiance = $this->getEntityManager()->getRepository('HubsFacebookAdsBundle:CustomAudience')
                    ->findOneByList($listId);
            if ($customAudiance) {
                return;
            }
        }
        $q = $this->_em->getConnection()->createQueryBuilder();
        $q->update(MAUTIC_TABLE_PREFIX.'custom_audience_lead_list')
                ->set('is_removed', 1);
        if ($listId) {
            $q->where('custom_audience_id = '.$customAudiance->getId());
        }
        if ($leadId) {
            $q->andWhere('lead_id = '.$leadId);
        }
        $q->execute();
    }
}
