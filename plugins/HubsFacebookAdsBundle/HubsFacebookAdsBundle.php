<?php
/**
 * @copyright   2015 Digital Innaovation Lab. All rights reserved
 * @author      arul
 *
 * @link        http://diginlab.com
 */
namespace MauticPlugin\HubsFacebookAdsBundle;

use Doctrine\ORM\Tools\SchemaTool;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Mautic\PluginBundle\Entity\Plugin;

class HubsFacebookAdsBundle extends PluginBundleBase
{
    /**
     * @param Plugin        $plugin
     * @param MauticFactory $factory
     * @param null          $metadata
     * @param null          $installedSchema
     *
     * @throws \Exception
     */
    public static function onPluginInstall(Plugin $plugin, MauticFactory $factory, $metadata = null, $installedSchema = null)
    {
        if ($metadata !== null) {
            self::installPluginSchema($metadata, $factory, $installedSchema);
        }
    }

    /**
     * Install plugin schema based on Doctrine metadata.
     *
     * @param array         $metadata
     * @param MauticFactory $factory
     * @param null          $installedSchema
     *
     * @throws \Exception
     */
    public static function installPluginSchema(array $metadata, MauticFactory $factory, $installedSchema = null)
    {
        if (null !== $installedSchema) {
            // Schema exists so bail
            return;
        }
        $db             = $factory->getDatabase();
        $schemaTool     = new SchemaTool($factory->getEntityManager());
        $installQueries = $schemaTool->getUpdateSchemaSql($metadata);
        $db->beginTransaction();
        try {
            foreach ($installQueries as $q) {
                $db->query($q);
            }

            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();

            throw $e;
        }
    }
}
