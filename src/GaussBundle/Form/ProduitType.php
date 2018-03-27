<?php

namespace GaussBundle\Form;

use GaussBundle\Repository\CategoryRepository;
use MultimediaBundle\Form\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameProduct',TextType::class)
            ->add('category', EntityType::class, array(
                'label' => 'Select category:',
                'class' => 'GaussBundle:Category',
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (CategoryRepository $er){
                    return $er->createQueryBuilder('c')
                        ;
                }))
            ->add('descriptionProduct',TextareaType::class)
            ->add('currentPrice',IntegerType::class)
            ->add('otherPrice',IntegerType::class)
            ->add('statusProduct',IntegerType::class)
            ->add('codeProduct',TextType::class)
            ->add('classement',TextType::class)
            ->add('image',ImageType::class)
            ->add('Submit',SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GaussBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gaussbundle_produit';
    }


}
