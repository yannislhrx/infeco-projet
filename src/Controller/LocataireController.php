<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\DepotGarantit;
use App\Entity\EtatLieux;
use App\Entity\Locataire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddLocataireType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping\Entity;




class LocataireController extends AbstractController
{
    /**
     * @Route("/locataire", name="app_locataire")
     */
    public function index(): Response
    {
        return $this->render('locataire/index.html.twig', [
            'controller_name' => 'LocataireController',
        ]);
    }

    public function getLocataires(): Response {

            // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Locataire::class);
        $locataires = $repository->findAll();

        return $this->render('administration/liste-locataire.html.twig', [
            'locataires' => $locataires,
        ]);
    }
    public function create(Request $request)
    {
        $entity = new Locataire();
        $form = $this->createForm(AddLocataireType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            // dump($entity);die;
            return $this->redirectToRoute('liste-locataire');
            // return $this->redirectToRoute('liste-appartement', ['id' => $entity->getId()]);
        }

        return $this->render('administration/ajout-locataire.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    public function deleteLocataire($id)
    {
        // return new Response('Hello '.$id);

        $entityManager = $this->getDoctrine()->getManager();
        $locataire = $entityManager->getRepository(Locataire::class)->find($id);
    
        if (!$locataire) {
            throw $this->createNotFoundException(
                'Aucun locataire trouvé avec l\'ID : '.$id
            );
        }
    
        $entityManager->remove($locataire);
        $entityManager->flush();
    
        return $this->redirectToRoute('liste-locataire');
    }

    public function modifierLocataire($id, Request $request)
    {
        // return new Response('Hello '.$id);

        $entityManager = $this->getDoctrine()->getManager();
        $locataire = $entityManager->getRepository(Locataire::class)->find($id);
    
        if (!$locataire) {
            throw $this->createNotFoundException(
                'Aucun locataire trouvé avec l\'ID : '.$id
            );
        }

        if ($request->get('modifierLocataire') ) {

            if ($request->get('nom')) {
                $nom = $request->get('nom');
             }
             else {
                 $nom = '';
             }
            if ($request->get('prenom')) {
                $prenom = $request->get('prenom');
             }
             else {
                 $prenom = '';
             }
            if ($request->get('mail')) {
                $mail = $request->get('mail');
             }
             else {
                 $mail = '';
             }
            if ($request->get('tel')) {
                $tel = $request->get('tel');
             }
             else {
                 $tel = '';
             }

             $locataire->setNom($nom);
             $locataire->setPrenom($prenom);
             $locataire->setMail($mail);
             $locataire->setTel($tel);
            
             $entityManager->flush();

        }
    
    
        return $this->render('administration/modifier-locataire.html.twig', [
            'locataire' => $locataire,
            
        ]);
    }

    public function ficheLocataire($id): Response {

        // Récupération de tous les produits depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $locataire = $entityManager->getRepository(Locataire::class)->find($id);

        if (!$locataire) {
            throw $this->createNotFoundException(
                'Aucun locataire trouvé avec l\'ID : '.$id
            );
        }

        $appartement = '';

       

        $repository = $entityManager->getRepository(Appartement::class);

        $appartement = $repository->findBy(['idStrLocataires' => $id]);

        if (!$appartement) {
            $appartement = '';
        }

        $repository = $entityManager->getRepository(EtatLieux::class);

        $etatLieux = $repository->findBy(['id_locataire' => $id]);

        if (!$etatLieux) {
            $etatLieux = '';
        }

        $repository = $entityManager->getRepository(DepotGarantit::class);

        $depotGarantie = $repository->findBy(['id_locataire' => $id]);

        if (!$depotGarantie) {
            $depotGarantie = '';
        }






        return $this->render('administration/fiche-locataire.html.twig', [
            'locataire' => $locataire,
            'appartement' => $appartement,
            'etatLieux' => $etatLieux,
            'depotGarantie' => $depotGarantie,
        ]);
    }


   
}
