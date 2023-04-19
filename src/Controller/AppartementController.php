<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appartement;
use App\Entity\DepotGarantit;
use App\Entity\EtatLieux;
use App\Entity\Locataire;
use App\Entity\Paiement;
use App\Form\AjoutAppartementType;
use App\Form\AjoutLocataireToAppartementType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping\Entity;




class AppartementController extends AbstractController
{
    /**
     * @Route("/appartement", name="app_appartement")
     */
    public function index(): Response
    {
        return $this->render('appartement/index.html.twig', [
            'controller_name' => 'AppartementController',
        ]);
    }

    public function getAppartements(): Response {

        // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Appartement::class);
        $appartements = $repository->findAll();

        return $this->render('administration/liste-appartement.html.twig', [
            'appartements' => $appartements,
        ]);
    }

    public function pageAjoutAppartement(): Response {

        // Récupération de tous les produits depuis la base de données
       

        return $this->render('administration/ajout-appartement.html.twig', [
            
        ]);
    }

    public function create(Request $request)
    {
        $entity = new Appartement();
        $form = $this->createForm(AjoutAppartementType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            // dump($entity);die;
            return $this->redirectToRoute('liste-appartement');
            // return $this->redirectToRoute('liste-appartement', ['id' => $entity->getId()]);
        }

        return $this->render('administration/ajout-appartement.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function deleteAppartement($id)
    {
        // return new Response('Hello '.$id);

        $entityManager = $this->getDoctrine()->getManager();
        $appartement = $entityManager->getRepository(Appartement::class)->find($id);
    
        if (!$appartement) {
            throw $this->createNotFoundException(
                'Aucun appartement trouvé avec l\'ID : '.$id
            );
        }
    
        $entityManager->remove($appartement);
        $entityManager->flush();
    
        return $this->redirectToRoute('liste-appartement');
    }

    public function deleteLocAppart($id)
    {
        // return new Response('Hello '.$id);

        $entityManager = $this->getDoctrine()->getManager();
        $appartement = $entityManager->getRepository(Appartement::class)->find($id);
    
        if (!$appartement) {
            throw $this->createNotFoundException(
                'Aucun appartement trouvé avec l\'ID : '.$id
            );
        }
    
        $appartement->setIdStrLocataires('');
        $entityManager->flush();
    
        return $this->redirectToRoute('fiche-appartement', ['id' => $id]);

    }

    public function modifierAppartement($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $appartement = $entityManager->getRepository(Appartement::class)->find($id);
    
        if (!$appartement) {
            throw $this->createNotFoundException(
                'Aucun appartement trouvé avec l\'ID : '.$id
            );
        }

        if ($request->get('modifierAppartement') ) {

            if ($request->get('adresse')) {
                $adresse = $request->get('adresse');
             }
             else {
                 $adresse = '';
             }
            if ($request->get('complement')) {
                $complement = $request->get('complement');
             }
             else {
                 $complement = '';
             }
            if ($request->get('ville')) {
                $ville = $request->get('ville');
             }
             else {
                 $ville = '';
             }
            if ($request->get('code_postal')) {
                $code_postal = $request->get('code_postal');
             }
             else {
                 $code_postal = '';
             }

            if ($request->get('loyer')) {
                $loyer = $request->get('loyer');
             }
             else {
                 $loyer = '';
             }
            if ($request->get('charges')) {
                $charges = $request->get('charges');
             }
             else {
                 $charges = '';
             }

             $appartement->setAdresse($adresse);
             $appartement->setComplement($complement);
             $appartement->setVille($ville);
             $appartement->setCodePostal($code_postal);
             $appartement->setLoyer($loyer);
             $appartement->setCharges($charges);
            
             $entityManager->flush();

        }
    
    
        return $this->render('administration/modifier-appartement.html.twig', [
            'appartement' => $appartement,
            
        ]);
    }

    public function ficheAppartement($id,Request $request): Response {
        
        // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Locataire::class);
        $locataires = $repository->findAll();

        // Récupération de tous les produits depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $appartement = $entityManager->getRepository(Appartement::class)->find($id);

        if (!$appartement) {
            throw $this->createNotFoundException(
                'Aucun appartement trouvé avec l\'ID : '.$id
            );
        }

        $form = $this->createForm(AjoutLocataireToAppartementType::class);
        
        $form->handleRequest($request);

        //traitement form ajout locataire a un appartement
        if ($request->get('select') != 0) {

            $appartement->setIdStrLocataires($request->get('select'));
            $entityManager->flush();

         
                    // return new Response('Hello '.$nom . '/' . $prenom);

          }

          $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQueryBuilder()
                ->select('u.idStrLocataires')
                ->from(Appartement::class, 'u')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
            
            $idStrLocataires = $query->getSingleScalarResult();


            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQueryBuilder()
                ->select('l.nom, l.prenom, l.id')
                ->from(Locataire::class, 'l')
                ->where('l.id = :id')
                ->setParameter('id', $idStrLocataires)
                ->getQuery();

                $infosLocataires = $query->getOneOrNullResult();

            // etat des lieux
          
                $repository = $entityManager->getRepository(EtatLieux::class);

                $repository2 = $entityManager->getRepository(DepotGarantit::class);


        
                // Récupérez tous les articles de la base de données
                // $etatDesLieux1 = $repository->findAll();

                $etatDesLieuxE = '';
                $etatDesLieuxS = '';
                $depotGarantie = '';
                $paiement = '';



                if ($infosLocataires) {
                    $etatDesLieuxE = $repository->findBy(['id_appartement' => $id,'id_locataire' => $infosLocataires['id'], 'quand' => 'entree','etat' => 1 ]);
                    $etatDesLieuxS = $repository->findBy(['id_appartement' => $id,'id_locataire' => $infosLocataires['id'] , 'quand' => 'sortie','etat' => 1]);
                    $depotGarantie = $repository2->findBy(['id_appartement' => $id,'id_locataire' => $infosLocataires['id'],'etat' => 1]);

                    $repository = $entityManager->getRepository(Paiement::class);

                    $query = $repository->createQueryBuilder('p')
                        ->where('p.id_locataire = :id')
                        ->setParameter('id', $infosLocataires['id'])
                        ->orderBy('p.date', 'DESC')
                        ->getQuery();
            
                        $paiement = $query->getResult();
            
                    if (!$paiement) {
                        $paiement = '';
                    }

                    
                    if (!$etatDesLieuxE) {
                        $etatDesLieuxE = '';
                    }
                    if (!$etatDesLieuxS) {
                        $etatDesLieuxS = '';
                    }
                    if (!$depotGarantie) {
                        $depotGarantie = '';
                    }

                }
                else {
                    $infosLocataires='';
                }
                

        
                // Récupérez tous les articles de la base de données
                // $etatDesLieux1 = $repository->findAll();

                // Récupérez tous les articles dont le titre contient "Symfony"

                


                


                if ($appartement->getIdStrLocataires()) {
                    $appartement->setEtat('1');

                    $repository3 = $entityManager->getRepository(EtatLieux::class);
                    $repository4 = $entityManager->getRepository(DepotGarantit::class);

                    if ($infosLocataires) {
                        $etatLieux = $repository3->findBy(['id_appartement' => $id,'id_locataire' => $infosLocataires['id'], 'etat' => '1' ]);
                        $depot = $repository4->findBy(['id_appartement' => $id,'id_locataire' => $infosLocataires['id'], 'etat' => '1' ]);
                        $count = count($etatLieux);
                        $count2 = count($depot);
                        // return new Response('Hello '.$count . 'et ' . $count2);
    
    
                        if ($appartement->getIdStrLocataires() && $count>0 && $count2) {
                            $appartement->setEtat('2');
                        }
                        $entityManager->flush();

    
                    }


                }
                else {
                    $appartement->setEtat('0');
                    $entityManager->flush();
                }


              
               

              


                



                // $appartement->getIdStrLocataires();
                // $entityManager->flush();
         
   

        return $this->render('administration/fiche-appartement.html.twig', [
            'appartement' => $appartement,
            'locataires' => $locataires,
            'form' => $form->createView(),
            'infosLocataires' => $infosLocataires,
            'etatDesLieuxE' => $etatDesLieuxE,
            'etatDesLieuxS' => $etatDesLieuxS,
            'depotGarantie' => $depotGarantie,
            'paiement'      => $paiement,
        ]);
    }



    public function ajoutEtat($id_locataire,$id_appartement, Request $request): Response {

        if ($request->get('valider_etat') ) {
            
            if ($request->get('remarque')) {
               $remarque = $request->get('remarque');
            }
            else {
                $remarque = '';
            }

            if ($request->get('entree')) {
               $entree = $request->get('entree');
            }
            else {
                $entree = '';
            }
            if ($request->get('date_etat')) {
               
               $date_etat =new  \DateTime( $request->get('date_etat'));
            }
            else {
                $date_etat = '';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $etatDesLieux = new EtatLieux();


            // Définir les valeurs des champs
            $etatDesLieux->setDate($date_etat);
            $etatDesLieux->setRemarque($remarque);
            $etatDesLieux->setQuand($entree);
            $etatDesLieux->setIdLocataire($id_locataire);
            $etatDesLieux->setIdAppartement($id_appartement);
            $etatDesLieux->setEtat('1');

            $entityManager->persist($etatDesLieux);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

          
        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);


    }

    public function ajoutEtatSortie($id_locataire,$id_appartement, Request $request): Response {

        if ($request->get('valider_etatS') ) {
            
            if ($request->get('remarqueS')) {
               $remarqueS = $request->get('remarqueS');
            }
            else {
                $remarqueS = '';
            }

            if ($request->get('sortie')) {
               $sortie = $request->get('sortie');
            }
            else {
                $sortie = '';
            }
            if ($request->get('date_etatS')) {
               
               $date_etatS =new  \DateTime( $request->get('date_etatS'));
            }
            else {
                $date_etatS = '';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $etatDesLieux = new EtatLieux();


            // Définir les valeurs des champs
            $etatDesLieux->setDate($date_etatS);
            $etatDesLieux->setRemarque($remarqueS);
            $etatDesLieux->setQuand($sortie);
            $etatDesLieux->setIdLocataire($id_locataire);
            $etatDesLieux->setIdAppartement($id_appartement);
            $etatDesLieux->setEtat('1');

            $entityManager->persist($etatDesLieux);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
    }

    public function modifierEtat($id_locataire,$id_appartement,$id_etat, Request $request): Response {

        if ($request->get('modifier_etat') ) {
            
            if ($request->get('remarque')) {
               $remarque = $request->get('remarque');
            }
            else {
                $remarque = '';
            }

            if ($request->get('entree')) {
               $entree = $request->get('entree');
            }
            else {
                $entree = '';
            }
            if ($request->get('date_etat')) {
               
               $date_etat =new  \DateTime( $request->get('date_etat'));
            }
            else {
                $date_etat = '';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $etatDesLieux = $entityManager->getRepository(EtatLieux::class)->find($id_etat);


            // Définir les valeurs des champs
            $etatDesLieux->setDate($date_etat);
            $etatDesLieux->setRemarque($remarque);
            $etatDesLieux->setQuand($entree);
            $etatDesLieux->setEtat('1');

            $entityManager->persist($etatDesLieux);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        if ($request->get('supprimer_etat') ) {
            
          

            $entityManager = $this->getDoctrine()->getManager();
            $etatDesLieux = $entityManager->getRepository(EtatLieux::class)->find($id_etat);

          

            $entityManager->remove($etatDesLieux);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
    }


    public function modifierEtatSortie($id_locataire,$id_appartement,$id_etat, Request $request): Response {

        if ($request->get('modifier_etatS') ) {
            
            if ($request->get('remarqueS')) {
                $remarqueS = $request->get('remarqueS');
             }
             else {
                 $remarqueS = '';
             }
 
             if ($request->get('sortie')) {
                $sortie = $request->get('sortie');
             }
             else {
                 $sortie = '';
             }
             if ($request->get('date_etatS')) {
                
                $date_etatS =new  \DateTime( $request->get('date_etatS'));
             }
             else {
                 $date_etatS = '';
             }
 
             $entityManager = $this->getDoctrine()->getManager();
             $etatDesLieux = $entityManager->getRepository(EtatLieux::class)->find($id_etat);
 
 
             // Définir les valeurs des champs
             $etatDesLieux->setDate($date_etatS);
             $etatDesLieux->setRemarque($remarqueS);
             $etatDesLieux->setQuand($sortie);
          
 
             $entityManager->persist($etatDesLieux);
             $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        if ($request->get('supprimer_etat') ) {
            
          

            $entityManager = $this->getDoctrine()->getManager();
            $etatDesLieux = $entityManager->getRepository(EtatLieux::class)->find($id_etat);

          

            $entityManager->remove($etatDesLieux);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
    }





    public function ajoutDepotGarantie($id_locataire,$id_appartement, Request $request): Response {

        if ($request->get('valider_depot') ) {
            
            if ($request->get('somme')) {
               $somme = $request->get('somme');
            }
            else {
                $somme = '';
            }

           

            $entityManager = $this->getDoctrine()->getManager();
            $depotGarantie = new DepotGarantit();


            // Définir les valeurs des champs
            $depotGarantie->setSomme($somme);
            
            $depotGarantie->setIdLocataire($id_locataire);
            $depotGarantie->setIdAppartement($id_appartement);
            $depotGarantie->setEtat('1');


            $entityManager->persist($depotGarantie);
            $entityManager->flush();

            
            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
    }


    public function modifierDepotGarantie($id_locataire,$id_appartement,$id_depot, Request $request): Response {

        if ($request->get('modifier_depot') ) {
            
            if ($request->get('somme')) {
               $somme = $request->get('somme');
            }
            else {
                $somme = '';
            }

           

            $entityManager = $this->getDoctrine()->getManager();
            $depotGarantie = $entityManager->getRepository(DepotGarantit::class)->find($id_depot);



            // Définir les valeurs des champs
            $depotGarantie->setSomme($somme);


            $entityManager->persist($depotGarantie);
            $entityManager->flush();

            
            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        if ($request->get('supprimer_depot') ) {
            
          

            $entityManager = $this->getDoctrine()->getManager();
            $depotGarantie = $entityManager->getRepository(DepotGarantit::class)->find($id_depot);

          

            $entityManager->remove($depotGarantie);
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
    }



    public function reinitAppartement($id_appartement) {

        $etatSortie = '';

        $etatEntree = '';

        $depot = '';

        // set etat appartement 0

        $entityManager = $this->getDoctrine()->getManager();
        $appartement = $entityManager->getRepository(Appartement::class)->find($id_appartement);
    



        $id_locataire = $appartement->getIdStrLocataires();
        $loyer = $appartement->getLoyer();







        // set id strLoc appartement -> null

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQueryBuilder()
            ->select('d.id, d.etat')
            ->from(DepotGarantit::class, 'd')
            ->where('d.id_locataire = :id_locataire and d.etat = 1 and d.id_appartement = :id_appartement')
            ->setParameter('id_locataire', $id_locataire)
            ->setParameter('id_appartement', $id_appartement)
            ->getQuery();
        
        $depotArray = $query->getOneOrNullResult();

      

        if ($depotArray) {
            $entityManager = $this->getDoctrine()->getManager();
            $depot = $entityManager->getRepository(DepotGarantit::class)->find($depotArray['id']);
        }

        // depot avec id appart -> 0. depot garantie


        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQueryBuilder()
            ->select('e.id, e.etat, e.quand')
            ->from(EtatLieux::class, 'e')
            ->where('e.id_locataire = :id_locataire and e.etat = 1 and e.id_appartement = :id_appartement and e.quand = :quand')
            ->setParameter('id_locataire', $id_locataire)
            ->setParameter('id_appartement', $id_appartement)
            ->setParameter('quand', 'entree')
            ->getQuery();
        
        $etatEntreeArray = $query->getOneOrNullResult();
      

        
        if ($etatEntreeArray) {
            $entityManager = $this->getDoctrine()->getManager();
            $etatEntree = $entityManager->getRepository(EtatLieux::class)->find($etatEntreeArray['id']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQueryBuilder()
            ->select('e.id, e.etat, e.quand')
            ->from(EtatLieux::class, 'e')
            ->where('e.id_locataire = :id_locataire and e.etat = 1 and e.id_appartement = :id_appartement and e.quand = :quand')
            ->setParameter('id_locataire', $id_locataire)
            ->setParameter('id_appartement', $id_appartement)
            ->setParameter('quand', 'sortie')
            ->getQuery();
        
        $etatSortieArray = $query->getOneOrNullResult();
     
        if ($etatSortieArray) {
            $entityManager = $this->getDoctrine()->getManager();
            $etatSortie = $entityManager->getRepository(EtatLieux::class)->find($etatSortieArray['id']);
        }


        

        // if (!$etatSortie) {
        //     $etatSortie = '';
        // }
        // if (!$etatEntree) {
        //     $etatEntree = '';
        // }
        // if ($etatSortieArray) {
        //     $depot = '';
        // }


        if ($appartement->getEtat() == 0 ) {
            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        elseif ($appartement->getEtat() == 1) {

            if ($etatEntree) {
                $entityManager->remove($etatEntree);
                $entityManager->flush();
            }
            if ($etatSortie) {
                $entityManager->remove($etatSortie);
                $entityManager->flush();
            }
            if ($depot) {
                $entityManager->remove($depot);
                $entityManager->flush();
            }

            
            $appartement->setIdStrLocataires('') ;
            $entityManager->flush();



            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        }
        else {
            if ($etatEntree) {
                $etatEntree->setEtat(0);
                $entityManager->flush();
            }
            if ($etatSortie) {
                $etatSortie->setEtat(0);
                $entityManager->flush();
            }
            if ($depot) {
                $depot->setEtat(0);
                $entityManager->flush();
            }
            $appartement->setIdStrLocataires('') ;
            $entityManager->flush();

            return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);
        }

        // etat avec id appart -> 0. etat 



        

        return $this->redirectToRoute('fiche-appartement', ['id' => $id_appartement]);

        







    }


    

    
}
