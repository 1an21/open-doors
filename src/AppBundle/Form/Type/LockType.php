<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 23.01.2018
 * Time: 19:00
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lock_name', TextType::class)
            ->add('lock_pass', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Lock',
            'allow_extra_fields' => true,
        ]);
    }

    public function getName()
    {
        return 'lock';
    }
}