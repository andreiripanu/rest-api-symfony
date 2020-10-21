<?php

namespace Arcsym\RestApiSymfony\Form;

use Arcsym\RestApiSymfony\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This class is a form used to create or update a student.
 */
class StudentType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('lastname', null, [
        'invalid_message' => 'invalid.lastname'
      ])
      ->add('firstname', null, [
        'invalid_message' => 'invalid.firstname'
      ])
      ->add('gender', null, [
        'invalid_message' => 'invalid.gender'
      ])
      ->add('email', null, [
        'invalid_message' => 'invalid.email'
      ])
      ->add('mobile', null, [
        'invalid_message' => 'invalid.mobile'
      ])
      ->add('registrationNumber', null, [
        'invalid_message' => 'invalid.registration_number'
      ])
    ;
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Student::class,
      'csrf_protection' => false,
      'allow_extra_fields' => false,
    ]);
  }

  /**
   * @return string
   */
  public function getBlockPrefix(): string
  {
    return '' ;
  }
}
