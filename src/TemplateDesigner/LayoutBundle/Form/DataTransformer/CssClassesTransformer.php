<?php // src/Acme/TaskBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace TemplateDesigner\LayoutBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use TemplateDesigner\LayoutBundle\Model\Layout;

class CssClassesTransformer implements DataTransformerInterface
{


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
        if(!empty($cssClasses)){
            if(is_array($cssClasses[0])){
                $cssClasses = implode(' ', $cssClasses[0]);
            }else{
                $cssClasses = $cssClasses[0];
            } 
        }
        
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
        $delimiter = strpos($cssClasses,',')?',':' ';
        $cssClasses = explode($delimiter, $cssClasses);
        return $cssClasses;
        
    }
}