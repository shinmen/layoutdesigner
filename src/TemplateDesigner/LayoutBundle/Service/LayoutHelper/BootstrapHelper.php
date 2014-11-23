<?php 
namespace TemplateDesigner\LayoutBundle\Service\LayoutHelper;

use TemplateDesigner\LayoutBundle\Service\LayoutHelper\LayoutConfigurableInterface;


class BootstrapHelper implements LayoutConfigurableInterface{

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

	public function getContainerClass(){
		return 'container';
	}

	
}