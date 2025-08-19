<?php

namespace App\Form;

use App\Entity\Farm;
use App\Entity\Veterinarian;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FarmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nome da Fazenda'])
            ->add('size', null, ['label' => 'Tamanho em (HA)'])
            ->add('responsible', null, ['label' => 'Responsável'])
            ->add('veterinarian', EntityType::class, [
                'class' => Veterinarian::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Veterinários',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Farm::class,
        ]);
    }
}
