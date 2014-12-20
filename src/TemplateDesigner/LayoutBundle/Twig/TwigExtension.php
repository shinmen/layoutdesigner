<?php 
namespace TemplateDesigner\LayoutBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class TwigExtension extends \Twig_Extension {


    protected $em;
    protected $environment;
    protected $parameterTemplate;

    public function __construct(EntityManager $entityManager, $parameterTemplate)
    {
        $this->em = $entityManager;
        $this->parameterTemplate = $parameterTemplate;
    }

    /**
    * {@inheritDoc}
    */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'render_layout' => new \Twig_Function_Method($this, 'renderLayout'),
            'layout_start' => new \Twig_Function_Method($this, 'layoutStart'),
            'layout_end' => new \Twig_Function_Method($this, 'layoutEnd')
        );
    }

    /**
     * @param string $string
     * @return template
     */
    public function renderLayout ($rootName,$param=null,$position=null,$displayRootTag=true) {
        $template = $this->parameterTemplate;
        if($position){
            $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($rootName,$position);
        }else{
           $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findOneBy(array('name'=>$rootName)); 
        }
        return $this->environment->render('TemplateDesignerLayoutBundle:Layout:macroLayout.html.twig',array('template'=>$template,'params'=>$param,'parent'=>$parent,'position'=>$position,'displayRootTag'=>$displayRootTag));
    }

        /**
     * @param string $string
     * @return string
     */
    public function layoutStart ($rootName,$position=null) {
        if($position){
            $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($rootName,$position);
        }else{
           $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findOneBy(array('name'=>$rootName)); 
        }
        $tag = '<'.$parent->getTag().' class="'.$parent->getCssClassesAsString().' '.$parent->getCssComplementClasses().'" id="'.$parent->getCssId().'" data-position="'.$parent->getPosition().'">';
        return $tag;
    }

    /**
     * @param string $string
     * @return string
     */
    public function layoutEnd ($rootName,$position=null) {
        if($position){
            $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($rootName,$position);
        }else{
           $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findOneBy(array('name'=>$rootName)); 
        }
        $tag = '</'.$parent->getTag().'>';
        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'LayoutBundle';
    }
}