<?php

namespace App\Form;

use App\Entity\Yeti;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddFormType extends AbstractType
{

    public function __construct(private Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Jméno',
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Pohlaví',
                'choices' => [
                    'Muž' => 'male',
                    'Žena' => 'female',
                ],
                'placeholder' => ''
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Věk',
            ])
            ->add('height', NumberType::class, [
                'label' => 'Výška',
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Hmotnost',
            ])
            ->add('latitude', HiddenType::class, [

            ])
            ->add('longitude', HiddenType::class, [
            ])
            ->add('address', HiddenType::class, [
            ])
            ->add('photoFile', FileType::class, [
                'label' => 'Fotka',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5m',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Nahrajte foto ve formátu jpg/png',
                    ])
                ]
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'submitListener']
            );

    }

    public function submitListener(FormEvent $event)
    {
        if (!($this->security->isGranted('ROLE_USER'))) {
            $event->getForm()->addError(new FormError('Pro přidávání musíte být přihlášen!'));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Yeti::class
        ]);
    }


}