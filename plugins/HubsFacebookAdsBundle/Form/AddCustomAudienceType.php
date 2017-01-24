<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of AddCustomAudienceType.
 *
 * @author arul
 */
class AddCustomAudienceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('addToLists', 'leadlist_choices', [
            'label'      => 'hubs.fbAds.label.addCustomAudience',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\NotBlank(),
            ],
            'multiple' => false,
            'expanded' => false,
        ]);
        $builder->add('buttons', 'form_buttons');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_custom_audience';
    }
}
