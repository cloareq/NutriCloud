<?php

namespace NC\PlanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\PlanBundle\Entity\Aliment;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AlimentController extends Controller {

    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet à un pro d'ajouter un aliment à sa base de données.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Aliment" = "#FF8000"},
     *  resource=true,
     *  description="/pro/aliment/new",
     *  statusCodes={
     *         201="Data Created",
     *         401="Unauthorized: You are not connected",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="aliment_name", "dataType"="string", "required"=true, "description"="Nom de l'aliment à ajouter"}
     *  },
     * views = {"default", "pro", "aliment"})
     */
    public function newAlimentAction() {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que pro.")), 401, $this->header));
        $request = $this->get('request');
        $Pro = $this->getUser();
        $Aliment = new Aliment();
        $Aliment->setName($request->request->get('aliment', null));
        $Aliment->setPro($Pro);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($Aliment);
            $em->flush();
            return (new Response("", 201, $this->header));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro d'accèder à ces aliments.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Aliment" = "#FF8000"},
     *  resource=true,
     *  description="/pro/aliment/get",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected"
     *  },
     * views = {"default", "pro", "aliment"}
     * )
     */
    public function getAlimentAction() {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que pro.")), 401, $this->header));
        return $this->render('NCPlanBundle:Json:Aliments.json.twig', array('aliments' => $this->getUser()->getAliment()));
    }
}