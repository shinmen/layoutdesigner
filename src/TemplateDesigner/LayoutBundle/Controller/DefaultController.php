<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tool\ToolBundle\Entity\MyLayout;
use Tool\ToolBundle\Entity\Content;

class DefaultController extends Controller
{
    /**
     * @Route("/youhou/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        

        return array('name' => $name,'vroom'=>'vroom');
    }
}
