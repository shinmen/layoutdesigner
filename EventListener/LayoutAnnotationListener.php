<?php 
namespace TemplateDesigner\LayoutBundle\EventListener;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\ORM\EntityManager;
use TemplateDesigner\LayoutBundle\Annotation\LayoutAnnotation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
 
class LayoutAnnotationListener {

    private $reader;
    protected $em;

    public function __construct(Reader $reader, EntityManager $em) {
        $this->reader = $reader;
        $this->em = $em;
    }
    public function onCoreController(FilterControllerEvent $event) {
        if (!is_array($controller = $event->getController())) {
            return;
        }
        
        $method = new \ReflectionMethod($controller[0], $controller[1]);
 
        if (!$annotations = $this->reader->getMethodAnnotations($method)) {
            return;
        }
 
        foreach($annotations as $annotation){
            if($annotation instanceof LayoutAnnotation) {
                if($annotation->getPosition()){
                    $root = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($annotation->getName(),$annotation->getPosition());
                }else{
                    $root = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findOneBy(array('name'=>$annotation->getName()));
                }
                if(!$annotation->getPosition() && !$annotation->getName()){
                    throw new \Exception("Parameter missing in layout annotation", 1);
                }
                if(!$root){
                    throw new NotFoundHttpException('Layout not found'); 
                }

                $route_params = $event->getRequest()->attributes->get('_route_params');
                $route_params['rootLayout'] = $root;
                $event->getRequest()->attributes->set('_route_params',$route_params);                
            }
        }
    }
}