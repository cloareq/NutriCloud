<?php

namespace NC\PatientBundle\Controller;

use NC\PatientBundle\Entity\Calorie;
use NC\PatientBundle\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class CalorieController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'obtenir les calories.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Calorie"="#000066"},
     *  resource=true,
     *  description="/pro/calorie/{patientId} -- /patient/calorie/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         404="Le patient recherché n'existe pas",
     *         403="Le patient recherché ne fait pas partie de votre liste de patient"
     *  },
     *  requirements={
     *  {"name"="patientid", "dataType"="int", "required"=true, "description"="Id du patient pour qui on accede au calories, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "calorie"})
     */
    public function getCaloriesAction($patientId)
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
        return $this->render('NCPatientBundle:Json:Calorie.json.twig', array('calories' => $Patient->getCalorie()));
    }

    /**
     * Cette méthode permet d'ajouter des calories.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Calorie"="#000066"},
     *  resource=true,
     *  description="/pro/calorie/create/{patientId} -- /patient/calorie/create/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      404="Le patient recherché n'existe pas",
     *      403="Le patient recherché ne fait pas partie de votre liste de patient",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="calorie", "dataType"="integer", "required"=true, "description"="number of calorie"}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du patient a qui on ajoute des calories, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "calorie" })
     */
    public function createCalorieAction($patientId)
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
        $newCalorie = new Calorie();
        $newCalorie->setCalorie($request->request->get('calorie', null));
        $newCalorie->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newCalorie);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de modifier une calorie.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Calorie"="#000066"},
     *  resource=true,
     *  description="/pro/calorie/update/{id} -- /patient/calorie/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="calorie", "dataType"="integer", "required"=true, "description"="number of calories"},
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de la cal à modifier"}
     * },
     * views = { "default", "pro", "patient", "calorie"})
     */
    public function updateCalorieAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Calorie');
        $CalorieToUpdate = $repository->findOneById($id);
        if ($CalorieToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($CalorieToUpdate->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($CalorieToUpdate->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $request = $this->get('request');
        $CalorieToUpdate->setCalorie($request->request->get('calorie', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($CalorieToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de supprimer une calorie.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Calorie"="#000066"},
     *  resource=true,
     *  description="/pro/calorie/delete/{id} -- /delete/calorie/delete/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de la cal à supprimer"}
     * },
     * views = { "default", "pro", "patient", "calorie"})
     */
    public function deleteCalorieAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Calorie');
        $CalorieToDelete = $repository->findOneById($id);
        if ($CalorieToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($CalorieToDelete->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($CalorieToDelete->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($CalorieToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
