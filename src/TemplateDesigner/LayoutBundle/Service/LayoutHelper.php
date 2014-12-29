<?php 
namespace TemplateDesigner\LayoutBundle\Service;

use TemplateDesigner\LayoutBundle\Service\LayoutHelper\BootstrapHelper;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutConfigurableInterface;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutManipulableInterface;
use Doctrine\ORM\EntityManager;

class LayoutHelper implements LayoutConfigurableInterface, LayoutManipulableInterface{

	private $engineParameter;
	private $helper;
	private $em;

	public function __construct(EntityManager $em,$engineParameter){
		$this->em = $em;
		$this->engineParameter = $engineParameter;
		$this->helper = $this->getLayoutHelper($engineParameter);
	}

	public function getAllCssClasses(){
		return $this->helper->getAllCssClasses();
	}

	public function getAllTags(){
		return $this->helper->getAllTags();
	}

	public function extractClasses($cssClasses){
		return $this->helper->extractClasses($cssClasses);
	}

	public function getContainerClass(){
		return $this->helper->getContainerClass();
	}

	public function addChildToEntity($entity){
		return $this->helper->addChildToEntity($entity);
	}

	public function reverseTransformForm($classes){
		return $this->helper->reverseTransformForm($classes);
	}

    public function transform($layout,$name,$classes,$tag){
    	return $this->helper->transform($layout,$name,$classes,$tag);
    }

    public function recursiveTransform($children,$root,$parent){
    	return $this->helper->recursiveTransform($children,$root,$parent);
    }

	private function getLayoutHelper($engineParameter){
		if($engineParameter == 'bootstrap'){
			$helper = new BootstrapHelper($this->em);
		}
		return $helper;
	}


}