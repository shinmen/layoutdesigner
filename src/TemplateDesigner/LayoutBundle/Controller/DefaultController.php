<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
