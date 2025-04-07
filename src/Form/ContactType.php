<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ServiceRepository;
use Doctrine\ORM\QueryBuilder;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', EntityType::class, [
                'class' => 'App\Entity\Service',
                'choice_label' => 'name',
                'choice_value' => 'email',

                'query_builder' => function (ServiceRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('name', TextType::class, [
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Your name'
                ]
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Your email'
                ]
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Your message'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class
        ]);
    }
}
