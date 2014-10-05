<?php 
namespace TemplateDesigner\LayoutBundle\Entity;

/**
 * Une interface que le sujet de la facture devrait implémenter.
 * Dans la plupart des circonstances, il ne devrait y avoir qu'un unique objet
 * qui implémente cette interface puisque le ResolveTargetEntityListener peut
 * changer seulement la cible d'un objet unique.
 */
interface ContentSubjectInterface
{
    // Liste toutes les méthodes additionnelles dont votre
    // InvoiceBundle aura besoin pour accéder au sujet afin
    // que vous soyez sûr que vous avez accès à ces méthodes.

    /**
     * @return object
     */
    public function getContent();

    /**
     * @return string
     */
    public function getContentText();


}
