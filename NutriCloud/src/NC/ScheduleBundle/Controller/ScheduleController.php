<?php

namespace NC\ScheduleBundle\Controller;

use NC\ScheduleBundle\Entity\Appointment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use \DateTime;
use NC\AlertBundle\Entity\Alert;

class ScheduleController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    public function checkAppointmentAlert($Patient)
    {
        $alerts = $Patient->getAlert();
        $today = new \DateTime('now');
        $futur = (new \DateTime('now'))->add(\DateInterval::createFromDateString('31 days'));
        $count = 0;

        foreach ($alerts as $alert){
            if ($alert->getName() == "APPOINTMENT_NEEDED");
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($alert);
                $em->flush();
            }
            catch(\Exception $e){
                //return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
                echo '';
            }
        }
        if (sizeof($Patient->getAppointmentByWeek($today->format('Y-m-d\TH:i:s.\0\0\Z'), $futur->format('Y-m-d\TH:i:s.\0\0\Z'))) == 0) {
            //          echo sizeof($Patient->getAppointmentByWeek($today->format('Y-m-d\tH-i-s.\0\0\Z'), $futur->format('Y-m-d\tH-i-s.\0\0\Z')));
            /*CREATE ALERT*/
            $newAlert = new Alert();
            $newAlert->setLevel(2);
            $newAlert->setIsSaw(False);
            $newAlert->setName("APPOINTMENT_NEEDED");
            $newAlert->setPro($Patient->getPro());
            $newAlert->setPatient($Patient);
            $newAlert->setTarget(2);
            $em = $this->getDoctrine()->getManager();
            try {
                $count = $count + 1;
                $em->persist($newAlert);
                $em->flush();
            } catch (\Exception $e) {
                //        return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
                echo "";
            }
            /**//**/
        }
    }

    /**
     * Cette méthode permet à un pro d'actualiser les alertes APPOINTMENT_NEEDED de tous ses patient.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Alert"="#FF33CC", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/alert/update/appointment",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected"
     *  },
     * views = {"default", "pro", "alert"})
     */
    public function proCheckApppointmentAlertAction(){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $Patients = $Pro->getPatients();
        foreach ($Patients as $Patient){
            $this->checkAppointmentAlert($Patient);
        }
        return (new Response(json_encode(array('APPOINTMENT_NEEDED' => "checked")), 200, $this->header));
    }

    /**
     * Cette méthode permet à un pro de récupérer TOUT ses rendez-vous.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/get/all",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "pro", "schedule"})
     */
    public function getProAllAppointmentAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        return $this->render('NCScheduleBundle:Json:schedule.json.twig', array('appointments' => $Pro->getAppointment()));
    }

    /**
     * Cette méthode permet à un pro de récupérer tout les rendez-vous entre deux dates.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/get/{start}/{end}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     *  requirements={
     *  {"name"="start", "dataType"="string", "required"=true, "description"="Date de debut. Format: YYYY-mm-ddTHH:MM:SS.ffffffZ"},
     *  {"name"="end", "dataType"="string", "required"=true, "description"="Date de fin. Format: YYYY-mm-ddTHH:MM:SS.ffffffZ"}
     * },
     * views = {"default", "pro", "schedule"})
     */
    public function getProAppointmentBetweenTwoDatesAction($start, $end) // format YYYY-mm-ddTHH:MM:SS.ffffffZ
    {
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        return $this->render('NCScheduleBundle:Json:schedule.json.twig', array('appointments' => $Pro->getAppointmentByWeek($start, $end)));
    }

    /**
     * Cette méthode permet à un patient de récupérer TOUT ses rendez-vous.
     * @ApiDoc(
     * tags={"Patient"="#0000FF", "Schedule"="#000000"},
     *  resource=true,
     *  description="/patient/appointment/get/all",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "patient", "schedule"})
     */
    public function getPatientAppointmentAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_PATIENT'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
        $Patient = $this->getUser();
        return $this->render('NCScheduleBundle:Json:schedule.json.twig', array('appointments' => $Patient->getAppointment()));
    }

    /**
     * Cette méthode permet à un pro de récupérer les rendez-vous D'UN de ses patients.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Patient"="#0000FF", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/get/patient/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id du patient pour lequel on souhaite récupérer le rendez-vous"}
     * },
     * views = {"default", "pro", "patient", "schedule"})
     */
    public function proGetPatientAppointmentAction($patientId)
    {
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
        return $this->render('NCScheduleBundle:Json:schedule.json.twig', array('appointments' => $Patient->getAppointment()));
    }

    /**
     * Cette méthode permet à un pro de supprimer un de ses rendez-vous.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/remove/{appointmentId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="appointmentId", "dataType"="int", "required"=true, "description"="Id du rendez-vous que l'on souhaite supprimer"}
     * },
     * views = {"default", "pro", "patient", "schedule"})
     */
    public function removeAppointmentAction($appointmentId){
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('NCScheduleBundle:Appointment');
        $appointment = $repository->findOneById($appointmentId);
        if ($appointment == NULL)
            return (new Response(json_encode(array('desc' => "Le rendez-vous recherché n'existe pas.")), 404, $this->header));
        else if ($appointment->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        try {
            $em->remove($appointment);
            $em->flush();
            $this->checkAppointmentAlert($appointment->getPatient());
            return (new Response('', 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de créer un nouveau rendez-vous.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/create/{patientId}",
     *  statusCodes={
     *          201="Data Created",
     *          401="Unauthorized: You are not connected",
     *          403="Forbidden: You are connected but you are not allowed to access to this data",
     *          404="Not Found: Data does not exist",
     *          500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id du patient pour lequel nous souhaitons créer un rendez-vous"}
     * },
     *  parameters={
     *      {"name"="start", "dataType"="string", "required"=true, "format"="YYYY-mm-ddTHH:MM:SS.ffffffZ", "description"="Date de début de rendez-vous"},
     *      {"name"="end", "dataType"="string", "required"=true, "format"="YYYY-mm-ddTHH:MM:SS.ffffffZ", "description"="Date de fin de rendez-vous"},
     *      {"name"="comment", "dataType"="string", "required"=true,  "description"="Commentaire lié au rendez-vous"},
     *  },
     * views = {"default", "pro", "patient", "schedule"})
     */
    public function createAppointmentAction($patientId){
        $request = $this->get('request');
        $Pro = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $patient = $repository->findOneById($patientId);
        if ($patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        else if ($patient->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        $newAppointment = new Appointment();
        $newAppointment->setStart($request->request->get('start', null));
        $newAppointment->setEnd($request->request->get('end', null));
        $newAppointment->setComment($request->request->get('comment', null));
        $newAppointment->setPatient($patient);
        $newAppointment->setPro($patient->getPro());
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newAppointment);
            $em->flush();
            $this->checkAppointmentAlert($newAppointment->getPatient());
            return (new Response('', 201, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de modifier rendez-vous existant.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Schedule"="#000000"},
     *  resource=true,
     *  description="/pro/appointment/update/{appointmentId}",
     *  statusCodes={
     *          201="Data Created",
     *          401="Unauthorized: You are not connected",
     *          403="Forbidden: You are connected but you are not allowed to access to this data",
     *          404="Not Found: Data does not exist",
     *          500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="appointmentId", "dataType"="int", "required"=true, "description"="Id du rendez-vous que l'on souhaite modifier"}
     * },
     *  parameters={
     *      {"name"="start", "dataType"="string", "required"=true, "format"="YYYY-mm-ddTHH:MM:SS.ffffffZ", "description"="Date de début de rendez-vous"},
     *      {"name"="end", "dataType"="string", "required"=true, "format"="YYYY-mm-ddTHH:MM:SS.ffffffZ", "description"="Date de fin de rendez-vous"},
     *      {"name"="comment", "dataType"="string", "required"=true,  "description"="Commentaire lié au rendez-vous"},
     *  },
     * views = {"default", "pro", "patient", "schedule"})
     */
    public function updateAppointmentAction($appointmentId)
    {
        $request = $this->get('request');
        if (!$this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $Pro = $this->getUser();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCScheduleBundle:Appointment');
        $appointment = $repository->findOneById($appointmentId);
        if ($appointment == NULL)
            return (new Response(json_encode(array('desc' => "Le rendez-vous recherché n'existe pas.")), 404, $this->header));
        else if ($appointment->getPro()->getId() != $Pro->getId())
            return (new Response(json_encode(array('desc' => "Le rendez-vous recherché ne fait pas partie de vos rendez-vous.")), 403, $this->header));
        $appointment->setStart($request->request->get('start', null));
        $appointment->setEnd($request->request->get('end', null));
        $appointment->setComment($request->request->get('comment', null));
        $em = $this->getDoctrine()->getManager();
        try {
            #$em->persist($appointment);
            $em->flush();
            $this->checkAppointmentAlert($appointment->getPatient());
            return (new Response('', 200, $this->header));
        } catch (\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }
}