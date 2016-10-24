<?php

namespace NC\PatientBundle\Controller;

use NC\PatientBundle\Entity\Allergy;
use NC\PatientBundle\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class AllergyController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'obtenir les allergies.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Allergy"="#000066"},
     *  resource=true,
     *  description="/pro/allergy/{patientId} -- /patient/allergy/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         404="Le patient recherché n'existe pas",
     *         403="Le patient recherché ne fait pas partie de votre liste de patient"
     *  },
     *  requirements={
     *  {"name"="patientid", "dataType"="int", "required"=true, "description"="Id du patient pour qui on accede au allergys, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "allergy"})
     */
    public function getAllergysAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        return $this->render('NCPatientBundle:Json:Allergy.json.twig', array('allergys' => $Patient->getAllergy()));
    }

    /**
     * Cette méthode permet d'ajouter des allergys.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Allergy"="#000066"},
     *  resource=true,
     *  description="/pro/allergy/create/{patientId} -- /patient/allergy/create/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      404="Le patient recherché n'existe pas",
     *      403="Le patient recherché ne fait pas partie de votre liste de patient",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="name", "dataType"="integer", "required"=true, "description"=""}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du patient a qui on ajoute des allergys, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "allergy" })
     */
    public function createAllergyAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        $request = $this->get('request');
        $newAllergy = new Allergy();
        $newAllergy->setName($request->request->get('name', null));
        $newAllergy->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newAllergy);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de modifier une allergy.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Allergy"="#000066"},
     *  resource=true,
     *  description="/pro/allergy/update/{id} -- /patient/allergy/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="name", "dataType"="integer", "required"=true, "description"=""},
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de l'allergie à modifier"}
     * },
     * views = { "default", "pro", "patient", "allergy"})
     */
    public function updateAllergyAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Allergy');
        $AllergyToUpdate = $repository->findOneById($id);
        if ($AllergyToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($AllergyToUpdate->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($AllergyToUpdate->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $request = $this->get('request');
        $AllergyToUpdate->setName($request->request->get('name', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($AllergyToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de supprimer une allergy.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Allergy"="#000066"},
     *  resource=true,
     *  description="/pro/allergy/delete/{id} -- /delete/allergy/delete/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de l'allergie à supprimer"}
     * },
     * views = { "default", "pro", "patient", "allergy"})
     */
    public function deleteAllergyAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Allergy');
        $AllergyToDelete = $repository->findOneById($id);
        if ($AllergyToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($AllergyToDelete->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($AllergyToDelete->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($AllergyToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
