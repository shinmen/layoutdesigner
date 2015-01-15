<?php
namespace TemplateDesigner\LayoutBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutValidationInterface;


class ContainsCorrectClassesValidator extends ConstraintValidator{
	
	protected $helper;

	public function __construct(LayoutValidationInterface $helper){
		$this->helper = $helper;
	}

	public function validate($cssClasses, Constraint $constraint)
    {

        $valid = $this->helper->validateClasses($cssClasses);
        $wrapClasses = $this->helper->getWrappingClasses();
        if(!$valid){$this->context->addViolation($constraint->message,array('%classes%'=>implode(', ', $wrapClasses)));}
    }
}