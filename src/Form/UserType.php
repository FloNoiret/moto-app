<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", TextType::class, 
            ["label" => "Nom d'Utilisateur", "required" => true,
            "constraints" => [
                new Length(["min"=> 2, "max" => 180, "minMessage"=>"Le nom d\'utilisateur ne peux pas être inférieur à 2 caratères"]),
                new NotBlank(["message"=> "Le nom d\'utilisateur ne peut pas être vide"])]
            ])

            ->add("password", PasswordType::class, ["label" => "Mot de Passe", "required" => true, 
            "constraints" => [
                new NotBlank(["message"=> "Le mot de passe ne peut pas être vide"])]
            ])

            ->add("confirm", PasswordType::class, ["label" => "Confirmer le mot de passe", "required" => true, 
            "constraints" => [
                new Callback(['callback' => function ($value, ExecutionContext $ec) {
                    if ($ec->getRoot()['password'] -> getViewData() !== $value) {
                        $ec->addViolation("Les mots de passe ne sont pas identiques");
                    }
                }]),
                new NotBlank(["message"=> "Le mot de passe ne peut pas être vide"])]
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
