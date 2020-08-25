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

/**
 * Ce formulaire permet de laisser un avis sur la page produit.
 * Il est relié à l'entité Review.
 */
class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // On ajoute le champ email avec le type correspondant, ainsi que ses contraintes.
            ->add('email', EmailType::class, [
                'help' => 'Votre adresse e-mail doit contenir un "@" et un ".".',
                'attr' => ['placeholder' => 'Votre e-mail...'], 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                    new Email([
                        'message' => 'Votre adresse e-mail n\'est pas valide.',
                    ])
                ]
            ])
            // On ajoute le champ username pour le pseudo avec le type correspondant, ainsi que ses contraintes.
            ->add('username', TextType::class, [
                'help' => 'Votre pseudonyme doit être entre 3 et 15 caractères.',
                'attr' => ['placeholder' => 'Votre pseudonyme...'], 
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
            // On ajoute le champ rating avec le type correspondant, ainsi que ses contraintes.
            // En l'occurrence, on part sur un ChoiceType afin d'avoir une sélection pour la note (1 a 5)
            ->add('rating', ChoiceType::class, [
                'help' => 'La note va de 1 étoile à 5 étoiles selon votre appréciation du produit.',
                'attr' => ['class' => 'tinybox'],
                'choices' => [
                    1 => true,
                    2,
                    3,
                    4,
                    5,
                ],
                'expanded' => true,
            ])
            // On ajoute le champ comment avec le type correspondant, ainsi que ses contraintes.
            // Ici, on utilise le bundle CKEditor afin d'avoir un éditeur de texte pour l'ajoute d'un commentaire
            // Ainsi que le bundle PurifyHTML qui permet la bonne transcription des balises HTML utilisées par l'éditeur de texte
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
            // On ajoute le champ imageReviews avec le type correspondant.
            // multiple permet l'upload de plusieurs fichiers.
            ->add('imageReviews', FileType::class, [
                'help' => 'Vous pouvez charger une ou plusieurs photos.',
                'required' => false,
                'multiple' => true,
                'mapped' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Veuillez sélectionner votre ou vos fichier(s) - (optionnel)'], 
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
