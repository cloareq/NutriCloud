<?php

namespace NC\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\MessageBundle\Entity\Message;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use NC\AlertBundle\Entity\Alert;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MessageController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet à un pro d'envoyer un message a son patient en prescisant son id.\n Ainsi que pour un patient d'envoyer un message à son praticien en prescisant son propre ID.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Message"="#FFCC33"},
     *  resource=true,
     *  description="/pro/message/new -- /patient/message/new",
     *  statusCodes={
     *         201="Data Created",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="patientId", "dataType"="string", "required"=true, "description"="Id du patient, un patient aillant seulement un praticient, il a donc une seul conversation. La clé est donc son ID"},
     *      {"name"="text", "dataType"="string", "required"=true, "description"="Message à poster dans la conversation"}
     *  },
     * views = { "default", "pro", "patient", "message" })
     */
    public function newMessageAction()
    {
        $request = $this->get('request');
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $Patient = $repository->findOneById($request->request->get('patientId', null));

        if ($Patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        $newMessage = new Message();
        if ($this->get('security.context')->isGranted('ROLE_PRO')){
            $newMessage->setSender("pro");
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')){
            $newMessage->setSender("patient");
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));

        /*CREATE ALERT*/
        $alert = new Alert();
        $alert->setLevel(1);
        $alert->setIsSaw(False);
        $alert->setName("NEW_MESSAGE");
        $alert->setPro($Patient->getPro());
        $alert->setPatient($Patient);
        if ($newMessage->getSender() == "patient")
            $alert->setTarget(1);
        else // pro
            $alert->setTarget(3);
        /**//**/
        $newMessage->setDate(new \Datetime());
        $newMessage->setText($request->request->get('text', null));
        $newMessage->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($alert);
            $em->persist($newMessage);
            $em->flush();
            //return (new Response("", 201, $this->header));
            if ($newMessage->getSender() == "patient")
                return $this->redirect($this->buildUrl(
                    $Patient->getUsername(),
                    $Patient->getPro()->getUsername(),
                    "NEW_MESSAGE",
                    $newMessage->getText()));
            else // pro
                return $this->redirect($this->buildUrl(
                    $Patient->getPro()->getUsername(),
                    $Patient->getUsername(),
                    "NEW_MESSAGE",
                    $newMessage->getText()));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    private function buildUrl($sender, $receiver, $notif_type, $content){
        return "http://beta.nutricloud.eu:3001/websocket?sender=".$sender."&receiver=".$receiver."&notiftype=".$notif_type."&content=".urlencode($content);
    }

    /**
     * Cette méthode permet au pro et patient de réccupérer la conversation qui les concernent.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Message"="#FFCC33"},
     *  resource=true,
     *  description="/pro/message/get/{patientId} -- /patient/message/get/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     * views = { "default", "pro", "patient", "message" })
     */
    public function getMessageAction($patientId)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $Patient = $repository->findOneById($patientId);
        if ($Patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')){
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
            /* ALERT */
            $alerts = $Pro->getAlert();
            foreach ($alerts as $alert){
                if ($alert->getTarget() <= 2 and $alert->getName() == "NEW_MESSAGE" and $alert->getPatient()->getPro()->getId() == $Pro->getId()) {
                    $em = $this->getDoctrine()->getManager();
                    try {
                        $em->remove($alert);
                        $em->flush();
                    } catch (\Exception $e) {
                        return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
                    }
                }
            }
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')){
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
            /* ALERT */
            $alerts = $Patient->getAlert();
            foreach ($alerts as $alert){
                if ($alert->getTarget() >= 2 and $alert->getName() == "NEW_MESSAGE" and $alert->getPatient()->getId() == $Patient->getId()) {
                    $em = $this->getDoctrine()->getManager();
                    try {
                        $em->remove($alert);
                        $em->flush();
                    }
                    catch(\Exception $e){
                        return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
                    }
                }
            }
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        return $this->render('NCMessageBundle:Json:Message.json.twig', array('messages' => $Patient->getMessage()));
    }
}
