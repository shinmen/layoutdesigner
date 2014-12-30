<?php
namespace TemplateDesigner\LayoutBundle\EventListener;

use TemplateDesigner\LayoutBundle\Templating\DelegatingEngineEvent;
use TemplateDesigner\LayoutBundle\Templating\DelegatingEngineEvents;
use TemplateDesigner\LayoutBundle\Templating\EventableDelegatingEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class ParameterRenderWrapperListener implements EventSubscriberInterface
{
	/**
	* Returns an array of event names this subscriber wants to listen to.
	*
	* The array keys are event names and the value can be:
	*
	* * The method name to call (priority defaults to 0)
	* * An array composed of the method name to call and the priority
	* * An array of arrays composed of the method names to call and respective
	* priorities, or 0 if unset
	*
	* For instance:
	*
	* * array('eventName' => 'methodName')
	* * array('eventName' => array('methodName', $priority))
	* * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
	*
	* @return array The event names to listen to
	*
	* @api
	*/
	public static function getSubscribedEvents()
	{
		return [
		EventableDelegatingEngine::PRE_RENDER => 'onPreRender'
		];
	}
	/**
	* @param DelegatingEngineEvent $event
	* @return void
	*/
	public function onPreRender(DelegatingEngineEvent $event)
	{
		$templateParams = $event->getParameters();
		$routeParams = $event->getRequest()->attributes->get('_route_params');
    	// get injected layout entity from annotation and add it to the paramaters
    	if(isset($routeParams['rootLayout'])){
    		$templateParams['rootLayout'] = $routeParams['rootLayout'];
    	}
		// wrap all parameters in params array
    	$templateParams['params'] = $templateParams;
    	$event->setParameters($templateParams);
	}
} 