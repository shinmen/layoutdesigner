<?php

namespace TemplateDesigner\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LayoutType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TemplateDesigner\LayoutBundle\Entity\Layout'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'templatedesigner_layoutbundle_layout';
    }
}
