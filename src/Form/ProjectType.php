<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\TeamMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('teamMembers', CollectionType::class, [
                'entry_type'=>TeammemberType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'required'=>false,
                'by_reference'=>false,
                'disabled'=>false,
                'prototype'=>true,
                'label'=>false
            ])        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
