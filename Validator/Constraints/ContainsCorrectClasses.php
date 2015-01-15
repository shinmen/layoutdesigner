<?php
namespace TemplateDesigner\LayoutBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsCorrectClasses extends Constraint{
	
	public $message  = 'The layout must contain at least one css class among the following: %classes%';

	public function validatedBy()
	{
	    return 'layout_classes.validator';
	}

}