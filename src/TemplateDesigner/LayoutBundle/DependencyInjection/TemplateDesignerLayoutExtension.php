<?php

namespace TemplateDesigner\LayoutBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Container;



/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TemplateDesignerLayoutExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $container->setParameter('template_designer_layout.custom_param_template', $config['custom_param_template']);
        $container->setParameter('template_designer_layout.template_engine', $config['template_engine']);
        $container->setParameter('template_designer_layout.edit_form_twig', $config['edit_form_twig']);
        
        $engines = array_map(function ($engine) { return new Reference('templating.engine.'.$engine); }, $container->getParameter('templating.engines'));
        $container->setDefinition('templating.engine.delegating',new Definition('%templating.engine.delegating.class%',array(new Reference('service_container'),$engines)));

        $container->setAlias('templating','templating.engine.delegating');
    }
}
