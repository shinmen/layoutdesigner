<?php namespace TemplateDesigner\LayoutBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
use TemplateDesigner\LayoutBundle\Entity\Layout;
 
class AddSubLayoutFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPath;
 
    public function __construct($propertyPath)
    {
        $this->propertyPath = $propertyPath;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addSubForm($form, $root_id)
    {
        $formOptions = array(
            'class'         => 'TemplateDesignerLayoutBundle:Layout',
            'attr'          => array(
                'class' => '',
            ),
            'query_builder' => function (EntityRepository $repository) use ($root_id) {
                $qb = $repository->createQueryBuilder('l')
                    ->join('l.root','r')
                    ->where('r.id = :root')
                    ->setParameter('root', $root_id)
                ;
 
                return $qb;
            }
        );
 
        $form->add($this->propertyPath, 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor    = PropertyAccess::createPropertyAccessor();
 
        $subs        = $accessor->getValue($data, $this->propertyPath);
        $root_id = (!$subs->isEmpty()) ? $subs->first()->getRoot()->getId() : null;
 
        $this->addSubForm($form, $root_id);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $root_id = array_key_exists('root', $data) ? $data['root'] : null;
 
        $this->addSubForm($form, $root_id);
    }
}