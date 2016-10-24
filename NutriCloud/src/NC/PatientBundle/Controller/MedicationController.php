<?php

namespace NC\PatientBundle\Controller;

use NC\PatientBundle\Entity\Medication;
use NC\PatientBundle\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class MedicationController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'obtenir les medications.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Medication"="#C0C0C0"},
     *  resource=true,
     *  description="/pro/medication/{patientId} -- /patient/medication/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         404="Le patient recherché n'existe pas",
     *         403="Le patient recherché ne fait pas partie de votre liste de patient"
     *  },
     *  requirements={
     *  {"name"="patientid", "dataType"="int", "required"=true, "description"="Id du patient pour qui on accede au medications, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "medication"})
     */
    public function getMedicationsAction($patientId)
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
        return $this->render('NCPatientBundle:Json:Medication.json.twig', array('medications' => $Patient->getMedication()));
    }

    /**
     * Cette méthode permet d'ajouter des medications.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Medication"="#C0C0C0"},
     *  resource=true,
     *  description="/pro/medication/create/{patientId} -- /patient/medication/create/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      404="Le patient recherché n'existe pas",
     *      403="Le patient recherché ne fait pas partie de votre liste de patient",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="medication", "dataType"="integer", "required"=true, "description"="number of medication"}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du patient a qui on ajoute des medications, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "medication" })
     */
    public function createMedicationAction($patientId)
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
        $newMedication = new Medication();
        $newMedication->setName($request->request->get('name', null));
        $newMedication->setNote($request->request->get('note', null));
        $newMedication->setIsActive($request->request->get('is_active', null));
        $newMedication->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newMedication);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de modifier une medication.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Medication"="#C0C0C0"},
     *  resource=true,
     *  description="/pro/medication/update/{id} -- /patient/medication/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="medication", "dataType"="integer", "required"=true, "description"="number of medications"},
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de la cal à modifier"}
     * },
     * views = { "default", "pro", "patient", "medication"})
     */
    public function updateMedicationAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Medication');
        $MedicationToUpdate = $repository->findOneById($id);
        if ($MedicationToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($MedicationToUpdate->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($MedicationToUpdate->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $request = $this->get('request');
        $MedicationToUpdate->setName($request->request->get('name', null));
        $MedicationToUpdate->setNote($request->request->get('note', null));
        $MedicationToUpdate->setIsActive($request->request->get('is_active', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($MedicationToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de supprimer une medication.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Medication"="#C0C0C0"},
     *  resource=true,
     *  description="/pro/medication/delete/{id} -- /patient/medication/delete/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id de la cal à supprimer"}
     * },
     * views = { "default", "pro", "patient", "medication"})
     */
    public function deleteMedicationAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Medication');
        $MedicationToDelete = $repository->findOneById($id);
        if ($MedicationToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($MedicationToDelete->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($MedicationToDelete->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($MedicationToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
