<?php

namespace NC\PatientBundle\Controller;

use NC\PlanBundle\Entity\PlanWeek;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\PatientBundle\Entity\Patient;
use NC\ProBundle\Entity\Pro;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Null;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PatientController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    public function getPro()
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $user = $this->getUser();
            return $user;
        }
        return NULL;
    }

    public function getConnectedUserIdAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $response->setContent(json_encode(array('id' => $this->getUser()->getId())));
        else
            $response->setContent(json_encode(array('id' => 0)));
        return ($response);
    }

    /**
     * Cette méthode permet à un patient de supprimer son compte.
     * @ApiDoc(
     * tags={"Patient"="#0000FF"},
     *  resource=true,
     *  description="/patient/remove",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     * views = {"default", "patient"})
     */
    public function removePatientAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient = $this->getUser();
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($connectedPatient->getId());
            if ($Patient == NULL)
                return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
            else {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->remove($Patient);
                    $em->flush();
                    return (new Response(json_encode(array('desc' => "Compte supprimé")), 200, $this->header));
                } catch (\Exception $e) {
                    return (new Response(json_encode(array('desc' => "Internal error.")), 500, $this->header));
                }
            }
        }
        return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
    }

    /**
     * Cette méthode permet à un pro de supprimer un de ses patients
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Patient"="#0000FF"},
     *  resource=true,
     *  description="/pro/patient/delete/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         500= "Internal Error (BDD error)"
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function proRemovePatientAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
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
            $em = $this->getDoctrine()->getManager();
            try {
                foreach ($patient->getPlans() as $tmp_plan)
                    $em->remove($tmp_plan);
                $em->remove($Patient);
                $em->flush();
                return (new Response('', 200, $this->header));
            }
            catch(\Exception $e) {
                return (new Response($e->getMessage(), 500, $this->header));
            }
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que pro.")), 401, $this->header));
      }

    /**
     * Cette méthode renvoie la list des patients du pro actuellement connecté.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Patient"="#0000FF"},
     *  resource=true,
     *  description="/pro/patient/list",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         500= "Internal Error (BDD error)"
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function listPatientAction()
    {
        if ((!$this->get('security.context')->isGranted('ROLE_PRO')))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $Pro = $this->getPro();
        if ($Pro == NULL)
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $PatientList = $Pro->getPatients();
        return $this->render('NCPatientBundle:Json:PatientList.json.twig', array("PatientList" => $PatientList));
    }

    /**
     * Cette méthode permet à un pro de créer un nouveau compte patient..
     * @ApiDoc(
     * tags={ "Pro"="#FF0000", "Patient"="#0000FF"},
     *  resource=true,
     *  description="/pro/patient/new",
     *  statusCodes={
     *         201="Data Created",
     *         400="Bad Request: At least one param is wrong",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="firstname", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="lastname", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="username", "dataType"="string", "required"=true, "format"="Au moins 6 charactères", "description"=""},
     *      {"name"="password", "dataType"="string", "required"=true,"format"="Au moins 6 charactères", "description"=""},
     *      {"name"="mail", "dataType"="string", "required"=true, "format"="Adresse email avec un format valide", "description"=""},
     *      {"name"="phone", "dataType"="string", "required"=true, "description"=""}
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function newPatientAction(){
        $request = $this->get('request');
        $emailConstraint = new EmailConstraint();

        if(strlen($request->request->get('password')) < 5)
            return (new Response(json_encode(array('desc' => 'Le mot de passe doit contenir au moins 6 caractères.')), 400, $this->header));
        else if(strlen($request->request->get('username')) < 5)
            return (new Response(json_encode(array('desc' => 'Le nom d\'utilisateur doit contenir au moins 6 caractères.')), 400, $this->header));
        else if (($this->get('validator')->validate($request->request->get('mail'),$emailConstraint)) != "")
            return (new Response(json_encode(array('desc' => 'Adresse Email invalide.')), 400, $this->header));

        $tmp_user = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCProBundle:Pro')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($tmp_user != null)
            return (new Response(json_encode(array('desc' => 'Username déjà utilisé.')), 400, $this->header));
        $tmp_user = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($tmp_user != null)
            return (new Response(json_encode(array('desc' => 'Username déjà utilisé.')), 400, $this->header));

        $newPatient = new Patient();
        $newPatient->setUsername($request->request->get('username', null));
        $newPatient->setFirstname($request->request->get('firstname', null));
        $newPatient->setLastname($request->request->get('lastname', null));
        $newPatient->setPassword($request->request->get('password', null));
        $newPatient->setMail($request->request->get('mail', null));
        $newPatient->setPhone($request->request->get('phone', null));
        $newPatient->setPasswordRecoveryHash("");
        $user = $this->getUser();
        $user->addPatient($newPatient);
        $newPatient->setPro($user);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newPatient);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e){
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de modifier les informations de son patient ou a un patient de modifier ses propres informations.
     * @ApiDoc(
     * tags={"Pro"="#FF0000", "Patient"="#0000FF"},
     *  resource=true,
     *  description="/pro/update/{patientId} -- /patient/update/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         400="Bad Request: At least one param is wrong",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  parameters={
     *      {"name"="firstname", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="lastname", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="username", "dataType"="string", "required"=true, "format"="Au moins 6 charactères", "description"=""},
     *      {"name"="mail", "dataType"="string", "required"=true, "format"="Adresse email avec un format valide", "description"=""},
     *      {"name"="phone", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="address", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="city", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="postcode", "dataType"="string", "required"=true, "description"=""}
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function updatePatientAction($patientId)
    {
        $request = $this->get('request');
        $emailConstraint = new EmailConstraint();

        if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $Patient = $this->getUser();
            if ($Patient->getId() != $patientId)
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PRO')) {
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
        } else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        if(strlen($request->request->get('username')) < 5)
            return (new Response(json_encode(array('desc' => 'Le nom d\'utilisateur doit contenir au moins 6 caractères.')), 400, $this->header));
        else if (($this->get('validator')->validate($request->request->get('mail'),$emailConstraint)) != "")
            return (new Response(json_encode(array('desc' => 'Adresse Email invalide.')), 400, $this->header));
        $Patient->setUsername($request->request->get('username', null));
        $Patient->setPhone($request->request->get('phone', null));
        $Patient->setAddress($request->request->get('address', null));
        $Patient->setCity($request->request->get('city', null));
        $Patient->setPostcode($request->request->get('postcode', null));
        $Patient->setMail($request->request->get('mail', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($Patient);
            $em->flush();
            return (new Response("", 200, $this->header));
        } catch (\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un patient de consulter toutes ses informations personnelles.
     * @ApiDoc(
     * tags={"Patient"="#0000FF"},
     *  resource=true,
     *  description="/patient/info",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *  },
     * views = {"default", "patient"})
     */
    public function getInfoAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $patient = $this->getUser();
            return $this->render('NCPatientBundle:Json:patientInfo.json.twig', array('patient' => $patient));
        }
        return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que patient.")), 401, $this->header));
    }

    /**
     * Cette méthode permet à un pro de consulter les informations personnelles d'un de ses patient.
     * @ApiDoc(
     * tags={ "Pro"="#FF0000", "Patient"="#0000FF"},
     *  resource=true,
     *  description="/pro/patient/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     * views = {"default", "pro", "patient"})
     */
    public function getPatientInfoAction($patientId)
    {
        if ((!$this->get('security.context')->isGranted('ROLE_PRO')))
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionel.")), 401, $this->header));
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $patient = $repository->findOneById($patientId);
        if ($patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        else if ($this->getUser()->getId() != $patient->getPro()->getId())
            return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        return $this->render('NCPatientBundle:Json:patientInfo.json.twig', array('patient' => $patient));
    }
}