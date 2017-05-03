<?php

namespace MauticPlugin\BeeEditorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bee_client_id', 'password', [
                'label'      => '55hubs.bee.clientid',
                'required'   => false,
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'        => 'form-control',
                    'placeholder'  => 'mautic.user.user.form.passwordplaceholder',
                    'preaddon'     => 'fa fa-lock',
                    'autocomplete' => 'off',
                ],
        ]);
        $builder->add('bee_client_secret', 'password', [
            'label'      => '55hubs.bee.clientsecret',
            'required'   => false,
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                    'class'        => 'form-control',
                    'placeholder'  => 'mautic.user.user.form.passwordplaceholder',
                    'preaddon'     => 'fa fa-lock',
                    'autocomplete' => 'off',
                ],
        ]);
        $builder->add('bee_uid', 'text', [
            'label'      => '55hubs.bee.uid',
            'required'   => false,
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                    'class' => 'form-control',
                ],
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\Regex(['pattern' => '/^[a-zA-Z_-]+$/i']),
            ],
        ]);
    }

    public function getName()
    {
        return 'bee_config';
    }
}
