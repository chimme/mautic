<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
        $builder->add('list', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
            'class'         => \Mautic\LeadBundle\Entity\LeadList::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('list')
                                ->leftJoin('HubsFacebookAdsBundle:CustomAudience', 'ca', 'WITH', 'ca.list = list.id')
                                ->where('ca.id is null');
            },
            'choice_label' => 'name',
            'label'        => 'hubs.fbAds.label.addCustomAudience',
            'label_attr'   => ['class' => 'control-label'],
            'attr'         => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\NotBlank(),
            ],
            'multiple' => false,
            'expanded' => false,
        ]);
        $builder->add(
                'description', 'textarea', [
            'label'      => 'mautic.core.description',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control editor'],
            'required'   => false,
                ]
        );
        $builder->add('buttons', 'form_buttons');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_custom_audience';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
                [
                    'data_class' => 'MauticPlugin\HubsFacebookAdsBundle\Entity\CustomAudience',
                ]
        );
    }
}
