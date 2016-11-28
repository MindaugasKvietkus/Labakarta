<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateUserForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name_surname', TextType::class, array(
            'label' => 'VardasPavardė*'
        ))
            ->add('email', EmailType::class, array(
                'label' => 'El.paštas*'
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'label' => false,
                'first_name' => 'Slaptazodis',
                'second_name' => 'Pakartotislaptazodi'
            ))
        ->add('register', SubmitType::class, array(
            'label' => 'REGISTRUOTIS'
        ));
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CreateUserVariables'
        ));
        //parent::configureOptions($resolver); // TODO: Change the autogenerated stub
    }

    public function getName(){

        return 'create_user';
    }
}
/**
 * Created by PhpStorm.
 * User: Mariukas
 * Date: 2016.11.11
 * Time: 10:28
 */