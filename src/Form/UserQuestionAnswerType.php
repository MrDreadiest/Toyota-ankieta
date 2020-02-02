<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Dealer;
use App\Model\UserQuestionAnswer\SetUserQuestionAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserQuestionAnswerType
 * @package App\Form
 */
class UserQuestionAnswerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer_id', EntityType::class, [
                'class' => Answer::class,
                'property_path' => 'answer'
            ])
            ->add('answer_text', TextType::class, [
                'property_path' => 'textAnswer'
            ])
            ->add('dealer_id', EntityType::class, [
                'class' => Dealer::class,
                'property_path' => 'dealer'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SetUserQuestionAnswer::class,
            'csrf_protection' => false
        ]);
    }
}
