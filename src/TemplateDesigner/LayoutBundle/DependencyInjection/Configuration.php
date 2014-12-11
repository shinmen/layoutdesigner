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
                ->scalarNode('custom_param_template')
                    ->defaultValue('TemplateDesignerLayoutBundle:Default:file.html.twig')
                    ->end()
                ->end()
            ->children()
                ->scalarNode('template_engine')
                    ->defaultValue('bootstrap')
                    ->end()
                ->end()
            ->children()
                ->scalarNode('edit_form_twig')
                    ->defaultValue('::base.html.twig')
                    ->end()
                ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
