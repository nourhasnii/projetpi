<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;
use App\Form\CourseFileType; // Ajout du formulaire de gestion des fichiers

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du cours',
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Length([
                        'max' => 255, // Correction pour permettre un titre plus long
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du cours',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                ],
            ])
            ->add('courseFiles', CollectionType::class, [
                'entry_type' => CourseFileType::class, // Utilisation d'un formulaire pour CourseFile
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Fichiers associés',
            ])
            ->add('content_file', FileType::class, [
                'label' => 'Ajouter des fichiers (Images, Vidéos, PDFs)',
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '50M',
                                'mimeTypes' => [
                                    'image/jpeg', 'image/png', 'image/gif',
                                    'video/mp4', 'video/ogg', 'video/webm',
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                ],
                                'mimeTypesMessage' => 'Seuls les fichiers JPEG, PNG, GIF, MP4, OGG, WEBM et PDF sont acceptés.',
                            ])
                        ],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
