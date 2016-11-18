<?php
/**
 * Created by PhpStorm.
 * User: Mariukas
 * Date: 2016.11.17
 * Time: 13:59
 */

namespace AppBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminUserSearchForm
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name_surname', TextType::class, array(
            'label'=> 'VardasPavarde',
        ))->add('email', TextType::class, array(
            'label' => 'Elpastas'
        ))->add('search', SubmitType::class, array(
            'label' => 'Ieskoti'
        ));
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdminUserSearchVariables'
        ));
        //parent::configureOptions($resolver); // TODO: Change the autogenerated stub
    }

    public function getName(){

        return 'reset';

    }
}