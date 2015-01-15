<?php

namespace TemplateDesigner\LayoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('template_designer_layout')
            ->children()
                ->booleanNode('assetic')
                    ->defaultTrue()
                    ->end()
                ->end()
            ->children()
                ->scalarNode('custom_param_template')
                    ->example(array('MyBundle:Default:file.html.twig'))
                    ->cannotBeEmpty()
                    ->end()
                ->end()
            ->children()
                ->scalarNode('template_engine')
                    ->example(array('bootstrap','foundation','squeleton'))
                    ->defaultValue('bootstrap')
                    ->validate()
                    ->ifNotInArray(array('bootstrap','foundation','squeleton'))
                        ->thenInvalid('Invalid template_engine "%s"')
                        ->end()
                    ->end()
                ->end()
            ->children()
                ->scalarNode('base_twig')
                    ->defaultValue('::base.html.twig')
                    ->end()
                ->end()
            ->children()
                ->arrayNode('class_configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity')
                            ->defaultValue('TemplateDesigner\LayoutBundle\Entity\Layout')
                            ->end()
                        ->scalarNode('layout_choice_form')
                            ->defaultValue('TemplateDesigner\LayoutBundle\Form\LayoutEditionType')
                            ->end()
                        ->scalarNode('layout_edit_form')
                            ->defaultValue('TemplateDesigner\LayoutBundle\Form\LayoutType')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->children()
                ->arrayNode('data_collector_css')
                        ->cannotBeEmpty()
                        ->children()
                        ->scalarNode('main')
                            ->example('bundles/yourBundle/bootstrap/bootstrap-3.2.0-dist/css/bootstrap.min.css')
                            ->isRequired() 
                            ->end()
                        ->scalarNode('optional')
                            ->defaultNull()
                            ->end()
                        ->end()    
                    ->end()
                ->end()
           
                

        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
