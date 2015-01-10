<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;

use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutConfigurableInterface;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutManipulableInterface;
use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutValidationInterface;
use TemplateDesigner\LayoutBundle\Entity\Layout;


class BootstrapHelper implements LayoutConfigurableInterface, LayoutManipulableInterface,LayoutValidationInterface{

    public function __construct($em){
        $this->em = $em;
    }

	public function getAllCssClasses(){
        $hiddens = array('hidden-xs'=>'Hidden Mobile','hidden-sm'=>'Hidden Tablet','hidden-md'=>'Hidden Desktop','hidden-lg'=>'Hidden Large Desktop');
        $visibles = array('visible-xs-block'=>'Only visible Mobile','visible-sm-block'=>'Only visible Tablet','visible-md-block'=>'Only visible Desktop','visible-lg-block'=>'Only visible Large Desktop');
        $devices = array('col-xs-'=>'mobile','col-sm-'=>'tablet','col-md-'=>'desktop','col-lg-'=>'large desktop');
        $css = array('row'=>'row','container'=>'container');
        
        foreach ($devices as $key => $device) {
            $css[$device] = array();
            foreach (range(1, 12) as $value) {
                $css[$device][$key.$value]= $value.' column(s)';
            }
        }
        $css['hidden'] = array();
        foreach ($hiddens as $key => $hidden) {
            $css['hidden'][$key] = $hidden;
        }
        $css['only visible'] = array();
        foreach ($visibles as $key => $visible) {
            $css['only visible'][$key] = $visible;
        }
        return $css;
	}

	public function getAllTags(){
		return array('article'=>'article','div'=>'div','nav'=>'nav','section'=>'section');
	}

    public function getWrappingClasses(){
        return array('container','row','column');
    }
	
	public function extractClasses($cssClasses){
		preg_match('[container]', $cssClasses,$matches);
        if(empty($matches)){
           preg_match('[row]', $cssClasses,$matches);
        }
        if(empty($matches)){
            preg_match_all('/col-[a-z]+-[0-9]+/', $cssClasses,$matches);
            $matches[0][]='column';
            if(preg_match_all('/hidden-[a-z]+/', $cssClasses, $hiddens)){
                foreach ($hiddens[0] as $hidden) {
                    $matches[0][] = $hidden;
                }
            }
            if(preg_match_all('/visible-[a-z]+-[a-z]+/', $cssClasses, $visibles));{
                foreach ($visibles[0] as $visible) {
                    $matches[0][] = $visible;
                }
            }
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

    public function validateClasses($cssClasses){
        $valid = false;
        foreach ($cssClasses[0] as $key => $cssClass) {
            if(strpos($cssClass, 'col')===0|| strpos($cssClass, 'row')===0|| strpos($cssClass, 'container')===0){
                $valid = true;
            }
        }
        return $valid;
    }

	
}