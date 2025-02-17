<?php

namespace App\Form;

use App\Entity\CourseFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CourseFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName', TextType::class, [
                'label' => 'Nom du fichier',
                'required' => false,
                'attr' => ['readonly' => true], // Champ non modifiable
            ])
            ->add('file', FileType::class, [
                'label' => 'Télécharger un fichier',
                'mapped' => false, // Ne mappe pas directement sur l'entité
                'required' => false,
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
                        'mimeTypesMessage' => 'Formats autorisés : JPEG, PNG, GIF, MP4, OGG, WEBM, PDF, DOC.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseFile::class,
        ]);
    }
}
