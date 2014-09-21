<?php

namespace TemplateDesigner\LayoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Layout
 */
class Layout
{
    /**
     * @var integer
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
