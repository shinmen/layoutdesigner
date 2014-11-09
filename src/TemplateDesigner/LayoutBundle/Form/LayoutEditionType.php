<?php

namespace TemplateDesigner\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use TemplateDesigner\LayoutBundle\Form\EventListener\AddSubLayoutFieldSubscriber;

class LayoutEditionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('root','entity',array(
                'class'=>'TemplateDesignerLayoutBundle:Layout',
                'empty_value'   => 'RootLayouts',
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('l');
                    $qb
                        ->where($qb->expr()->isNull('l.parent'))
                        ->orderBy('l.name', 'ASC');
                        return $qb;
                })
            );
        $builder
            ->addEventSubscriber(new AddSubLayoutFieldSubscriber('subs'))
            ->add('submit','submit');

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TemplateDesigner\LayoutBundle\Entity\Layout',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'templatedesigner_layoutbundle_layout_edition';
    }
}
