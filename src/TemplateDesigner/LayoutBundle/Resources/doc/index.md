TemplateDesignerLayoutBundle
============================

Create and edit your global pages design in a wysiwyg fashion thanks to twitter boostrap framework

##Installation
Add LayoutBundle by running the command:

``` bash
$ php composer.phar require template_designer/layout_bundle "*"
```
then register the bundle with your kernel in 'app/AppKernel.php':
```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new TemplateDesigner\LayoutBundle\TemplateDesignerLayoutBundle(),
        // ...
    );

    // ...
}
```

##Configuration

Import all routes from the bundle
``` yaml
# app/config/routing.yml

template_designer_layout:
    resource: "@TemplateDesignerLayoutBundle/Controller/"
    type:     annotation
    prefix:   /
```

``` yaml
# app/config/config.yml

template_designer_layout:
    custom_param_template: # necessary to deal with include or render with parameter - example MyBundle:Default:file.html.twig
    # assetic : #default true
    # template_engine: #default bootstrap
    # base_twig : #from which to extends - default ::base.html.twig
    # class_configuration:
    #     layout_choice_form: #default TemplateDesigner\LayoutBundle\Form\LayoutEditionType
    #	  layout_edit_form : #default TemplateDesigner\LayoutBundle\Form\LayoutType
```

##Usage

# routes:
- your url/layout to create templates
- your url/layout/edit to edit templates

Create your first template and name it. Names are unique and will be the id of the template when we request it

Once you've created all the blocks in your template page, go to the edit route to assign static templates or render templates for one or more blocks.
Should you need to give parameters to a block, you must give the block a custom parameters name in order to access the parameters later on.

``` php
namespace YourProject\DefaultBundle\Controller;
#To display the page with your templates and blocks, you just have to call the annotation which will inject the layout entity (rootLayout) in your twig #and wrap your parameters in an array parameter called params

use TemplateDesigner\LayoutBundle\Annotation\LayoutAnnotation;
class DefaultController extends Controller
{
	/**
     * @Route("/hello/{name}")
     * @LayoutAnnotation(name="test")
     */
    public function indexAction($name)
    {
        return $this->render('YourProjectDefaultBundle:Default:index.html.twig',array('name'=>$name));
    }
}
```

In your twig template, you call the twig function with the injected layout entity and your parameters
``` twig
{% block body %}
	{{render_layout(rootLayout.name,params)|raw}}
{% endblock %}
```

When you need to access a parameter inside a block, you can use the custom_param_template
``` twig
{% if child.root.name == "test" and child.custom == "custom name" %}
	{% render(controller(child.render,{name:params['name']})) %}
{% endif %}
```

If you need to see what content goes into which block, you can access the template design in the debug toolbar





