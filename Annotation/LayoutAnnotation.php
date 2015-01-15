<?php 
namespace TemplateDesigner\LayoutBundle\Annotation;

/**
 * @Annotation
 */
final class LayoutAnnotation
{
	
	protected $name;
	protected $position;

	public function __construct(array $data){
		$this->name = $data['name'];
		if(isset($data['position']))$this->position = $data['position'];
	}

	public function getName(){
		return $this->name;
	}

	public function getPosition(){
		return $this->position;
	}
}