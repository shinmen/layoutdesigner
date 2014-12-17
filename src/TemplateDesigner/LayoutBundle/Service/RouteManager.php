<?php 
namespace TemplateDesigner\LayoutBundle\Service;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;
 
class RouteManager {

    private $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    // get array of routes with human readable as values and bundle format as keys
    public function getFormattedRoutesForForms($routes=null){
        if(!$routes){
            $routes = $this->router->getRouteCollection()->all();
        }
        $formattedRoutes = array();
        foreach ($routes as $route) {
            $path = $route->getPath();
            $methods = $route->getMethods();
            if(!strpos($path, '_') && (in_array('GET', $methods)||empty($methods)) ){
                $patterns = array('/Controller/',"/\\\/",'/::/','/Action/');
                $replacements = array(':');
                $action = preg_replace($patterns,$replacements,$route->getDefaults()['_controller']);
                $formattedRoutes[$action]= $path; 
            }
        }
        return $formattedRoutes;
    }

}