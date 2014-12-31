<?php 
namespace TemplateDesigner\LayoutBundle\Entity;


interface ContentSubjectInterface
{

    /**
     * @return object
     */
    public function getContent();

    /**
     * @return string
     */
    public function getContentText();


}
