<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TemplateDesigner\LayoutBundle\Entity\Layout;
use TemplateDesigner\LayoutBundle\Form\LayoutType;
use TemplateDesigner\LayoutBundle\Form\LayoutEditionType;

/**
 * Layout controller.
 *
 * @Route("/layout")
 */
class LayoutController extends Controller
{

    /**
     * Lists all Layout entities.
     *
     * @Route("/", name="layout")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->findAll();
        $edit_form_twig = $this->container->getParameter('template_designer_layout.edit_form_twig');

        return array(
            'entities' => $entities,
            'edit_form_twig'=>$edit_form_twig
        );
    }
    /**
     * Creates a new Layout entity.
     *
     * @Route("/", name="layout_create")
     * @Method("POST")
     * @Template("TemplateDesignerLayoutBundle:Layout:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Layout();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('layout_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

        /**
     * Creates a new Layout entity.
     *
     * @Route("/transform", name="layout_transform")
     */
    public function transformAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $helper = $this->get('layout.helper');
        $layout = $request->get('layout');
        $name = $request->get('name');
        $tag = $layout['tag'];
        $temp = $layout['cssClass'];
        $children = $layout['children'];
        preg_match('[container]', $temp,$matches);
        $root = new Layout();
        $root->setCssClasses($matches);
        $root->setTag($tag);
        $root->setPosition(0);
        $root->setName($name);
        $em->persist($root);
        $validator = $this->get('validator');
        $errorList = $validator->validate($root);
        
        if (count($errorList) == 0) {
            $em->flush();
            $this->recursiveTransform($children,$root,$root);
            $i=0;
            foreach ($root->getSubs() as $sub) {
                $sub->setPosition(++$i);
                $em->flush();
            } 
        }
        
        return new JsonResponse($errorList);
    }

    private function recursiveTransform($children,$root,$parent){
        $em = $this->getDoctrine()->getManager();
        $helper = $this->get('layout.helper');

        foreach ($children as $child) {
            $tag = $child['tag'];
            $temp = $child['cssClass'];
            $matches = $helper->extractClasses($temp);
            $new = new Layout();
            $new->setCssClasses($matches);
            $new->setTag($tag);
            $new->setRoot($root);
            $new->setParent($parent);
            $em->persist($new);
            $root->addSub($new);
            $parent->addChild($new);
            $em->flush();
            if(isset($child['children'])){
                $this->recursiveTransform($child['children'],$root,$new);
            }
        }
    }


    /**
     * Creates a form to create a Layout entity.
     *
     * @param Layout $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Layout $entity)
    {
        $form = $this->createForm(new LayoutType(), $entity, array(
            'action' => $this->generateUrl('layout_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Layout entity.
     *
     * @Route("/new", name="layout_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Layout();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

        /**
     * Displays a form to edit an existing Layout entity.
     *
     * @Route("/edit", name="layout_edition")
     * @Method("GET")
     * @Template()
     */
    public function editLayoutAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Layout();
        $editForm = $this->createForm(new LayoutEditionType(),$entity);
        $edit_form_twig = $this->container->getParameter('template_designer_layout.edit_form_twig');
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'edit_form_twig'=>$edit_form_twig
        );
    }


    /**
     * Finds and displays a Layout entity.
     *
     * @Route("/{id}", name="layout_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }




    /**
     * Displays a form to edit an existing Layout entity.
     *
     * @Route("/edit_ajax/", name="layout_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $id = $request->request->get('layout');
            $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Layout entity.');
            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }else{
            return $this->redirect($this->generateUrl('layout_edition'));
        }

        
    }

        /**
     * Displays a layout template of an existing Layout entity.
     *
     * @Route("/show_ajax", name="layout_show_layout")
     * @Template("TemplateDesignerLayoutBundle:Layout:displayLayoutWithPositions.html.twig")
     */
    public function showTemplateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('root');
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        return array('parent'=> $entity);
    }

    /**
    * Creates a form to edit a Layout entity.
    *
    * @param Layout $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Layout $entity)
    {
        $routes = $this->get('route.manager')->getFormattedRoutesForForms();
        $templates = $this->get('template_finder')->getFormattedTemplateForForm();
        $helper = $this->get('layout.helper');
        $css = $helper->getAllCssClasses();
        $tags = $helper->getAllTags();
        $form = $this->createForm(new LayoutType(), $entity, array(
            'action' => $this->generateUrl('layout_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'routes' => $routes,
            'css'    => $css,
            'tags'   => $tags,
            'templates' => $templates
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Layout entity.
     *
     * @Route("/{id}", name="layout_update")
     * @Method("PUT")
     * @Template("TemplateDesignerLayoutBundle:Layout:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Add child to an existing Layout entity.
     *
     * @Route("/parent/add_child", name="layout_update_add")
     * @Template("TemplateDesignerLayoutBundle:Layout:edit.html.twig")
     */
    public function updateAddChildAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return $this->redirect($this->generateUrl('layout_edition'));
        }
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('parent');
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $newChild = $this->addChildToEntity($entity);
        $em->flush();

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }



    /**
     * Deletes a Layout entity.
     *
     * @Route("/{id}", name="layout_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Layout entity.');
            }
            $entity->getChildren()->clear();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('layout_edition'));
    }

    /**
     * Creates a form to delete a Layout entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('layout_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
    * @Route("/sub_layout/ajax", name="select_subs")
    * @Template()
    */
    public function selectSubsAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return $this->redirect($this->generateUrl('layout_edition'));
        }
        $root_id = $request->request->get('root');
        $em = $this->getDoctrine()->getManager();
        $root = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($root_id);
        $subs = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->findBy(array('root'=>$root));
        array_unshift($subs, $root);
        return array('subs'=>$subs);
    }

    private function addChildToEntity($entity){

        $cloned = clone($entity);
        $cloned->setName(null);
        $cloned->setCssComplementClasses('');
        if($root = $entity->getRoot()){
            $position = $root->getSubs()->count() + 1;
            $cloned->setParent($entity);
            $cloned->setRoot($root);
        }else{
            $position = $entity->getSubs()->count() + 1;
            $cloned->setParent($entity);
            $cloned->setRoot($entity);
        }
        $cloned->setPosition($position);
        $classes = (is_array($entity->getCssClasses()[0]))?$entity->getCssClasses()[0] :$entity->getCssClasses();
        if(in_array('container', $classes)){
            $cloned->setCssClasses(array('row'));
        }elseif (in_array('column', $classes)) {
            $cloned->setCssClasses(array('row'));
        }elseif (in_array('row', $classes)) {
            $cloned->setCssClasses(array(array('col-xs-12','column')));
        }
        $entity->addChild($cloned);
        return $cloned;
    }
}
