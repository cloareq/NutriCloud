<?php

namespace NC\ProBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use NC\ProBundle\Entity\Pro;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\HttpFoundation\Response;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\ZMQ\Context;
//use React\ZMQ\Pusher;
use Gos\Bundle\WebSocketBundle\Pusher;
use Gos\Bundle\WebSocketBundle\Event\Events;
use Gos\Bundle\WebSocketBundle\Event\PushHandlerEvent;
use Gos\Bundle\WebSocketBundle\Pusher\AbstractServerPushHandler;
use Gos\Bundle\WebSocketBundle\Pusher\MessageInterface;
use Gos\Bundle\WebSocketBundle\Pusher\PusherInterface;
require  __DIR__ . '/../../../../vendor/autoload.php';
/*
 *         200="OK",
 *         201="Data Created",
 *         400="Bad Request: At least one param is wrong";
 *         401="Unauthorized: You are not connected",
 *         403="Forbidden: You are connected but you are not allowed to access to this data",
 *         404="Not Found: Data does not exist",
 *         500= "Internal Error (BDD error)"
 */

class ProController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }
    /* GENERIC */
    public function getPro()
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')){
            $user = $this->getUser();
            return $user;
           }
        return NULL;
    }

    /* ROUTE */
    /**
     * Cette méthode permet de savoir si un pro ou un patient est connecté et permet de réccupérer son id.
     * @ApiDoc(
     * tags={
     *      "Pro" = "#FF0000",
     *      "Patient" = "#0000FF"
     *  },
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/pro/whoisconnected - http://nutricloud.eu/NutriCloud/web/app_dev.php/patient/whoisconnected",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected"
     *  },
     *  views = {"default", "pro", "patient"})
     */
    public function whoIsConnectedAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO'))
            return (new Response(json_encode(array('role' => "Pro", "id" => $this->getUser()->getId())), 200, $this->header));
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            return (new Response(json_encode(array('role' => "Pro", "id" => $this->getUser()->getId())), 200, $this->header));
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
    }

    public function sendMailAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('jeremy.maignan@gmail.com')
            ->setTo('jeremy.maignan@epitech.eu')
            ->setBody($this->renderView('NCProBundle:Html:test.html.twig'), 'text/html');
        $this->get('mailer')->send($message);
        return (new Response(json_encode(array('desc' => "okok")), 200, $this->header));
    }

    /**
     * Cette méthode permet de supprimer un compte pro.
     * @ApiDoc(
     * tags={
     *      "Pro" = "#FF0000",
     *  },
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/pro/remove",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         500= "Internal Error (BDD error)"
     *  },
     * views = {"default", "pro"})
     */
    public function removeProAction()
    {
        $Pro = $this->getPro();
        if ($Pro == NULL)
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($Pro);
            $em->flush();
            return (new Response('', 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet d'obtenir les informations sur un pro.
     * @ApiDoc(
     * tags={
     *      "Pro" = "#FF0000",
     *  },
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/pro/info",
     * statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected"
     *  },
     * views = {"default", "pro"})
     */
    public function getProInfoAction()
    {
        $Pro = $this->getPro();
        if ($Pro == NULL)
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        return $this->render('NCProBundle:Json:proInfo.json.twig', array('Pro' => $Pro));
    }

    /**
     * Cette méthode est chargé de la création de nouveaux comptes patient ou pro.
     * @ApiDoc(
     * tags={
     *      "Pro" = "#FF0000",
     *      "Patient" = "#0000FF"
     *  },
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/register",
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
     *      {"name"="mail", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="phone", "dataType"="string", "required"=true, "description"=""}
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function registerAction()
    {
        $emailConstraint = new EmailConstraint();
        $request = $this->get('request');

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
        $pro = new Pro();
        $pro->setUsername($request->request->get('username', null));
        $pro->setFirstname($request->request->get('firstname', null));
        $pro->setLastname($request->request->get('lastname', null));
        $pro->setPassword($request->request->get('password', null));
        $pro->setMail($request->request->get('mail', null));
        $pro->setPhone($request->request->get('phone', null));
        $pro->setPasswordRecoveryHash("");
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($pro);
            $em->flush();
            return (new Response('', 201, $this->header));
        } catch (\Exception $e){
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode est chargé de la mise à jour des informations des comptes pro. Notement ajouter une adresse.
     * @ApiDoc(
     * tags={"Pro"="#FF0000"},
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/pro/update",
     *  statusCodes={
     *         200="OK",
     *         400="Bad Request: At least one param is wrong",
     *         401="Unauthorized: You are not connected",
     *         500= "Internal Error (BDD error)"
     *  },
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
     * views = {"default", "pro"})
     */
    public function UpdateProAction()
    {
        $emailConstraint = new EmailConstraint();
        $Pro = $this->getPro();

        $request = $this->get('request');
       if ($Pro == NULL)
           return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que professionnel.")), 401, $this->header));
        else if(strlen($request->request->get('username')) < 5)
            return (new Response(json_encode(array('desc' => 'Le nom d\'utilisateur doit contenir au moins 6 caractères.')), 400, $this->header));
        else if (($this->get('validator')->validate($request->request->get('mail'),$emailConstraint)) != "")
            return (new Response(json_encode(array('desc' => 'Adresse Email invalide.')), 400, $this->header));
        $Pro->setUsername($request->request->get('username', null));
        $Pro->setFirstname($request->request->get('firstname', null));
        $Pro->setLastname($request->request->get('lastname', null));
        $Pro->setPhone($request->request->get('phone', null));
        $Pro->setAddress($request->request->get('address', null));
        $Pro->setCity($request->request->get('city', null));
        $Pro->setPostcode($request->request->get('postcode', null));
        $Pro->setMail($request->request->get('mail', null));
        $Pro->setPasswordRecoveryHash("");
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($Pro);
            $em->flush();
            return (new Response('', 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }

    /**
     * Cette méthode est chargé de générer un hashcode le set pour un utilisateur et envoie un mail à l'utilisateur avec le hashcode pour modifier son mot de passe.
     * @ApiDoc(
     * tags={"Pro"="#FF0000",  "Patient" = "#0000FF"},
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/passwordforgotten",
     *  statusCodes={
     *         200="OK",
     *         404="user not found",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "format"="Au moins 6 charactères", "description"=""}
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function forgotPasswordAction(){
        $request = $this->get('request');
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCProBundle:Pro')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($user == null)
            $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($user == null)
            return (new Response(json_encode(array('desc' => "L'utilisateur n'existe pas.")), 404, $this->header));
        $user->setPasswordRecoveryHash(str_replace('.', "", uniqid('', true). uniqid('', true). uniqid('', true)));
        $message = \Swift_Message::newInstance()
            ->setSubject('Nutricloud Récupération de mot de passe')
            ->setFrom('conctact.nutricloud@gmail.com')
            ->setTo($user->getMail())
            ->setBody($this->renderView('NCProBundle:Html:passwordrecovery.html.twig', array('hashcode' => $user->getPasswordRecoveryHash())), 'text/html');
        $this->get('mailer')->send($message);

        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($user);
            $em->flush();
            return (new Response('Email envoyé à l\'adresse ' . $user->getMail() , 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
        return (new Response('', 200, $this->header));

    }

    /**
     * Cette méthode permet à un utilisateur de changer son mot de passe, verrifie que le hashcode et le login match puis supprime le hashcode de la bdd.
     * @ApiDoc(
     * tags={"Pro"="#FF0000",  "Patient" = "#0000FF"},
     *  resource=true,
     *  description="http://nutricloud.eu/NutriCloud/web/app_dev.php/passwordrecovery",
     *  statusCodes={
     *         200="OK",
     *         401="bad argument",
     *         404="user not found",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "format"="Au moins 6 charactères", "description"=""},
     *      {"name"="password", "dataType"="string", "required"=true, "format"="Au moins 6 charactères", "description"=""},
     *      {"name"="hashcode", "dataType"="string", "required"=true, "description"="hashcode qui a été envoyé par mail dans une url, doit matcher avec le login en bdd"}
     *  },
     * views = {"default", "pro", "patient"})
     */
    public function updatePasswordAction()
    {
        $request = $this->get('request');
        if(strlen($request->request->get('password')) < 5)
            return (new Response(json_encode(array('desc' => 'Le mot de passe doit contenir au moins 6 caractères.')), 400, $this->header));
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCProBundle:Pro')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($user == null)
            $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient')->findOneByUsername(array('username' => $request->request->get('username', null)));
        if ($user == null)
            return (new Response(json_encode(array('desc' => "L'utilisateur n'existe pas.")), 404, $this->header));
        if ($user->getPasswordRecoveryHash() != $request->request->get('hashcode', null))
            return (new Response(json_encode(array('desc' => "Mauvais hash code.")), 401, $this->header));
        $user->setPassword($request->request->get('password', null));
        $user->setPasswordRecoveryHash(null);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($user);
            $em->flush();
            return (new Response('Mot de passe modifié;', 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response($e->getMessage(), 500, $this->header));
        }
    }
    public function socketAction(){
        $websocket = $this->get("nc.topic_service");
        //        $tmp->sendMessage("usertest", "coucouc");
        $pusher = $this->container->get('gos_web_socket.zmq.pusher');
        $pusher->push(['my_data' => 'data'], 'NC_web_socket.topic', ['username' => 'usertest', "channel" => "lol"]);
        return $this->render('NCProBundle:Html:socket.html.twig');
    }
}