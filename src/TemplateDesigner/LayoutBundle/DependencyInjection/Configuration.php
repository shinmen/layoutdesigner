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
                    ->defaultFalse()
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
                        ->thenInvalid('Invalid database driver "%s"')
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
                            // ->validate()
                            //     ->ifTrue(function ($v) { return (boolean) preg_match('/^[a-zA-Z\\]+$/', $v); })
                            //     ->then(function ($v) {
                            //         preg_match('/[a-zA-Z]+$/',$v,$match);
                            //         $namespace = preg_replace(array('/\\\/','/Entity/'), '', $v);
                            //         $bundle = $namespace.':'.$match[0];
                            //         $v['entity_bundle'] = $bundle;
                            //         return $v;})
                            //     ->end()
                            ->end()
                        ->scalarNode('layout_choice_form')
                            ->defaultValue('TemplateDesigner\LayoutBundle\Form\LayoutEditionType')
                            ->end()
                        ->scalarNode('layout_edit_form')
                            ->defaultValue('TemplateDesigner\LayoutBundle\Form\LayoutType')
                            ->end()
                        // ->scalarNode('entity_bundle')
                        //     // ->defaultValue('TemplateDesignerLayoutBundle:Layout')
                        //     ->end()
                    ->end()
                ->end()

        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
