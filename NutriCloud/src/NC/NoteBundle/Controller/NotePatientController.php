<?php

namespace NC\NoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use NC\NoteBundle\Entity\NotePatient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class NotePatientController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'obtenir les notespatient.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "NotePatient"="FFCCCC"},
     *  resource=true,
     *  description="/pro/notepatient/{patientId} -- /patient/notepatient/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         404="Le patient recherché n'existe pas",
     *         403="Le patient recherché ne fait pas partie de votre liste de patient"
     *  },
     *  requirements={
     *  {"name"="patientid", "dataType"="int", "required"=true, "description"="Id du patient a qui on lit un objectif, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "notepatient" })
     */
    public function getNotePatientsAction($patientId)
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
        return $this->render('NCNoteBundle:Json:Note.json.twig', array('notes' => $Patient->getNotePatient()));
    }

    /**
     * Cette méthode permet d'ajouter un objectif.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "NotePatient"="FFCCCC"},
     *  resource=true,
     *  description="/pro/notepatient/create/{patientId} -- /patient/notepatient/create/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      404="Le patient recherché n'existe pas",
     *      403="Le patient recherché ne fait pas partie de votre liste de patient",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="note", "dataType"="string", "required"=true, "description"=""}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du patient a qui on ajoute un objectif, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "notepatient" })
     */
    public function createNotePatientAction($patientId)
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
        $newNotePatient = new NotePatient();
        $newNotePatient->setNote($request->request->get('note', null));
        $newNotePatient->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newNotePatient);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de modifier un notepatient.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "NotePatient"="FFCCCC"},
     *  resource=true,
     *  description="/pro/notepatient/update/{id} -- /patient/notepatient/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="note", "dataType"="string", "required"=true, "description"=""}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du notepatient à modifier"}
     * },
     * views = { "default", "pro", "patient", "notepatient" })
     */
    public function updateNotePatientAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCNoteBundle:NotePatient');
        $NotePatientToUpdate = $repository->findOneById($id);
        if ($NotePatientToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($NotePatientToUpdate->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($NotePatientToUpdate->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $request = $this->get('request');
        $NotePatientToUpdate->setNote($request->request->get('note', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($NotePatientToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de supprimer un notepatient.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "NotePatient"="FFCCCC"},
     *  resource=true,
     *  description="/pro/notepatient/delete/{id} -- /delete/notepatient/delete/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du notepatient à supprimer"}
     * },
     * views = { "default", "pro", "patient", "notepatient" })
     */
    public function deleteNotePatientAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCNoteBundle:NotePatient');
        $NotePatientToDelete = $repository->findOneById($id);
        if ($NotePatientToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($NotePatientToDelete->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($NotePatientToDelete->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($NotePatientToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
