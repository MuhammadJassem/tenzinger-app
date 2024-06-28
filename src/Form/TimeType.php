<?php

declare(strict_types=1);

namespace App\Form;

use App\Utils\CompensationTimeHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', ChoiceType::class, [
                'choices' => CompensationTimeHelper::getValidCompensationYearsForm(),
                'label' => 'Choose year: ',
            ])
            ->add('month', ChoiceType::class, [
                'choices' => CompensationTimeHelper::getValidMonthsForm(),
                'label' => 'Choose month: ',
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
