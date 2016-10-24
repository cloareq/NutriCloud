<?php

namespace NC\PlanBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\PlanBundle\Entity\Aliment;
use Symfony\Component\HttpFoundation\Response;
use NC\PlanBundle\Entity\PlanAliment;
use NC\PlanBundle\Entity\PlanDay;
use NC\PatientBundle\Entity\Patient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PlanModelController extends Controller {

    function __construct() {
        $this->header = array('Content-Type' => 'application/json');
        $this->week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    }

    /**
     * Cette méthode permet à un pro de récupérer le PA model d'un de ses patients.\n AInsi qu'à un patient de récupérer son PA model.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient"="#0000FF", "PlanAlimentaire"="#218E4C", "Model"="#00FFB8"},
     *  resource=true,
     *  description="/pro/plan/model/{patientId} -- /patient/plan/model/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "model"}
     * )
     */
    public function getPlanModelAction($patientId){
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
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $Plans = $Patient->getPlans();
        $plan_week = new ArrayCollection();
        foreach ($Plans as $tmp_plan) {
            if (in_array($tmp_plan->getDate(), $this->week))
                $plan_week[] = $tmp_plan;
        }
        return $this->render('NCPlanBundle:Json:PlanWeek.json.twig', array("plans" => $plan_week));
    }

    /**
     * Cette méthode permet à un pro ou à un patient de télécharger son PA au format PDF.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "PlanAlimentaire"="#218E4C", "Model"="#00FFB8"},
     *  resource=true,
     *  description="/pro/plan/model/pdf/{patientId} -- /patient/plan/model/pdf/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "model"}
     * )
     */
    public function getPlanModelPdfAction($patientId){
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
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));

        $Plans = $Patient->getPlans();
        $plan_week = new ArrayCollection();
        foreach ($Plans as $tmp_plan) {
            if (in_array($tmp_plan->getDate(), $this->week))
                $plan_week[] = $tmp_plan;
        }
        $html = $this->renderView('NCPlanBundle:Json:PlanWeek.json.twig', array("plans" => $plan_week));
        return new Response(
            $this->get('\'knp_snappy.pdf\'')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="plan.pdf"'
            )
        );
    }

    /**
     * Cette méthode permet à un pro de créer et modifier le plan alimentaire d'un de ces patients.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "PlanAlimentaire"="#218E4C", "Model"="#00FFB8"},
     *  resource=true,
     *  description="/pro/plan/update/{patientId}",
     *  statusCodes={
     *      201="Data Created",
     *      401="Unauthorized: You are not connected",
     *      403="Forbidden: You are connected but you are not allowed to access to this data",
     *      404="Not Found: Data does not exist.",
     *      500= "Internal Error (BDD error)"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  parameters={
     *      {"name"="plan", "dataType"="string", "required"=true, "description"="Plan doit etre complet (tous les jours), c'est le plan json dans une string si ça marche pas tester d'encode en json"}
     * },
     *  views = {"default", "pro", "planalimentaire", "model"}
     * )
     */
    public function updatePlanModelAction($patientId)
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
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté en tant que pro.")), 401, $this->header));
        $request = $this->get('request');
        $request_plan = json_decode($request->request->get('plan', null));

        $Plans = $Patient->getPlans();
        // defile sur les plans jour de l'utilisateur
        foreach ($Plans as $planDay) {
            // check si c'est un plan jour model (cad si la date est un jour de la semain (lundi, mardi...))
            if (in_array($planDay->getDate(), $this->week))
                $dateTmp = $planDay->getDate();
                $planDay->removeAllAliments();
                // loop sur les aliments de la requetes
                foreach ($request_plan->{$dateTmp} as $a){
                    $new_aliment = new PlanAliment(str_replace('"', "", json_encode($a->{"aliment"})),
                        str_replace('"', "", json_encode($a->{"hour"})),
                        str_replace('"', "", json_encode($a->{"quantity"})),
                        "false", "true");
                        $planDay->addAliments($new_aliment);
                }
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($Patient);
            $em->flush();
            return (new Response(json_encode(array('desc' => "")), 201, $this->header));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}