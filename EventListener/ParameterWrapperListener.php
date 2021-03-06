<?php 

namespace TemplateDesigner\LayoutBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParameterWrapperListener
{
	
	/**
     * @var EngineInterface
     */
	protected $templating;

    /**
     * Constructor.
     *
     * @param EngineInterface $templating The service container instance
     */
    public function __construct(EngineInterface $templating)
    {
    	$this->templating = $templating;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

    	$request = $event->getRequest();
        $templating = $this->templating;
    	$parameters = $event->getControllerResult();

    	if (null === $parameters) {
            
    		if (!$vars = $request->attributes->get('_template_vars')) {
    			if (!$vars = $request->attributes->get('_template_default_vars')) {
    				return;
    			}
    		}

    		$parameters = array();
    		foreach ($vars as $var) {
    			$parameters[$var] = $request->attributes->get($var);
    		}
    	}
        $routeParams = $request->attributes->get('_route_params');
    	// get injected layout entity from annotation and add it to the paramaters
    	if(isset($routeParams['rootLayout'])){
    		$parameters['rootLayout'] = $routeParams['rootLayout'];
    	}

    	if (!$template = $request->attributes->get('_template')) {
    		return $parameters;
    	}

		// wrap all parameters in params array
    	$parameters['params'] = $parameters;

    	if (!$request->attributes->get('_template_streamable')) {
    		$event->setResponse($templating->renderResponse($template, $parameters));
    	} else {
    		$callback = function () use ($templating, $template, $parameters) {
    			return $templating->stream($template, $parameters);
    		};

    		$event->setResponse(new StreamedResponse($callback));
    	}
    }

}
