<?php

namespace TemplateDesigner\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
        $routes = $options['routes'];
        $css = $options['css'];
        $tags = $options['tags'];
        $templates = $options['templates'];
        $builder
            
            ->add($builder->create('cssClasses','choice', array('choices'=>$css,'multiple'=>true))
                 ->addModelTransformer($transformer))
            ->add('tag','choice',array('preferred_choices' => array('div'),'required'=>false,'empty_value'=>'Select a tag','choices'=>$tags))
            ->add('cssId',null,array('required'=>false))
            ->add('render','choice',array('required'=>false,'empty_value'=>'Select a path','choices'=>$routes))
            ->add('include','choice',array('required'=>false,'empty_value'=>'Select a template','choices'=>$templates))
            ->add('custom',null,array('required'=>false))
            ->add('cssComplementClasses','text',array('required'=>false))
            ->add('addNewChild','submit')
        ;
         $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $layout = $event->getData();
            $form = $event->getForm();

            if ($layout && null === $layout->getParent() ) {
               $form->add('name',null,array('required'=>false));
            }
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TemplateDesigner\LayoutBundle\Entity\Layout',
            'routes'     => null,
            'css'        => null,
            'tags'       => null,
            'templates'  => null,
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
