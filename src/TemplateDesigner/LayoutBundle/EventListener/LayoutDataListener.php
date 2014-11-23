<?php
namespace TemplateDesigner\LayoutBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use TemplateDesigner\LayoutBundle\Entity\Layout;

class LayoutDataListener{

	private $engineParameter;

	public function __construct($engineParameter){
		$this->engineParameter = $engineParameter;
	}

	public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Layout) {
            $entity->setEngine($this->engineParameter);
        }
    }

}