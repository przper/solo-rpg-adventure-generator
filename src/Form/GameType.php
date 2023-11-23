<?php

namespace App\Form;

use App\Enum\DungeonLength;
use App\Service\Game\NewGameDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Railroad' => 'railroad',
                    'Roguelike' => 'roguelike',
                ],
            ])
            ->add('length', EnumType::class, [
                'class' => DungeonLength::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewGameDTO::class,
        ]);
    }
}
