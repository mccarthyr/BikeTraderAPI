<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/28/15
 * Time: 3:23 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BicycleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add('name', 'text')
            -> add('description', 'text')
            -> add('type', 'text')
            -> add('save', 'submit', array('label' => 'Create Bike'));
    }

    /**
     * @return string - a unique identifier for this form 'type'
     */
    public function getName()
    {
        return 'createBike';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        /**
         * The 'data_class' option is left out here as the Entity that is associated
         * this form type is changed depending on the backend storage system that is
         * configured in the services file, e.g. ORM or ODM (Mongo).
         */
        $resolver -> setDefaults(array(
            'csrf_protection' => false
        ));
    }
}







