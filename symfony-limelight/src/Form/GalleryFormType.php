<?php

namespace App\Form;

use App\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class GalleryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('file', FileType::class,[
                    'mapped' => false,
                    'constraints' => [
                    new File(['mimeTypes' => ['image/jpeg','image/png',],'mimeTypesMessage' => 'Suba una imagen valida porfavor',])],])
            ->add('Send', SubmitType::class, ['attr' => ['class'=>'pull-right btn btn-lg sr-button']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
