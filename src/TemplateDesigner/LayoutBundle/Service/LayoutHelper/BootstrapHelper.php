<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;

use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutConfigurableInterface;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutManipulableInterface;
use TemplateDesigner\LayoutBundle\Entity\Layout;


class BootstrapHelper implements LayoutConfigurableInterface, LayoutManipulableInterface{

    public function __construct($em){
        $this->em = $em;
    }

	public function getAllCssClasses(){
        $devices = array('col-xs-'=>'mobile','col-sm-'=>'tablet','col-md-'=>'desktop','col-lg-'=>'large desktop');
        $css = array('row'=>'row','container'=>'container');
        
        foreach ($devices as $key => $device) {
            $css[$device] = array();
            foreach (range(1, 12) as $value) {
                $css[$device][$key.$value]= $value.' column(s)';
            }
        }
        return $css;
	}

	public function getAllTags(){
		return array('article'=>'article','div'=>'div','nav'=>'nav','section'=>'section');
	}

    public function getContainerClass(){
        return 'container';
    }
	
	public function extractClasses($cssClasses){
		preg_match('[container]', $cssClasses,$matches);
        if(empty($matches)){
           preg_match('[row]', $cssClasses,$matches);
        }
        if(empty($matches)){
            preg_match_all('/col-[a-z]+-[0-9]+/', $cssClasses,$matches);
            $matches[0][]='column';
        }
        return $matches;
	}

    public function addChildToEntity($entity){
        $cloned = clone($entity);
        $cloned->setName(null);
        $cloned->setCssComplementClasses('');
        if($root = $entity->getRoot()){
            $position = $root->getSubs()->count() + 1;
            $cloned->setParent($entity);
            $cloned->setRoot($root);
        }else{
            $position = $entity->getSubs()->count() + 1;
            $cloned->setParent($entity);
            $cloned->setRoot($entity);
        }
        $cloned->setPosition($position);
        $classes = (is_array($entity->getCssClasses()[0]))? $entity->getCssClasses()[0] :$entity->getCssClasses();
        if(in_array('container', $classes)){
            $cloned->setCssClasses(array('row'));
        }elseif (in_array('column', $classes)) {
            $cloned->setCssClasses(array('row'));
        }elseif (in_array('row', $classes)) {
            $cloned->setCssClasses(array(array('col-xs-12','column')));
        }
        $entity->addChild($cloned);
        return $cloned;
    }

    public function transform($layout,$name,$classes,$tag){
        preg_match('[container]',$classes,$matches);
        $root = new Layout();
        $root->setCssClasses($matches);
        $root->setTag($tag);
        $root->setPosition(0);
        $root->setName($name);
        $this->em->persist($root);
        return $root;
    }

    public function recursiveTransform($children,$root,$parent){
        $em = $this->em;
        foreach ($children as $child) {
            $tag = $child['tag'];
            $classes = $child['cssClass'];
            $matches = $this->extractClasses($classes);
            $new = new Layout();
            $new->setCssClasses($matches);
            $new->setTag($tag);
            $new->setRoot($root);
            $new->setParent($parent);
            $em->persist($new);
            $root->addSub($new);
            $parent->addChild($new);
            $em->flush($new);
            if(isset($child['children'])){
                $this->recursiveTransform($child['children'],$root,$new);
            }
        }
    }

    public function reverseTransformForm($cssClasses){
        $classesWrapper = array();
        foreach ($cssClasses as $key => $value) {
            if(strpos($value, 'col')!== false && !in_array('column',$cssClasses)){    
                $cssClasses[]='column';
            }
        }

        if(in_array('row', $cssClasses)){
            $cssClasses = array('row');
        }

        if(in_array('container', $cssClasses)){
            $cssClasses = array('container');
        }

        $classesWrapper[0] = $cssClasses;
        return $classesWrapper;
    }

	
}