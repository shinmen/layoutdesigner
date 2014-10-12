<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tool\ToolBundle\Entity\MyLayout;
use Tool\ToolBundle\Entity\Content;

class BaseController extends Controller
{
    protected function renderLayout($view, array $parameters = array(), Response $response = null){
        $parameters['params'] = $parameters;
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }
}
