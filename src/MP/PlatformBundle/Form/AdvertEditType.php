<?php
/**
 * Created by PhpStorm.
 * User: melpe
 * Date: 15/01/2018
 * Time: 19:15
 */

namespace MP\PlatformBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvertEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('date');
    }

    public function getParent()
    {
        return AdvertType::class;
    }
}