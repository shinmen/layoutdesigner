<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TemplateDesigner\LayoutBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TemplateDesigner\LayoutBundle\Annotation\LayoutAnnotation;
use Tool\ToolBundle\Entity\MyLayout;
use Tool\ToolBundle\Entity\Content;

class DefaultController extends BaseController
{
    /**
     * @Route("/youhou/hello/{name}")
     * @Template()
     * @LayoutAnnotation(name="root")
     */
    public function indexAction($name)
    {
        
        $vroom = "vroom";
        // return $this->renderLayout('TemplateDesignerLayoutBundle:Default:index.html.twig',array('name'=>$name,'vroom'=>$vroom));
        return array('name' => $name,'vroom'=>$vroom);
    }

    /**
     * @Route("/hello/{name}", name="test")
     * @Template()
     */
    public function testAction($name)
    {
        
        return array('name' => $name,'vroom'=>'vroom');
    }
}
