<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\HubsCoreBundle\InstallFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mautic\UserBundle\Entity\Role;

/**
 * Class RoleData
 */
class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $role       = new Role();
        $role->setName('Marketeer');
        $role->setDescription('Der ausführende Marketeer kann eigene Kampagnen planen und durchführen');
        $role->setIsAdmin(0);
        $manager->persist($role);

        $role       = new Role();
        $role->setName('Supermarketeer');
        $role->setDescription('Superuser Marketing für die Verwaltung aller Marketing Features, nicht aber der Benutzerverwaltung');
        $role->setIsAdmin(0);
        $manager->persist($role);


        $manager->flush();

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
