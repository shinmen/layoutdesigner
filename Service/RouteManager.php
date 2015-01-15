<?php 
namespace TemplateDesigner\LayoutBundle\Service;

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
            $routeDefaults = $route->getDefaults();
            // include route without '_' and without method or GET
            if(!strpos($path, '_') && (in_array('GET', $methods)||empty($methods)) ){
                // value construction
                preg_match("/^[\w\\\]+\\\Controller/", $routeDefaults['_controller'],$matches);
                $patterns = array('/Controller/',"/\\\/",'/Bundle/');
                $replacements = array('');
                $prefix = preg_replace($patterns, $replacements, $matches[0]);
                // key construction
                $patterns = array('/Controller/',"/\\\/",'/::/','/Action/');
                $replacements = array(':');
                $action = preg_replace($patterns,$replacements,$routeDefaults['_controller']);
                // route
                $formattedRoutes[$action]= $prefix.$path; 
            }
        }
        return $formattedRoutes;
    }

}