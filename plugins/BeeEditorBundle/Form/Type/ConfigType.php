<?php

namespace MauticPlugin\BeeEditorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bee_client_id', 'text', [
                'label'      => '55hubs.bee.clientid',
                'required'   => false,
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
        ]);
        $builder->add('bee_client_secret', 'text', [
            'label'      => '55hubs.bee.clientid',
            'required'   => false,
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
        ]);
    }

    public function getName()
    {
        return 'bee_config';
    }
}
