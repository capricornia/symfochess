<?php

//--- src/Symfochess/PartiesBundle/Form/Parties/NouvellePartie.php
namespace Symfochess\PartiesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
            ->add('titre')
            ->add('description')
            ->add('joueurs')
            ->add('date')
            ->add('Envoyer', 'submit');
    }

    public function getName()
    {
        return 'partie';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfochess\PartiesBundle\Entity\Partie',
        ));
    }
}