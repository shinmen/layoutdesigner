<?php
namespace TemplateDesigner\LayoutBundle\Templating;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use TemplateDesigner\LayoutBundle\Templating\DelegatingEngineEvent;
use TemplateDesigner\LayoutBundle\Templating\DelegatingEngineEvents;


class EventableDelegatingEngine extends DelegatingEngine
{
	/**
	* @param string $view
	* @param array $parameters
	* @param Response $response
	* @return Response|void
	*/
	public function renderResponse($view, array $parameters = array(), Response $response = null)
	{
		$event = new DelegatingEngineEvent($view, $parameters, $response, $this->getRequest());
		$this->getEventDispatcher()->dispatch(DelegatingEngineEvents::PRE_RENDER, $event);
		
		return parent::renderResponse($event->getView(), $event->getParameters(), $event->getResponse());
	}

	/**
	* @return EventDispatcherInterface
	*/
	protected function getEventDispatcher()
	{
		return $this->container->get('event_dispatcher');
	}
	/**
	* @return \Symfony\Component\HttpFoundation\Request
	*/
	protected function getRequest()
	{
		return $this->container->get('request');
	}
} 