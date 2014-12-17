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
            
            ->add($builder->create('cssClasses','choice', array('label'=>'Layout class element','choices'=>$css,'multiple'=>true))
                 ->addModelTransformer($transformer))
            ->add('tag','choice',array('label'=>'Tag element','preferred_choices' => array('div'),'required'=>false,'empty_value'=>'Select a tag','choices'=>$tags))
            ->add('cssId',null,array('label'=>'Id element','required'=>false))
            ->add('render','choice',array('label'=>'Render','required'=>false,'empty_value'=>'Select a path','choices'=>$routes))
            ->add('include','choice',array('label'=>'Include','required'=>false,'empty_value'=>'Select a template','choices'=>$templates))
            ->add('custom',null,array('label'=>'Custom parameters','required'=>false))
            ->add('cssComplementClasses','text',array('label'=>'Additionnal class element','required'=>false))
            ->add('addNewChild','submit',array('attr'=>array('label'=>'Add a child','class'=>'btn btn-default','onclick'=>'return form_submit_add_child($(this));')))
            ->add('submit', 'submit', array('attr'=>array('class'=>'btn btn-default'),'label' => 'Update'));
        ;
         $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $layout = $event->getData();
            $form = $event->getForm();

            if ($layout && null === $layout->getParent() ) {
               $form->add('name',null,array('label'=>'Layout Name','required'=>false));
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
