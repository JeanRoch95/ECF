<?php

namespace App\Form;

use App\Entity\UserPartenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PartenaireRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4 '
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image partenaire',
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Active',
                'attr' =>[
                    'class' => ' mx-4'
                ],
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Description partenaire',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Adresse partenaire',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('partenaireName', TextType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Nom du partenaire',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Num??ro de t??l??phone',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('zipcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control form'
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control form'
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum 8 caract??res',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex('#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$#', 'Votre mot de passe doit contenir : 8 caract??res minimum || 1 Majuscule || Un caract??re sp??cial || 1 chiffre ')
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4 mb-4'
                ],
                'label' => 'Soumettre'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserPartenaire::class,
        ]);
    }
}
