<?php

namespace TemplateDesigner\LayoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TemplateDesigner\LayoutBundle\Entity\Layout;

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
     */
    public function createAction()
    {
        $edit_form_twig = $this->container->getParameter('template_designer_layout.base_twig');
        $assetic = $this->container->getParameter('template_designer_layout.assetic');
        $engine = $this->container->getParameter('template_designer_layout.template_engine');
        return $this->render('TemplateDesignerLayoutBundle:Layout:create'.ucfirst($engine).'.html.twig',array('base_twig'=>$edit_form_twig,'template_assetic'=>$assetic));
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
        $classes = $layout['cssClass'];
        $children = (isset($layout['children']))?$layout['children']:null;

        $root = $helper->transform($layout,$name,$classes,$tag);
        $errorList = $this->get('validator')->validate($root);
        $errors = '';
        if (count($errorList) == 0) {
            $em->flush($root);
            if($children){
               $helper->recursiveTransform($children,$root,$root);
                $i = 0;
                foreach ($root->getSubs() as $sub) {
                    $sub->setPosition(++$i);
                    $em->flush($sub);
                }  
            }
        }else{
            foreach ($errorList as $error) {
                $errors[] = $error->getMessage();
            }
        }
        return new JsonResponse($errors);
    }

    /**
     * Displays a form to edit an existing Layout entity.
     *
     * @Route("/edit", name="layout_edition")
     * @Method("GET")
     */
    public function editLayoutAction()
    {
        // instances of layout choice form and entity from config
        $config = $this->container->getParameter('template_designer_layout.class_configuration');
        $engine = $this->container->getParameter('template_designer_layout.template_engine');
        $edit_form_twig = $this->container->getParameter('template_designer_layout.base_twig');
        $assetic = $this->container->getParameter('template_designer_layout.assetic');

        $class = new \ReflectionClass($config['entity']);
        $entity = $class->newInstance();
        $formClass =  new \ReflectionClass($config['layout_choice_form']);
        $formType = $formClass->newInstance();

        $editForm = $this->createForm($formType,$entity);
        
        return $this->render('TemplateDesignerLayoutBundle:Layout:editLayout'.ucfirst($engine).'.html.twig',array(
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
            'base_twig' => $edit_form_twig,
            'template_assetic' => $assetic
            ));
    }


    /**
     * Displays a form to edit an existing Layout entity.
     *
     * @Route("/edit_ajax/", name="layout_edit")
     */
    public function editAction(Request $request)
    {
        
        if(!$request->isXmlHttpRequest()){return $this->redirect($this->generateUrl('layout_edition'));}
        $engine = $this->container->getParameter('template_designer_layout.template_engine');
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('layout');
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);
        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Layout entity.');
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TemplateDesignerLayoutBundle:Layout:edit'.ucfirst($engine).'.html.twig',array(
            'entity'        => $entity,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
            ));
    }

    /**
     * Displays a layout template of an existing Layout entity.
     *
     * @Route("/show_ajax", name="layout_show_layout")
     */
    public function showTemplateAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){return $this->redirect($this->generateUrl('layout_edition'));}
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('root');
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        return $this->render('TemplateDesignerLayoutBundle:Layout:displayLayoutWithPositions.html.twig',array('parent'=> $entity));
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
        // options
        $routes = $this->get('route.manager')->getFormattedRoutesForForms();
        $templates = $this->get('template_finder')->getFormattedTemplateForForm();
        $helper = $this->get('layout.helper');
        $css = $helper->getAllCssClasses();
        $tags = $helper->getAllTags();
        // instance of layout edit form from config
        $config = $this->container->getParameter('template_designer_layout.class_configuration');
        $formClass =  new \ReflectionClass($config['layout_edit_form']);
        $formType = $formClass->newInstance();

        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl('layout_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'routes' => $routes,
            'css'    => $css,
            'tags'   => $tags,
            'templates' => $templates,
            'helper'    => $helper
        ));

        return $form;
    }

    /**
     * Edits an existing Layout entity.
     *
     * @Route("/{id}", name="layout_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, Layout $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $engine = $this->container->getParameter('template_designer_layout.template_engine');
        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
        }
        return $this->render('TemplateDesignerLayoutBundle:Layout:edit'.ucfirst($engine).'.html.twig',array(
            'entity'        => $entity,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }


    /**
     * Add child to an existing Layout entity.
     *
     * @Route("/parent/add_child", name="layout_update_add")
     */
    public function updateAddChildAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return $this->redirect($this->generateUrl('layout_edition'));
        }
        $engine = $this->container->getParameter('template_designer_layout.template_engine');
        $em = $this->getDoctrine()->getManager();
        $helper = $this->get('layout.helper');
        $id = $request->request->get('parent');
        $entity = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Layout entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $newChild = $helper->addChildToEntity($entity);
        $em->flush();         

        return $this->render('TemplateDesignerLayoutBundle:Layout:edit'.ucfirst($engine).'.html.twig',array(
            'entity'        => $entity,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }



    /**
     * Deletes a Layout entity.
     *
     * @Route("/{id}", name="layout_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Layout $entity)
    {
        $form = $this->createDeleteForm($entity->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
            ->add('submit', 'submit', array('label' => 'Delete','attr'=>array('class'=>'btn btn-default')))
            ->getForm()
        ;
    }

    /**
    * @Route("/sub_layout/ajax", name="select_subs")
    */
    public function selectSubsAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return $this->redirect($this->generateUrl('layout_edition'));
        }
        $root_id = $request->request->get('root');
        $em = $this->getDoctrine()->getManager();
        $root = $em->getRepository('TemplateDesignerLayoutBundle:Layout')->find($root_id);
        $subs = $root->getSubs()->toArray();
        array_unshift($subs, $root);
        return $this->render('TemplateDesignerLayoutBundle:Layout:selectSubs.html.twig',array('subs'=> $subs));
    }
}
