<?php 
namespace TemplateDesigner\LayoutBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class TwigExtension extends \Twig_Extension {


    protected $em;
    protected $environment;
    protected $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
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
    public function renderLayout ($name,$param=null,$position=null,$root=true) {
        if($position){
            $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($name,$position);
        }else{
           $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findOneBy(array('name'=>$name)); 
        }
        return $this->environment->render('TemplateDesignerLayoutBundle:Layout:macroLayout.html.twig',array('params'=>$param,'parent'=>$parent,'position'=>$position,'root'=>$root));
    }

        /**
     * @param string $string
     * @return string
     */
    public function layoutStart ($name,$position,$root=false) {
        $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($name,$position);
        $tag = '<'.$parent->getTag().' class="'.$parent->getCssClasses().'" id="'.$parent->getCssId().'" data-position="'.$parent->getPosition().'">';
        return $tag;
    }

    /**
     * @param string $string
     * @return string
     */
    public function layoutEnd ($name,$position,$root=false) {
        $parent = $this->em->getRepository('TemplateDesignerLayoutBundle:Layout')->findLayoutWitOptions($name,$position);
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