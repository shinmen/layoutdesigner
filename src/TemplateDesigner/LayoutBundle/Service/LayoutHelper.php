<?php 
namespace TemplateDesigner\LayoutBundle\Service;

use TemplateDesigner\LayoutBundle\Service\LayoutHelper\BootstrapHelper;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutConfigurableInterface;

class LayoutHelper implements LayoutConfigurableInterface{

	private $engineParameter;
	private $helper;

	public function __construct($engineParameter){
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

	private function getLayoutHelper($engineParameter){
		if($engineParameter == 'bootstrap'){
			$helper = new BootstrapHelper();
		}
		return $helper;
	}

}