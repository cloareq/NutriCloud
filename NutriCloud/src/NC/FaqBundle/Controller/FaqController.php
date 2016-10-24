<?php

namespace NC\FaqBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\FaqBundle\Entity\FaqElem;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FaqController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'ajouter une question/reponse à la FAQ.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "FAQ"="#6600CC"},
     *  resource=true,
     *  description="/pro/faq/new",
     *  statusCodes={
     *      201="Data Created",
     *      401="Unauthorized: You are not connected",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="category", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="question", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="response", "dataType"="string", "required"=true, "description"=""}
     *  },
     * views = { "default", "faq", "pro" })
     */
    public function newAction()
    {
        $request = $this->get('request');
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $newElem = new FaqElem();
        $newElem->setCategory($request->request->get('category', null));
        $newElem->setQuestion($request->request->get('question', null));
        $newElem->setResponse($request->request->get('response', null));
        $user = $this->getUser();
        $user->addFaqElem($newElem);
        $newElem->setPro($user);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newElem);
            $em->flush();
            return (new Response("", 201, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet renvoie sa FAQ pour un pro et pour un patient renvoie la FAQ de son praticien.
     * @ApiDoc(
     * tags={"Pro" = "#FF0000", "Patient" = "#0000FF", "FAQ"= "#6600CC"},
     *  resource=true,
     *  description="/pro/faq -- /patient/faq",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected"
     *  },
     * views = { "default", "faq", "pro", "patient" })
     */
    public function getFaqAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO'))
            return $this->render('NCFaqBundle:Json:Faq.json.twig', array("faqList" => $this->getUser()->getFaqElems()));
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            return $this->render('NCFaqBundle:Json:Faq.json.twig', array("faqList" => $this->getUser()->getPro()->getFaqElems()));
        return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
    }

    /**
     * Cette méthode permet à un pro de supprimer une question/réponse de sa FAQ.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "FAQ"="#6600CC"},
     *  resource=true,
     *  description="/pro/faq/remove/{id}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id of the FAQ elem to delete"}
     * },
     * views = { "default", "faq", "pro" })
     */
    public function removeFaqAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $connectedPro =  $this->getUser();
        $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('NCFaqBundle:FaqElem');
        $FaqToDelete = $repository->findOneById($id);
        if ($FaqToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        else if ($FaqToDelete->getPro()->getId() != $connectedPro->getId())
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($FaqToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de modifier une question/réponse de sa FAQ.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "FAQ"="#6600CC"},
     *  resource=true,
     *  description="/pro/faq/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="category", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="question", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="response", "dataType"="string", "required"=true, "description"=""}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id of the FAQ elem to delete"}
     * },
     * views = { "default", "faq", "pro" })
     */
    public function updateFaqAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $connectedPro =  $this->getUser();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCFaqBundle:FaqElem');
        $FaqToUpdate = $repository->findOneById($id);
        if ($FaqToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($FaqToUpdate->getPro()->getId() != $connectedPro->getId())
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        $request = $this->get('request');
        $FaqToUpdate->setCategory($request->request->get('category', null));
        $FaqToUpdate->setQuestion($request->request->get('question', null));
        $FaqToUpdate->setResponse($request->request->get('response', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($FaqToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}