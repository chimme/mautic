<?php

namespace MauticPlugin\HubsSlugGeneratorBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;

class SlugGeneratorIntegration extends AbstractIntegration
{
    const PLUGIN_NAME = 'SlugGenerator';

    public function getName()
    {
        return self::PLUGIN_NAME;
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
        if ($formArea === 'keys') {
            $builder->add(
                    'slugfield', 'leadfields_choices', [
                'label'       => 'hubs.plugin.sluggenerator.leadfield',
                'data'        => (isset($data['slugfield'])) ? $data['slugfield'] : false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ],
                    ]
            );
        }
    }
}
