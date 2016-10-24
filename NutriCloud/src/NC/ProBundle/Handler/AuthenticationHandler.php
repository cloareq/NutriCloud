<?php
namespace NC\ProBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    protected $security;

    public function __construct(SecurityContext $security)
    {
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $result = array('state' => "success", 'desc' => "Erreur lors dans la connexion, mauvais identifiant ou mot de passe.");
        if($this->security->isGranted('ROLE_PRO'))
            $result = array('state' => "success", 'desc' => "Vous êtes maintenant connecté en tant que professionnel.");
        else if($this->security->isGranted('ROLE_PATIENT'))
            $result = array('state' => "success", 'desc' => "Vous êtes maintenant connecté en tant que patient.");
        /*if ($request->isXmlHttpRequest())
            $result = array('state' => "success", 'desc' => "Vous êtes maintenant connecté en tant que professionnel.");
        else
            $result = array('state' => "success", 'desc' => "Vous êtes maintenant connecté en tant que professionnel.");
        */
        return new Response(json_encode($result));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest())
            $result = array('state' => "failure",
                'desc' => "Erreur lors dans la connexion, mauvais identifiant ou mot de passe.");
        else
            $result = array('state' => "failure",
                'desc' => "Erreur lors dans la connexion, mauvais identifiant ou mot de passe.");
        return new Response(json_encode($result));
    }
}