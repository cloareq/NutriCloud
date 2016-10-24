<?php

namespace NC\NoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NC\NoteBundle\Entity\Note;

class NoteController extends Controller
{

    public function getNotesAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché n'existe pas."));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché ne fait pas partie de votre liste de patient."));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        return $this->render('NCNoteBundle:Json:NotesList.json.twig', array('notes' => $Patient->getNotes()));
    }

    public function getLastNoteAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché n'existe pas."));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché ne fait pas partie de votre liste de patient."));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();


        if ($Patient->getNotes() == [])
            return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                'desc' => "Le patient n'a pas de note."));
        if ($Patient->getNotes() == null)
            return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                'desc' => "Le patient n'a pas de note."));
        if ($Patient->getNotes()[count($Patient->getNotes()) - 1] == null)
            return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                'desc' => "Le patient n'a pas de note."));
        else
            return $this->render('NCNoteBundle:Json:LastNote.json.twig', array('notes' => $Patient->getNotes()[count($Patient->getNotes()) - 1]));
    }

    public function getWeightAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $repository = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché n'existe pas."));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché ne fait pas partie de votre liste de patient."));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT'))
            $Patient = $this->getUser();
        return $this->render('NCNoteBundle:Json:WeightList.json.twig', array('notes' => $Patient->getNotes()));
    }

    public function newNoteAction($patientId)
    {
        if ($this->get('security.context')->isGranted('ROLE_PRO')) {
            $writer = "pro";
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('NCPatientBundle:Patient');
            $Patient = $repository->findOneById($patientId);
            if ($Patient == NULL)
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché n'existe pas."));
            $Pro = $this->getUser();
            if ($Patient->getPro()->getId() != $Pro->getId())
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => "Le patient recherché ne fait pas partie de votre liste de patient."));
        } else if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            $Patient = $this->getUser();
            $writer = "patient";
        }

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $newNote = new Note();
            $newNote->setDate(new \Datetime());
            $newNote->setNote($request->request->get('text', null));
            $newNote->setWeight($request->request->get('weight', null));
            $newNote->setHeight($request->request->get('height', null));
            $newNote->setWriter($writer);

            $newNote->setPatient($Patient);
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($newNote);
                $em->flush();
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "success",
                    'desc' => "Nouvelle note ajouté avec succès."));
            } catch (\Exception $e) {
                return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                    'desc' => $e->getMessage()));
            }
        } else
            return $this->render('NCProBundle:Json:response.json.twig', array('state' => "failure",
                'desc' => "La requête doit être de type POST."));
    }
}