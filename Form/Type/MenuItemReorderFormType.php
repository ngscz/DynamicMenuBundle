<?php
/**
 *
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemReorderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'disabled' => true,
                'attr' => [
                    'class' => 'menu-item-id',
                ],
            ])
            ->add('parent', 'entity', [
                'class' => 'DynamicMenuBundle:MenuItem',
                'property' => 'label',
                'attr' => [
                    'class' => 'menu-item-parent-id'
                ],
            ])
            ->add('left', 'integer', [
                'attr' => [
                    'class' => 'menu-item-left'
                ],
            ])
            ->add('right', 'integer', [
                'attr' => [
                    'class' => 'menu-item-right'
                ],
            ])
            ->add('level', 'integer', [
                'attr' => [
                    'class' => 'menu-item-level'
                ],
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SymfonyContrib\Bundle\DynamicMenuBundle\Entity\MenuItem',
        ]);
    }

    public function getName()
    {
        return 'menu_item_reorder';
    }
}
