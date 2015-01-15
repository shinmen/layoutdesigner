<?php // src/Acme/TaskBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace TemplateDesigner\LayoutBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CssClassesTransformer implements DataTransformerInterface
{

    private $helper;

    public function __construct($helper){
        $this->helper = $helper;
    }

    /**
     * Transforms an array (cssClasses) to a string (cssClasses). Display
     *
     * @param  cssClasses|null $cssClasses
     * @return string
     */
    public function transform($cssClasses)
    {
        if (null === $cssClasses) {
            return "";
        }

        $cssClasses = (is_array($cssClasses[0]))? $cssClasses[0] :$cssClasses;
        
        return $cssClasses;
    }

    /**
     * Transforms a string (cssClasses) to an array (cssClasses). Create/Edit
     *
     * @param  string $cssClasses
     * @return string
     * @throws TransformationFailedException if string (cssClasses) is not found.
     */
    public function reverseTransform($cssClasses)
    {
        if (!$cssClasses) {
            return null;
        }
        
        $classesWrapper = $this->helper->reverseTransformForm($cssClasses);
        
        return $classesWrapper;
        
    }
}