<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;


interface LayoutConfigurableInterface{

	public function getAllCssClasses();
	public function getAllTags();
	public function extractClasses($cssClasses);
	public function getContainerClass();
}