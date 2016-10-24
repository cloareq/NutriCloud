<?php

namespace NC\AlertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \DateTime;
use NC\AlertBundle\Entity\Alert;
use Symfony\Component\HttpFoundation\Response;
use NC\ScheduleBundle\Entity\Appointment;
use NC\ScheduleBundle\Controller\ScheduleController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class AlertController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet à un pro de récupérer TOUTES les alertes de TOUS ses patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/pro/alert/get/all",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proGetAllAlertAction(){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $Patients = $Pro->getPatients();
        $all = array();
        foreach ($Patients as $Patient){
            $alerts = $Patient->getAlert();
            foreach ($alerts as $alert)
                $all[] = $alert;
        }
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $all));
    }

    /**
     * Cette méthode permet à un patient de récupérer TOUTES ses alertes.
     * @ApiDoc(
     * tags={"Patient"="#0000FF", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/patient/alert/get/all",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "patient", "alert"})
     */
    public function patientGetAllAlertAction(){
        if (!$this->get('security.context')->isGranted('ROLE_PATIENT'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $this->getUser()->getAlert()));
    }

    /**
     * Cette méthode permet à un pro de récupérer les alertes par type de TOUS ses patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/pro/alert/get/name/{alert_name}",
     *  requirements={
     *  {"name"="alert_name", "dataType"="string", "required"=true, "description"="Type d'erreur à rechercher: APPOINTMENT_NEEDED, NEW_MESSAGE"}},
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proGetAlertByNameAction($alert_name){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $Patients = $Pro->getPatients();
        $all = array();
        foreach ($Patients as $Patient){
            $alerts = $Patient->getAlert();
            foreach ($alerts as $alert)
                if ($alert->getName() == $alert_name)
                    $all[] = $alert;
        }
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $all));
    }

    /**
     * Cette méthode permet à un patient de récupérer ses alertes par type.
     * @ApiDoc(
     * tags={"Patient"="#0000FF", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/patient/alert/get/name/{alert_name}",
     *  requirements={
     *  {"name"="alert_name", "dataType"="string", "required"=true, "description"="Type d'erreur à rechercher: APPOINTMENT_NEEDED, NEW_MESSAGE"}},
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "patient", "alert"})
     */
    public function patientGetAlertByNameAction($alert_name){
        if (!$this->get('security.context')->isGranted('ROLE_PATIENT'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
        $Patient = $this->getUser();
        $all = array();
        foreach ($Patient->getAlert() as $alert)
            if ($alert->getName() == $alert_name)
                $all[] = $alert;
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $all));
    }

    /**
     * Cette méthode permet à un pro de récupérer les alertes d'un de ces patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/pro/alert/get/{patientId}",
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id du patient pour lequel on souhaite obtenir les alertes"}},
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proGetPatientAlertAction($patientId){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $Patient = $repository->findOneById($patientId);
        if ($Patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        else if ($Patient->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $Patient->getAlert()));
    }

    /**
     * Cette méthode permet à un pro de récupérer les alertes par type d'un de ces patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/pro/alert/get/id/{patientId}/name/{alert_name}",
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id du patient pour lequel on souhaite obtenir les alertes"},
     *  {"name"="alert_name", "dataType"="string", "required"=true, "description"="Type d'erreur à rechercher: APPOINTMENT_NEEDED, NEW_MESSAGE"}
     *  },
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proGetPatientAlertByNameAction($patientId, $alert_name){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $Patient = $repository->findOneById($patientId);
        if ($Patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        else if ($Patient->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        $all = array();
        foreach ($Patient->getAlert() as $alert)
            if ($alert->getName() == $alert_name)
                $all[] = $alert;
        return $this->render('NCAlertBundle::alerts.json.twig', array('alerts' => $all));
    }

    /**
     * Cette méthode permet à un pro de supprimer une alertes d'un de ces patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/pro/alert/delete/{alert_id}",
     *  requirements={
     *  {"name"="alert_id", "dataType"="int", "required"=true, "description"="Id de l'alerte à supprimer"}},
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500="Internal error."
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proDeleteAlertAction($alert_id){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCAlertBundle:Alert');
        $alert = $repository->findOneById($alert_id);
        if ($alert == NULL)
            return (new Response(json_encode(array('desc' => "L'alert recherché n'existe pas.")), 404, $this->header));
        else if ($alert->getPatient()->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "L'alert recherché n'appartient pas à un de vos patients.")), 403, $this->header));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($alert);
            $em->flush();
            return (new Response(json_encode(array('desc' => "Alert deleted.")), 200, $this->header));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un patient de supprimer une de ses alertes.
     * @ApiDoc(
     * tags={"Patient"="#0000FF", "Alert"="#FF33CC"},
     *  resource=true,
     *  description="/patient/alert/delete/{alert_id}",
     *  requirements={
     *  {"name"="alert_id", "dataType"="int", "required"=true, "description"="Id de l'alerte à supprimer"}},
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500="Internal error."
     *  },
     * views = {"default", "patient", "alert"})
     */
    public function patientDeleteAlertAction($alert_id){
        if (!$this->get('security.context')->isGranted('ROLE_PATIENT'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
        $Patient = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCAlertBundle:Alert');
        $alert = $repository->findOneById($alert_id);
        if ($alert == NULL)
            return (new Response(json_encode(array('desc' => "L'alert recherché n'existe pas.")), 404, $this->header));
        else if ($alert->getPatient()->getId() != $Patient->getId())
            return (new Response(json_encode(array('desc' => "L'alert recherché ne vous appartient pas.")), 403, $this->header));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($alert);
            $em->flush();
            return (new Response(json_encode(array('desc' => "Alert supprimé.")), 200, $this->header));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
