<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;


interface LayoutManipulableInterface{

	public function extractClasses($cssClasses);
	public function addChildToEntity($entity);
	public function transform($layout,$name,$classes,$tag);
	public function recursiveTransform($children,$root,$parent);
	public function reverseTransformForm($classes);
}