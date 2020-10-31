<?php

namespace App\Form;

use App\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('capo', TextType::class, [
                'required' => true
            ])
            ->add('song_name', TextType::class, [
                'required' => true
            ])
            ->add('group_name', TextType::class, [
                'required' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['submit_label']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
            'submit_label' => "Undefined label title"
        ]);

        $resolver->setAllowedTypes('submit_label', 'string');
    }
}
