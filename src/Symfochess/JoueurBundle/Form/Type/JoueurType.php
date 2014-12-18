<?php

namespace Symfochess\JoueurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JoueurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder//->add('id', 'hidden')
        ->add('prenom', 'text', array('label' => 'Prénom'))
            ->add('nom', 'text', array('label' => 'Nom'))
            ->add('rang', 'integer', array('label' => 'Rang'))
            ->add('username', 'text', array('label' => 'Pseudo'))
            ->add('password', 'text', array('label' => 'Mot de passe'))
            ->add('email', 'email', array('label' => 'eMail'))
            ->add('dateNaissance', 'date', array('label' => 'Date de naissance'))
            ->add('Valider', 'submit')
            ->getForm();
    }

    public function getName()
    {
        return 'joueur';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfochess\JoueurBundle\Entity\Joueur',
        ));
    }
}