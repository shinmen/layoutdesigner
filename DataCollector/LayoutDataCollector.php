<?php 
namespace TemplateDesigner\LayoutBundle\DataCollector;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class LayoutDataCollector extends DataCollector
{

	protected $cssAsset;

    public function __construct($cssAsset){
        $this->cssAsset = $cssAsset;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {	
        $routeParams = $request->attributes->get('_route_params');
        $this->data = array('layout' => null, 'cssAsset'=>$this->cssAsset);
        if(isset($routeParams['rootLayout'])){
			$this->data = array('layout' => $routeParams['rootLayout'],'cssAsset'=>$this->cssAsset);
		}
    }

    public function getLayout()
    {
        return $this->data['layout'];
    }

    public function getCssAsset()
    {
        return $this->data['cssAsset'];
    }

    public function getName()
    {
        return 'layout';
    }
}



