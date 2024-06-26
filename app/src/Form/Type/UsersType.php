<?php
/**
 * Users Type.
 */

namespace App\Form\Type;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Form type for handling user data.
 */
class UsersType extends AbstractType
{
    /**
     * Builds the user form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options for the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
            ])
            ->add('nick', TextType::class, [
                'label' => 'label.nick',
                'required' => true, // Nickname change is optional
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => ['label' => 'label.passwords_dont_match'],
                'first_options' => ['label' => 'label.new_password'],
                'second_options' => ['label' => 'label.repeat_new_password'],
                'required' => true, // Password change is optional
            ]);
    }

    /**
     * Configures the form options.
     *
     * @param OptionsResolver $resolver The resolver for form options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}