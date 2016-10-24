<?php

namespace NC\PlanBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\PlanBundle\Entity\Aliment;
use Symfony\Component\HttpFoundation\Response;
use NC\PlanBundle\Entity\PlanAliment;
use NC\PlanBundle\Entity\PlanDay;
use NC\PatientBundle\Entity\Patient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

class PlanTraceController extends Controller
{

    function __construct()
    {
        $this->header = array('Content-Type' => 'application/json');
        $this->week = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    }

    /**
     * Cette méthode permet à un pro de récupérer le PA de la journée actuelle d'un de ses patients.\n Ainsi qu'à un patient de récupérer son PA de le journée actuelle.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "PlanAlimentaire"="#218E4C", "Suivi"="#6F5200"},
     *  resource=true,
     *  description="/pro/plan/daily/{patientId} -- /patient/plan/daily/{patientId}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500="BDD error"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "suivi"}
     * )
     */
    public function getTodayPlanAction($patientId)
    {
        $em = $this->getDoctrine()->getManager();

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
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')){
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $day_of_week = date("l");
        $today = date("Y-m-d");

        $plans = $Patient->getPlans();

        foreach ($plans as $plan){
            if ($plan->getDate() == $today)
                return $this->render('NCPlanBundle:Json:PlanDay.json.twig', array("day" => $plan));
        }
        // IF NEW DAY
        foreach ($plans as $plan) {
            if ($plan->getDate() == ucfirst($day_of_week))
                break;
        }
        $tmp = new PlanDay($today, $Patient);
        $tmp->setAliments($plan->getAliments());
        $Patient->addPlans($tmp);
        try {
            $em->persist($Patient);
            $em->flush();
            return $this->render('NCPlanBundle:Json:PlanDay.json.twig', array("day" => $tmp));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de récupérer le PA d'une journée donnée d'un de ses patients.\n Ainsi qu'à un patient de récupérer son PA d'une journée donnée.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient" = "#0000FF", "PlanAlimentaire"="#218E4C", "Suivi"="#6F5200"},
     *  resource=true,
     *  description="/pro/plan/daily/{patientId}/{date} -- /patient/plan/daily/{patientId}/{date}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"},
     *  {"name"="date", "dataType"="string", "required"=true, "description"="date du plan recherché format YYYY-MM-DD, ex: 2016-03-13"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "suivi"}
     * )
     */
    public function getPlanDateAction($patientId, $date)
    {
        $em = $this->getDoctrine()->getManager();
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
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')){
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));


        $plans = $Patient->getPlans();
        foreach ($plans as $plan){
            if ($plan->getDate() == $date)
                return $this->render('NCPlanBundle:Json:PlanDay.json.twig', array("day" => $plan));
        }
        $time = strtotime($date);
        $day_of_week = $this->week[date('w',$time)];

        // si c'est un jour passé, mais que j'ai jamais reccupérer le model
        foreach ($plans as $plan) {
            if ($plan->getDate() == ucfirst($day_of_week))
                break;
        }
        $tmp = new PlanDay($date, $Patient);
        $tmp->setAliments($plan->getAliments());
        $tmp->setExtraAliment(0);
        $tmp->setPercentage(0);
        $Patient->addPlans($tmp);
        try {
            $em->persist($Patient);
            $em->flush();
            return $this->render('NCPlanBundle:Json:PlanDay.json.twig', array("day" => $tmp));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }

    /**
     * Cette méthode permet à un pro de récupérer les PA entre 2 dates d'un de ses patients.\n Ainsi qu'à un patient de récupérer son PA entre 2 dates.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient"="#0000FF", "PlanAlimentaire"="#218E4C", "Suivi"="#6F5200"},
     *  resource=true,
     *  description="/pro/plan/daily/{patientId}/{from}/{to} -- /patient/plan/daily/{patientId}/{from}/{to}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist."
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"},
     *  {"name"="from", "dataType"="string", "required"=true, "description"="date de debut de recherche, format YYYY-MM-DD, ex: 2016-03-13"},
     *  {"name"="to", "dataType"="string", "required"=true, "description"="date de fin de recherche, format YYYY-MM-DD, ex: 2016-03-13"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "suivi"}
     * )
     */
    public function getPlanBetweenDateAction($patientId, $from, $to)
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
        }
        else if ($this->get('security.context')->isGranted('ROLE_PATIENT')){
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        }
        else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $plans = $Patient->getPlans();
        $tmp = new ArrayCollection();
        foreach ($plans as $plan){
            if ($plan->getDate() >= $from && $plan->getDate() <= $to)
                $tmp[] = $plan;
        }
        return $this->render('NCPlanBundle:Json:PlanWeek.json.twig', array("plans" => $tmp));
    }

    public function percentageCompletionCalculation($aliments){
        /*UPDATE pourcentage de la journée d'hier*/

        $nb_total_aliment_model = 0;
        $nb_eaten_aliment = 0;

        foreach ($aliments as $aliment) {
            if ($aliment->getIsModel() == "true") {
                $nb_total_aliment_model += 1;
                if ($aliment->getIsEaten() == "true")
                    $nb_eaten_aliment += 1;
            }
        }
        if ($nb_total_aliment_model == 0 || $nb_eaten_aliment == 0)
            return (0);
        return (round($nb_eaten_aliment * 100 / $nb_total_aliment_model));
    }

    /**
     * Cette méthode permet de completer PA de la journée actuelle ou passé.
     * @ApiDoc(
     *  tags={"Pro"="#FF0000", "Patient"="#0000FF", "PlanAlimentaire"="#218E4C", "Suivi"="#6F5200"},
     *  resource=true,
     *  description="/pro/plan/daily/update/{patientId}/{date} -- /patient/plan/daily/update/{patientId}/{date}",
     *  statusCodes={
     *         200="OK",
     *         401="Unauthorized: You are not connected",
     *         403="Forbidden: You are connected but you are not allowed to access to this data",
     *         404="Not Found: Data does not exist.",
     *         500="BDD error"
     *  },
     *  requirements={
     *  {"name"="patientId", "dataType"="int", "required"=true, "description"="Id of the concerned patient"}
     * },
     *  parameters={
     *      {"name"="plan", "dataType"="string", "required"=true, "description"="Plan doit etre d'une seul journée, c'est le plan json dans une string si ça marche pas tester d'encode en json"}
     * },
     *  views = {"default", "pro", "patient", "planalimentaire", "suivi"}
     * )
     */
    public function updateDailyPlanAction($patientId, $date)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('NCPatientBundle:Patient');
        $Patient = $repository->findOneById($patientId);
        if ($Patient == NULL)
            return (new Response(json_encode(array('desc' => "Le patient recherché n'existe pas.")), 404, $this->header));
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return (new Response(json_encode(array('desc' => "Le patient recherché ne fait pas partie de votre liste de patient.")), 403, $this->header));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $connectedPatient = $this->getUser();
            if ($Patient->getId() != $connectedPatient->getId())
                return (new Response(json_encode(array('desc' => "Vous n'avez pas accès à ces données.")), 403, $this->header));
        } else
            return (new Response(json_encode(array('desc' => "Vous n'êtes pas connecté.")), 401, $this->header));
        $plans = $Patient->getPlans();
        foreach ($plans as $plan) {
            if ($plan->getDate() == $date)
                break;
        }
        $request = $this->get('request');
        $current_plan_model = json_decode($request->request->get('plan', null));
        $plan->removeAllAliments();
        $aliments_tmp = new ArrayCollection();
        $extra_aliment = 0;
        foreach ($current_plan_model->{$date} as $a){
            $ali = new PlanAliment(str_replace('"', "", json_encode($a->{"aliment"})),
                str_replace('"', "", json_encode($a->{"hour"})),
                str_replace('"', "", json_encode($a->{"quantity"})),
                str_replace('"', "", json_encode($a->{"is_eaten"})),
                str_replace('"', "", json_encode($a->{"is_model"}))
                );
            $aliments_tmp[] = $ali;
            if ($ali->getIsModel() == "false")
                $extra_aliment += 1;
        }
        $plan->setAliments($aliments_tmp);
        $plan->setExtraAliment($extra_aliment);
        $plan->setPercentage($this->percentageCompletionCalculation($plan->getAliments()));
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($plan);
            $em->flush();
            //return (new Response(json_encode(array('desc' => "")), 200, $this->header));
            return ($this->getPlanDateAction($patientId, $date));
        }
        catch(\Exception $e){
            return (new Response(json_encode(array('desc' => $e->getMessage())), 500, $this->header));
        }
    }
}