<?php

namespace App\Game;

use App\Core\Enum\DungeonLength;
use App\Core\Enum\TTRPGSystem;
use App\Core\Map\MapType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('length', EnumType::class, [
                'class' => DungeonLength::class,
//                'expanded' => true,
            ])
            ->add('mapType', EnumType::class, [
                'class' => MapType::class,
//                'expanded' => true,
            ])
            ->add('system', EnumType::class, [
                'class' => TTRPGSystem::class,
//                'expanded' => true,
            ])
            ->add('playerLevels', CollectionType::class, [
                'entry_type' => NumberType::class,
                'entry_options' => [
                    'scale' => 0,
                    'html5' => true,
                    'required' => false,
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
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
