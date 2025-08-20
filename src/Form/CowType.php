<?php

namespace App\Form;

use App\Entity\Cow;
use App\Entity\Farm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class,  [
                'attr' => [
                    'class' => 'input-code',
                    'placeholder' => 'FAZ-2025-00000',
                ],
                'label' => 'Código',
                'required' => true
            ])
            ->add('milk', null, ['label' => 'Leite produzido por semana (em litros)'])
            ->add('food', null, ['label' => 'Ração consumido por semana (em quilogramas)'])
            ->add('weight', null, ['label' => 'Peso (em quilogramas)'])
            ->add('birth', null, ['label' => 'Data de nascimento'])
            ->add('slaughtered', CheckboxType::class, [
                'label'    => 'Animal abatido?',
                'required' => false,
            ])
            ->add('farm', EntityType::class, [
                'class' => Farm::class,
                'choice_label' => 'name',
                'label' => 'Fazenda',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cow::class,
        ]);
    }
}
