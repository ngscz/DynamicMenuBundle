<?php
/**
 *
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SymfonyContrib\Bundle\DynamicMenuBundle\Form\Type\MenuItemReorderFormType;

class MenuReorderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', 'collection', [
                'type' => new MenuItemReorderFormType(),
                'label' => false,
            ])
            ->add('save', 'submit', [
                'attr' => [
                    'class' => 'btn-success',
                ],
            ])
            ->add('reset', 'button', [
                'url' => $options['cancel_url'],
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'cancel_url' => '/',
        ]);
    }

    public function getName()
    {
        return 'menu_reorder_form';
    }
}
