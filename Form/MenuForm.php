<?php
/**
 *
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('label', 'text', [
                'required' => false,
            ])
            ->add('enabled', 'checkbox', [
                'required' => false,
            ])
            ->add('save', 'submit', [
                'attr' => [
                    'class' => 'btn-success',
                ],
            ])
            ->add('cancel', 'button', [
                'url' => $options['cancel_url'],
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SymfonyContrib\Bundle\DynamicMenuBundle\Entity\MenuItem',
            'cancel_url' => '/',
        ]);
    }

    public function getName()
    {
        return 'menu_form';
    }
}
