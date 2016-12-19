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
use Mautic\UserBundle\Entity\Permission;
use Mautic\UserBundle\Entity\Role;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadPermissionData.
 */
class LoadPermissionData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $translator    = $this->container->get('translator');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $datas = [
            '2' => [
                        [
                            'bundle'  => 'asset',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'asset',
                            'name'    => 'assets',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'campaigns',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'category',
                            'name'    => 'categories',
                            'bitwise' => 4,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'leads',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'lists',
                            'bitwise' => 2,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'emails',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'forms',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'pages',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'points',
                            'bitwise' => 4,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'triggers',
                            'bitwise' => 4,
                        ],
                        [
                            'bundle'  => 'report',
                            'name'    => 'reports',
                            'bitwise' => 6,
                        ],
            ],
            '3' => [
                        [
                            'bundle'  => 'asset',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'asset',
                            'name'    => 'assets',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'campaigns',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'category',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'leads',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'lists',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'fields',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'emails',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'forms',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'pages',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'points',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'triggers',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'report',
                            'name'    => 'reports',
                            'bitwise' => 1024,
                        ],
            ],
            '4' => [
                        [
                            'bundle'  => 'asset',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'asset',
                            'name'    => 'assets',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'campaign',
                            'name'    => 'campaigns',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'category',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'leads',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'lists',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'lead',
                            'name'    => 'fields',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'email',
                            'name'    => 'emails',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'form',
                            'name'    => 'forms',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'page',
                            'name'    => 'pages',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'categories',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'points',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'point',
                            'name'    => 'triggers',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'report',
                            'name'    => 'reports',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'stage',
                            'name'    => 'stages',
                            'bitwise' => 1024,
                        ],
                        [
                            'bundle'  => 'user',
                            'name'    => 'users',
                            'bitwise' => 4,
                        ],
                        [
                            'bundle'  => 'user',
                            'name'    => 'roles',
                            'bitwise' => 4,
                        ],
                        [
                            'bundle'  => 'user',
                            'name'    => 'profile',
                            'bitwise' => 1024,
                        ],
            ],
        ];

        foreach ($datas as $key => $value) {
            $role = $entityManager->getRepository('MauticUserBundle:Role')->find((int) $key);
            foreach ($value as $k => $val) {
                $permission = new Permission();
                $permission->setRole($role);
                $permission->setBundle($val['bundle']);
                $permission->setName($val['name']);
                $permission->setBitwise($val['bitwise']);
                $manager->persist($permission);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
