<?php

namespace App\Form;

use App\Entity\Veterinarian;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VeterinarianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nome do Veterinário'])
            ->add('crmv', TextType::class, [
                'attr' => [
                    'class' => 'input-crmv',
                    'placeholder' => 'CRMV-MG-00000',
                ],
                'label' => 'CRMV'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Veterinarian::class,
        ]);
    }
}
