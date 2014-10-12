<?php 
namespace TemplateDesigner\LayoutBundle\DataCollector;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class LayoutDataCollector extends DataCollector
{

	public function collect(Request $request, Response $response, \Exception $exception = null)
    {	
    	if(isset($request->attributes->get('_route_params')['root_layout'])){
			$this->data = array('layout' => $request->attributes->get('_route_params')['root_layout']);
		}
        
    }

    public function getLayout()
    {
        return $this->data['layout'];
    }

    public function getName()
    {
        return 'layout';
    }
}



