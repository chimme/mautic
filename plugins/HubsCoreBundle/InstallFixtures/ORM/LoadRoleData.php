<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace MauticPlugin\HubsCoreBundle\InstallFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mautic\UserBundle\Entity\Role;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RoleData.
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
        $role = new Role();
        $role->setName('Marketeer');
        $role->setDescription('Der ausführende Marketeer kann eigene Kampagnen planen und durchführen');
        $role->setRawPermissions(unserialize('a:16:{s:16:"asset:categories";a:1:{i:0;s:4:"full";}s:12:"asset:assets";a:1:{i:0;s:4:"full";}s:19:"campaign:categories";a:1:{i:0;s:4:"full";}s:18:"campaign:campaigns";a:1:{i:0;s:4:"full";}s:19:"category:categories";a:1:{i:0;s:4:"view";}s:10:"lead:leads";a:1:{i:0;s:4:"full";}s:10:"lead:lists";a:1:{i:0;s:9:"viewother";}s:16:"email:categories";a:1:{i:0;s:4:"full";}s:12:"email:emails";a:1:{i:0;s:4:"full";}s:15:"form:categories";a:1:{i:0;s:4:"full";}s:10:"form:forms";a:1:{i:0;s:4:"full";}s:15:"page:categories";a:1:{i:0;s:4:"full";}s:10:"page:pages";a:1:{i:0;s:4:"full";}s:12:"point:points";a:1:{i:0;s:4:"view";}s:14:"point:triggers";a:1:{i:0;s:4:"view";}s:14:"report:reports";a:2:{i:0;s:7:"viewown";i:1;s:9:"viewother";}}'));
        $role->setIsAdmin(0);
        $manager->persist($role);

        $role = new Role();
        $role->setName('Supermarketeer');
        $role->setDescription('Superuser Marketing für die Verwaltung aller Marketing Features, nicht aber der Benutzerverwaltung');
        $role->setRawPermissions(unserialize('a:18:{s:16:"asset:categories";a:1:{i:0;s:4:"full";}s:12:"asset:assets";a:1:{i:0;s:4:"full";}s:19:"campaign:categories";a:1:{i:0;s:4:"full";}s:18:"campaign:campaigns";a:1:{i:0;s:4:"full";}s:19:"category:categories";a:1:{i:0;s:4:"full";}s:10:"lead:leads";a:1:{i:0;s:4:"full";}s:10:"lead:lists";a:1:{i:0;s:4:"full";}s:11:"lead:fields";a:1:{i:0;s:4:"full";}s:16:"email:categories";a:1:{i:0;s:4:"full";}s:12:"email:emails";a:1:{i:0;s:4:"full";}s:15:"form:categories";a:1:{i:0;s:4:"full";}s:10:"form:forms";a:1:{i:0;s:4:"full";}s:15:"page:categories";a:1:{i:0;s:4:"full";}s:10:"page:pages";a:1:{i:0;s:4:"full";}s:16:"point:categories";a:1:{i:0;s:4:"full";}s:12:"point:points";a:1:{i:0;s:4:"full";}s:14:"point:triggers";a:1:{i:0;s:4:"full";}s:14:"report:reports";a:1:{i:0;s:4:"full";}}'));
        $role->setIsAdmin(0);
        $manager->persist($role);

        $role = new Role();
        $role->setName('Demo');
        $role->setDescription('Demo users');
        $role->setRawPermissions(unserialize('a:22:{s:16:"asset:categories";a:1:{i:0;s:4:"full";}s:12:"asset:assets";a:1:{i:0;s:4:"full";}s:19:"campaign:categories";a:1:{i:0;s:4:"full";}s:18:"campaign:campaigns";a:1:{i:0;s:4:"full";}s:19:"category:categories";a:1:{i:0;s:4:"full";}s:10:"lead:leads";a:1:{i:0;s:4:"full";}s:10:"lead:lists";a:1:{i:0;s:4:"full";}s:11:"lead:fields";a:1:{i:0;s:4:"full";}s:16:"email:categories";a:1:{i:0;s:4:"full";}s:12:"email:emails";a:1:{i:0;s:4:"full";}s:15:"form:categories";a:1:{i:0;s:4:"full";}s:10:"form:forms";a:1:{i:0;s:4:"full";}s:15:"page:categories";a:1:{i:0;s:4:"full";}s:10:"page:pages";a:1:{i:0;s:4:"full";}s:16:"point:categories";a:1:{i:0;s:4:"full";}s:12:"point:points";a:1:{i:0;s:4:"full";}s:14:"point:triggers";a:1:{i:0;s:4:"full";}s:14:"report:reports";a:1:{i:0;s:4:"full";}s:12:"stage:stages";a:1:{i:0;s:4:"full";}s:10:"user:users";a:1:{i:0;s:4:"view";}s:10:"user:roles";a:1:{i:0;s:4:"view";}s:12:"user:profile";a:1:{i:0;s:4:"full";}}'));
        $role->setIsAdmin(0);
        $manager->persist($role);

        $role = new Role();
        $role->setName('Kundenbeziehung');
        $role->setDescription('Unterstützende Rolle für die Pflege von Kundenbeziehungen, Event Hostessen oder Call Center Mitarbeiter');
        $role->setRawPermissions(unserialize('a:5:{s:16:"asset:categories";a:1:{i:0;s:4:"view";}s:12:"asset:assets";a:2:{i:0;s:7:"viewown";i:1;s:9:"viewother";}s:10:"lead:leads";a:2:{i:0;s:7:"viewown";i:1;s:9:"viewother";}s:10:"lead:lists";a:1:{i:0;s:9:"viewother";}s:14:"report:reports";a:2:{i:0;s:7:"viewown";i:1;s:9:"viewother";}}'));
        $role->setIsAdmin(0);
        $manager->persist($role);

        $role = new Role();
        $role->setName('API');
        $role->setDescription('API Vollzugriff');
        $role->setRawPermissions(unserialize('a:2:{s:10:"api:access";a:1:{i:0;s:4:"full";}s:11:"api:clients";a:1:{i:0;s:4:"full";}}'));
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
