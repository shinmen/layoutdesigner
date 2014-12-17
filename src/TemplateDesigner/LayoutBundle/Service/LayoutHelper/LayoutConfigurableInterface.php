<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;


interface LayoutConfigurableInterface{

	public function getAllCssClasses();
	public function getAllTags();
	public function extractClasses($cssClasses);
	public function getContainerClass();
	// public function addChildToEntity($entity);
	// public function transform($layout,$name);
	// public function recursiveTransform($children,$root,$parent);
}