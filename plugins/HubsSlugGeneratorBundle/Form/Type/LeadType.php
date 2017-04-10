<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\HubsSlugGeneratorBundle\Form\Type;

use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\LeadBundle\Form\Type\LeadType as ParentFieldType;
use Mautic\LeadBundle\Model\CompanyModel;
use MauticPlugin\HubsSlugGeneratorBundle\Helper\SlugGeneratorHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class FieldType.
 */
class LeadType extends ParentFieldType
{
    protected $slugHelper;

    /**
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory, CompanyModel $companyModel, SlugGeneratorHelper $slugHelper)
    {
        parent::__construct($factory, $companyModel);
        $this->slugHelper = $slugHelper;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $slugFieldName = $this->slugHelper->getSlugFieldName();
        if (!$slugFieldName) {
            return;
        }
        $slugHelper = $this->slugHelper;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($slugFieldName) {
            $form = $event->getForm();
            $formData = $event->getData();
            if ($form->has($slugFieldName) && $formData->__get($slugFieldName)) {
                $form->remove($slugFieldName);
                $form->add($slugFieldName, 'text', [
                        'label' => $slugFieldName,
                        'attr'  => [
                            'class' => 'form-control',
                        ],
                        'label_attr' => ['class' => 'control-label'],
                        'data'       => $formData->__get($slugFieldName),
                        'disabled'   => 'disabled',
                        'required'   => false,
                    ]
                );
            }
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($slugHelper, $slugFieldName) {
            $form = $event->getForm();
            $formData = $form->getData();
            $data = $event->getData();
            if ($form->has($slugFieldName) && (isset($data[$slugFieldName]) && $data[$slugFieldName])) {
                if (preg_match('/[^A-Za-z0-9_\-$]/', $data[$slugFieldName])) {
                    $form->addError(new \Symfony\Component\Form\FormError('Slug is not valid'));
                }
                if ($slugHelper->isContactSlugExists($data[$slugFieldName], $formData->getId())) {
                    $form->get($slugFieldName)->addError(new \Symfony\Component\Form\FormError('Slug already exists'));
                    $form->addError(new \Symfony\Component\Form\FormError('Slug already exists'));
                }
            }
        }
        );
    }
}
