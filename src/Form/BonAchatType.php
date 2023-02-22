<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use App\Entity\BonAchat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

class BonAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('montant', null, [
            'label' => 'Montant',
            'attr' => ['placeholder' => 'Montant'],
            'constraints' => [
                new PositiveOrZero([
                    'message' => 'Le montant doit être positif ou zéro.'
                ]),
                new Range([
                    'min' => 100,
                    'max' => 500,
                    'notInRangeMessage' => 'Le montant doit être entre {{ min }} et {{ max }}.'
                ])
            ]
        ])
        ->add('date_exp', DateType::class, [
            'widget' => 'single_text',
            'attr' => ['min' => (new \DateTime())->format('Y-m-d')],
        ])
        ->add('description')
            
            ->add('convention')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BonAchat::class,
        ]);
    }
}
