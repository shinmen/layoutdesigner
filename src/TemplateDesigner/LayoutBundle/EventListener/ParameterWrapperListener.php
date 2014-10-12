<?php 

namespace TemplateDesigner\LayoutBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\Container;

class ParameterWrapperListener
{
	
	/**
     * @var Container
     */
	protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container instance
     */
    public function __construct(Container $container)
    {
    	$this->container = $container;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

    	$request = $event->getRequest();
    	$templating = $this->container->get('templating');
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
    	// get injected layout entity from annotation and add it to the paramaters
    	if(isset($request->attributes->get('_route_params')['rootLayout'])){
    		$parameters['rootLayout'] = $request->attributes->get('_route_params')['rootLayout'];
    	}

    	if (!$template = $request->attributes->get('_template')) {
    		return $parameters;
    	}

		// wrap all parameters in params array
    	$parameters['params']=$parameters;

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