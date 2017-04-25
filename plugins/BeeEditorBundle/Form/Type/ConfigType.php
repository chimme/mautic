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
                    'tooltip'      => 'mautic.email.config.mailer.password.tooltip',
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
                    'tooltip'      => 'mautic.email.config.mailer.password.tooltip',
                    'autocomplete' => 'off',
                ],
        ]);
    }

    public function getName()
    {
        return 'bee_config';
    }
}
