<?php

namespace MauticPlugin\BeeEditorBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class BeeEditorIntegration extends AbstractIntegration
{
    const PLUGIN_NAME = 'BeeEditor';

    public function getName()
    {
        return self::PLUGIN_NAME;
    }

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
        if ($formArea !== 'keys') {
            return;
        }
        $builder->add(
                'uid', 'text', [
                    'label'      => '55hubs.bee.uid',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class' => 'form-control',
                    ],
                    'data'        => (isset($data['uid'])) ? $data['uid'] : false,
                    'constraints' => [new NotBlank()],
                ]
        );
        $builder->add(
                'clientid', 'password', [
                    'label'      => '55hubs.bee.clientid',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'       => 'form-control',
                        'placeholder' => '**************',
                    ],
                    'constraints' => [new NotBlank()],
                ]
        );
        $builder->add(
                'clientsecret', 'password', [
                    'label'      => '55hubs.bee.clientsecret',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'       => 'form-control',
                        'placeholder' => '**************',
                    ],
                    'constraints' => [new NotBlank()],
                ]
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (\Symfony\Component\Form\FormEvent $event) use ($data) {
            $form = $event->getForm();
            $form->add('clientid', 'password', [
                    'label'      => '55hubs.bee.clientid',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'       => 'form-control',
                        'placeholder' => '**************',
                    ],
                    'required'       => true,
                    'constraints'    => !isset($data['clientid']) ? [new NotBlank()] : [],
                    'data'           => isset($data['clientid']) ? $data['clientid'] : false,
                    'error_bubbling' => false,
                ]
            )->add('clientsecret', 'password', [
                    'label'      => '55hubs.bee.clientsecret',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'       => 'form-control',
                        'placeholder' => '**************',
                    ],
                    'required'       => true,
                    'constraints'    => !isset($data['clientsecret']) ? [new NotBlank()] : [],
                    'data'           => isset($data['clientsecret']) ? $data['clientsecret'] : false,
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
        return ['clientid', 'clientsecret'];
    }
}
