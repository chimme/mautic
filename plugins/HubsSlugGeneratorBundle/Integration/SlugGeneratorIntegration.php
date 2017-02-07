<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;

class SlugGeneratorIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'SlugGenerator';
    }

    /**
     * Return's authentication method such as oauth2, oauth1a, key, etc.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * @param FormBuilder|Form $builder
     * @param array            $data
     * @param string           $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        $builder->add(
                'slugfield',
                'leadfields_choices',
                [
                    'label' => 'mautic.plugin.leadfield',
                    'data'  => (isset($data['slugfield'])) ? $data['slugfield'] : false,
                ]
            );
    }
}
