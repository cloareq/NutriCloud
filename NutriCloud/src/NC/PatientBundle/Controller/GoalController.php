<?php

namespace NC\PatientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class GoalController extends Controller
{
    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
    }

    /**
     * Cette méthode permet d'obtenir les goals.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Goal"="FFCCCC"},
     *  resource=true,
     *  description="/pro/goal/{patientId} -- /patient/goal/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         404="Le patient recherché n'existe pas",
     *         403="Le patient recherché ne fait pas partie de votre liste de patient"
     *  },
     *  requirements={
     *  {"name"="patientid", "dataType"="int", "required"=true, "description"="Id du patient a qui on lit un objectif, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "goal" })
     */
    public function getGoalsAction($patientId)
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
        return $this->render('NCPatientBundle:Json:Goal.json.twig', array('goals' => $Patient->getGoals()));
    }

    /**
     * Cette méthode permet d'ajouter un objectif.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Goal"="FFCCCC"},
     *  resource=true,
     *  description="/pro/goal/create/{patientId} -- /patient/goal/create/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      404="Le patient recherché n'existe pas",
     *      403="Le patient recherché ne fait pas partie de votre liste de patient",
     *      500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="text", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="percentage", "dataType"="int", "required"=true, "description"=""},
     *      {"name"="date", "dataType"="datetime", "required"=true, "description"="Date prévu pour atteindre l'objectif"}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du patient a qui on ajoute un objectif, si vous etes connecté comme patient envoyer me à la place de cet ID"}
     * },
     * views = { "default", "pro", "patient", "goal" })
     */
    public function createGoalAction($patientId)
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
        $newGoal = new Goal();
        $newGoal->setCreatedAt(new \Datetime());
        $newGoal->setPercentage($request->request->get('percentage', null));
        $newGoal->setText($request->request->get('text', null));
        $newGoal->setDate($request->request->get('date', null));
        $newGoal->setPatient($Patient);
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($newGoal);
            $em->flush();
            return (new Response("", 201, $this->header));
        } catch (\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de modifier un goal.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Goal"="FFCCCC"},
     *  resource=true,
     *  description="/pro/goal/update/{id} -- /patient/goal/update/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500= "Internal Error (BDD error)"
     *  },
     *  parameters={
     *      {"name"="text", "dataType"="string", "required"=true, "description"=""},
     *      {"name"="percentage", "dataType"="int", "required"=true, "description"=""},
     *      {"name"="date", "dataType"="datetime", "required"=true, "description"="Date prévu pour atteindre l'objectif"}
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du goal à modifier"}
     * },
     * views = { "default", "pro", "patient", "goal" })
     */
    public function updateGoalAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Goal');
        $GoalToUpdate = $repository->findOneById($id);
        if ($GoalToUpdate == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à modifier n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($GoalToUpdate->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($GoalToUpdate->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $request = $this->get('request');
        $GoalToUpdate->setPercentage($request->request->get('percentage', null));
        $GoalToUpdate->setText($request->request->get('text', null));
        $GoalToUpdate->setDate($request->request->get('date', null));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($GoalToUpdate);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet de supprimer un goal.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "Goal"="FFCCCC"},
     *  resource=true,
     *  description="/pro/goal/delete/{id} -- /delete/goal/delete/{id}",
     *  statusCodes={
     *         200="OK",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist",
     *         500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="id", "dataType"="int", "required"=true, "description"="Id du goal à supprimer"}
     * },
     * views = { "default", "pro", "patient", "goal" })
     */
    public function deleteGoalAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Goal');
        $GoalToDelete = $repository->findOneById($id);
        if ($GoalToDelete == NULL)
            return (new Response(json_encode(array('desc' => "L'élément que vous cherchez à supprimer n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $connectedPro =  $this->getUser();
            if ($GoalToDelete->getPatient()->getPro()->getId() != $connectedPro->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient =  $this->getUser();
            if ($GoalToDelete->getPatient()->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'êtes pas le propriétaire de cet élément.")), 403, $this->header));
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($GoalToDelete);
            $em->flush();
            return (new Response("", 200, $this->header));
        }
        catch(\Exception $e) {
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}
