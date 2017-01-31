<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class FacebookAdsIntegration extends AbstractIntegration
{
    const PLUGIN_NAME = 'FacebookAds';

    public function getName()
    {
        return self::PLUGIN_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticationType()
    {
        return 'oauth2';
    }

    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea != 'keys') {
            return false;
        }
        $builder->add('add_account_id', 'password', [
            'label'      => 'add acc id',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => '**************',
            ],
            'required'    => true,
            'constraints' => [
                new NotBlank(),
            ],
            'error_bubbling' => false,
                ]
        )->add('access_token', 'password', [
            'label'      => 'access_token',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => '**************',
            ],
            'required'    => true,
            'constraints' => [
                new NotBlank(),
            ],
            'error_bubbling' => false,
                ]
        );
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (\Symfony\Component\Form\FormEvent $event) use ($data) {
            $form = $event->getForm();
            $form->add('add_account_id', 'password', [
                'label'      => 'add acc id',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => '**************',
                ],
                'required'       => true,
                'constraints'    => !isset($data['add_account_id']) ? [new NotBlank()] : [],
                'data'           => isset($data['add_account_id']) ? $data['add_account_id'] : false,
                'error_bubbling' => false,
                    ]
            )->add('access_token', 'password', [
                'label'      => 'access_token',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => '**************',
                ],
                'required'       => true,
                'constraints'    => !isset($data['access_token']) ? [new NotBlank()] : [],
                'data'           => isset($data['access_token']) ? $data['access_token'] : false,
                'error_bubbling' => false,
                    ]
            );
        });
    }

    /**
     * Array of keys to mask in the config form.
     *
     * @return array
     */
    public function getSecretKeys()
    {
        return [$this->getClientSecretKey(), 'access_token', 'add_account_id'];
    }
}
