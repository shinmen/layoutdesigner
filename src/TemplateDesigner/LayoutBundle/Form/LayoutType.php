<?php

namespace TemplateDesigner\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TemplateDesigner\LayoutBundle\Form\DataTransformer\CssClassesTransformer;

class LayoutType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $transformer = new CssClassesTransformer();
        $builder
            ->add('name')
             ->add($builder->create('cssClasses', 'text')
                 ->addModelTransformer($transformer))
            //->add('cssClasses')
            // ->add('tag')
            // ->add('cssId')
            // ->add('render')
            // ->add('include')
            // ->add('custom')
            // ->add('engine')
            // ->add('position')
            // ->add('parent')
            // ->add('root')
        ;
    }
    
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
