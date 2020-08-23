<?php

namespace App\Form;

use App\Entity\Review;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'help' => 'Votre adresse e-mail doit contenir un "@" et un ".".',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                    new Email([
                        'message' => 'Votre adresse e-mail n\'est pas valide.',
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'help' => 'Votre pseudonyme doit être entre 3 et 15 caractères.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                    new Length([
                       'min' => 3,
                       'max' => 15,
                       'minMessage' => 'Votre pseudonyme ne peut pas avoir moins de 3 caractères.',
                       'maxMessage' => 'Votre pseudonyme ne peut pas excéder 15 caractères.', 
                    ])
                ]
            ])
            ->add('rating', ChoiceType::class, [
                'help' => 'La note va de 1 étoile à 5 étoiles selon votre appréciation du produit.',
                'choices' => [
                    1 => true,
                    2,
                    3,
                    4,
                    5,
                ],
                'expanded' => true,
            ])
            ->add('comment', CKEditorType::class, [
                'help' => 'Votre commentaire est limité à 1000 caractères.',
                'purify_html' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Votre commentaire ne peut pas excéder 1000 caractères',
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                    ])
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
