<?php

namespace App\Form;

use App\Enum\DungeonLength;
use App\Enum\MapType;
use App\Enum\TTRPGSystem;
use App\Service\Game\NewGameDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('length', EnumType::class, [
                'class' => DungeonLength::class,
                'expanded' => true,
            ])
            ->add('mapType', EnumType::class, [
                'class' => MapType::class,
                'expanded' => true,
            ])
            ->add('system', EnumType::class, [
                'class' => TTRPGSystem::class,
                'expanded' => true,
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
