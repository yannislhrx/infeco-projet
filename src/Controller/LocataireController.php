<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\DepotGarantit;
use App\Entity\EtatLieux;
use App\Entity\Locataire;
use App\Entity\Paiement;
use App\Entity\Soldes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddLocataireType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LocataireController extends AbstractController
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    
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

        
         // Récupération du jeton de sécurité courant
         $token = $this->tokenStorage->getToken();

         // Vérification si l'utilisateur est authentifié
         if (!$token || !$token->isAuthenticated()) {
             throw new \LogicException('L\'utilisateur n\'est pas authentifié.');
         }
 
         // Récupération de l'utilisateur courant
         $user = $token->getUser();



            // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Locataire::class);
        // $locataires = $repository->findAll();
        $locataires = $repository->findBy(['id_agence' => $user->getId()]);

        return $this->render('administration/liste-locataire.html.twig', [
            'locataires' => $locataires,
            'user' => $user,

        ]);
    }
    public function create(Request $request)
    {

         // Récupération du jeton de sécurité courant
         $token = $this->tokenStorage->getToken();

         // Vérification si l'utilisateur est authentifié
         if (!$token || !$token->isAuthenticated()) {
             throw new \LogicException('L\'utilisateur n\'est pas authentifié.');
         }
 
         // Récupération de l'utilisateur courant
         $user = $token->getUser();


        $entity = new Locataire();
        $form = $this->createForm(AddLocataireType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entity->setidAgence($user->getId());
            $entityManager->persist($entity);
            $entityManager->flush();

            // dump($entity);die;
            return $this->redirectToRoute('liste-locataire');
            // return $this->redirectToRoute('liste-appartement', ['id' => $entity->getId()]);
        }

        return $this->render('administration/ajout-locataire.html.twig', [
            'form' => $form->createView(),
            'user' => $user,

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

        
         // Récupération du jeton de sécurité courant
         $token = $this->tokenStorage->getToken();

         // Vérification si l'utilisateur est authentifié
         if (!$token || !$token->isAuthenticated()) {
             throw new \LogicException('L\'utilisateur n\'est pas authentifié.');
         }
 
         // Récupération de l'utilisateur courant
         $user = $token->getUser();
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
            'user' => $user,

            
        ]);
    }

    public function ficheLocataire($id): Response {


        
         // Récupération du jeton de sécurité courant
         $token = $this->tokenStorage->getToken();

         // Vérification si l'utilisateur est authentifié
         if (!$token || !$token->isAuthenticated()) {
             throw new \LogicException('L\'utilisateur n\'est pas authentifié.');
         }
 
         // Récupération de l'utilisateur courant
         $user = $token->getUser();
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

        $appartement = $repository->findBy(['id_str_locataires' => $id]);

        if (!$appartement) {
            $appartement = '';
        }
        $appartementvalide = $repository->findBy(['id_str_locataires' => $id , 'etat' => 2]);

        if (!$appartementvalide) {
            $appartementvalide = '';
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




   

        $repository = $entityManager->getRepository(Paiement::class);

        // $paiement = $repository->findBy(['id_locataire' => $id,'id_appartement' => $infosAppart['id']]);

        $query = $repository->createQueryBuilder('p')
            ->where('p.id_locataire = :id')
            ->setParameter('id', $id)
            ->orderBy('p.date', 'DESC')
            ->getQuery();

            $paiement = $query->getResult();

        if (!$paiement) {
            $paiement = '';
        }

        $message = '';

        if ($appartementvalide) {
           $i = 0 ; 
           $soldes = '';
           $ajout = false;

            foreach ($appartementvalide as $app) {
                $i += 1 ; 
                $repository = $entityManager->getRepository(Soldes::class);
                $soldesbis = $repository->findBy(['id_locataire' => $id]);
                
                if ($soldesbis) {
                    // return new Response('je suis dans soldebis');

                    foreach ($soldesbis as $sol ) {
                        $message = $message .  '<br> sol id app == ' . $sol->getIdAppartement() . ' app id ==  '. $app->getId();
                        if ($sol->getIdAppartement() == $app->getId()) {
                            //  return new Response('je suis dans id appart == ap    ' . $i);
                            break;
                            $message = $message . ' <br>  oui   <br>  ';

                        }
                        else {
                            $message = $message . ' <br>  non  <br> ';

                            $ajout = true;

                            // $soldes = new Soldes();
                            // $entityManager = $this->getDoctrine()->getManager();
                            // $soldes->setIdLocataire($app->getIdStrLocataires());
                            // $soldes->setIdAppartement($app->getId());
            
                            
                            // foreach ($etatLieux as $ett ) {
                            //    if ($ett->getIdLocataire() == $app->getIdStrLocataires() && $ett->getIdAppartement() == $app->getId() && $ett->getQuand() == 'entree') {
                            //         $soldes->setDateEntree($ett->getDate());
            
                            //         $date1 = new DateTime('2023-06-29');
                            //         $date2 = $ett->getDate();
            
                            //         $interval = $date1->diff($date2);
            
                            //         $nb_mois = $interval->format('%m');
            
                            //         $somme = 0;
            
                            //         $somme += $app->getLoyer() * $nb_mois;
            
            
            
                            //         $soldes->setSomme($somme);
            
                            //    }
                            // }
                            
                           

                        }
                        if ($ajout == true) {
                            $ajout = false;
                            break;
                         }
                    }
                    

                   
                   
                    
                }
                else {
                    $soldes = new Soldes();
                    $entityManager = $this->getDoctrine()->getManager();
                    $soldes->setIdLocataire($app->getIdStrLocataires());
                    $soldes->setIdAppartement($app->getId());
    
                    
                    foreach ($etatLieux as $ett ) {
                       if ($ett->getIdLocataire() == $app->getIdStrLocataires() && $ett->getIdAppartement() == $app->getId() && $ett->getQuand() == 'entree') {
                            $soldes->setDateEntree($ett->getDate());
    
                            $date1 = new DateTime('2023-06-29');
                            $date2 = $ett->getDate();
    
                            $interval = $date1->diff($date2);
    
                            $nb_mois = $interval->format('%m');
    
                            $somme = 0;
    
                            $somme += $app->getLoyer() * $nb_mois;
    
    
    
                            $soldes->setSomme($somme);
    
                       }
                    }
                    

                    // return new Response('je suis ne suis pas dans solde bis');

                }

           
                
                if ($soldes) {
                    $entityManager->persist($soldes);
                    $entityManager->flush();
                }

               
                    $ajout = false; 
                
            }
            // return new Response('i = ' . $i);
            // return new Response( '' .$message);

        }

        $repository = $entityManager->getRepository(Soldes::class);

        $soldes = $repository->findBy(['id_locataire' => $id]);


        if (!$soldes) {
            $soldes = '';
        }







        return $this->render('administration/fiche-locataire.html.twig', [
            'locataire' => $locataire,
            'appartement' => $appartement,
            'appartementvalide' => $appartementvalide,
            'etatLieux' => $etatLieux,
            'depotGarantie' => $depotGarantie,
            'paiement' => $paiement,
            'user' => $user,
            'soldes' => $soldes,

        ]);
    }

    public function ajoutPaiement($id_locataire,Request $request): Response {

        if ($request->get('valider_paiement') ) {
            
            if ($request->get('somme')) {
               $somme = $request->get('somme');
            }
            else {
                $somme = '';
            }
            if ($request->get('date')) {
               $date = new  \DateTime( $request->get('date'));
            }
            else {
                $date = '';
            }
            if ($request->get('emmeteur')) {
               $emmeteur = $request->get('emmeteur');
            }
            else {
                $emmeteur = '';
            }
            if ($request->get('appartement')) {
               $appartement = $request->get('appartement');
            }
            else {
                $appartement = '';
            }

           

            $entityManager = $this->getDoctrine()->getManager();
            $paiement = new Paiement();


            // Définir les valeurs des champs
            $paiement->setSomme($somme);
            $paiement->setDate($date);
            $paiement->setEmmeteur($emmeteur);
            $paiement->setIdAppartement($appartement);
            $paiement->setIdLocataire($id_locataire);
            
           


            $entityManager->persist($paiement);
            $entityManager->flush();

          
            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQueryBuilder()
                ->select('s.id, s.somme, s.id_locataire, s.id_appartement')
                ->from(Soldes::class, 's')
                ->where('s.id_locataire = :id and s.id_appartement = :id_app')
                ->setParameter('id', $id_locataire)
                ->setParameter('id_app', $appartement)
                ->getQuery();

                $soldesid = $query->getOneOrNullResult();

                $entityManager = $this->getDoctrine()->getManager();
                $soldes = $entityManager->getRepository(Soldes::class)->find($soldesid['id']);
            
            // Vérification que l'entité existe
            if (!$soldes) {
                throw $this->createNotFoundException('Aucune entité trouvée pour cet id : ');
            }
            
            // Mise à jour de l'entité
            $val = $soldes->getSomme();
            $soldes->setSomme($val-$paiement->getSomme());
            
            // Enregistrement des modifications dans la base de données
            $entityManager->flush();
    
            
            return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);

        }
        return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);
    }
    public function modifierPaiement($id_locataire, $id_paiement, Request $request): Response {

        if ($request->get('modifier_paiement') ) {
            
            if ($request->get('somme')) {
               $somme = $request->get('somme');
            }
            else {
                $somme = '';
            }
            if ($request->get('date')) {
               $date = new  \DateTime( $request->get('date'));
            }
            else {
                $date = '';
            }
            if ($request->get('emmeteur')) {
               $emmeteur = $request->get('emmeteur');
            }
            else {
                $emmeteur = '';
            }
            if ($request->get('appartement')) {
               $appartement = $request->get('appartement');
            }
            else {
                $appartement = '';
            }

           

            $entityManager = $this->getDoctrine()->getManager();
            $paiement = $entityManager->getRepository(Paiement::class)->find($id_paiement);



            $oldSomme = $paiement->getSomme();


            // Définir les valeurs des champs
            $paiement->setSomme($somme);
            $paiement->setDate($date);
            $paiement->setEmmeteur($emmeteur);
            $paiement->setIdAppartement($appartement);
            $paiement->setIdLocataire($id_locataire);


            $newSomme = $paiement->getSomme();
            
           


            $entityManager->persist($paiement);
            $entityManager->flush();


            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQueryBuilder()
                ->select('s.id, s.somme, s.id_locataire, s.id_appartement')
                ->from(Soldes::class, 's')
                ->where('s.id_locataire = :id and s.id_appartement = :id_app')
                ->setParameter('id', $id_locataire)
                ->setParameter('id_app', $paiement->getIdAppartement())
                ->getQuery();

                $soldesid = $query->getOneOrNullResult();

                $entityManager = $this->getDoctrine()->getManager();
                $soldes = $entityManager->getRepository(Soldes::class)->find($soldesid['id']);
            
            // Vérification que l'entité existe
            if (!$soldes) {
                throw $this->createNotFoundException('Aucune entité trouvée pour cet id : ');
            }
            
            // Mise à jour de l'entité
            $val = $soldes->getSomme();
            $soldes->setSomme($val+$oldSomme);

            $val2 = $soldes->getSomme();
            $soldes->setSomme($val2 - $newSomme);
            
            // Enregistrement des modifications dans la base de données
            $entityManager->flush();




            
            return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);

        }

        
        return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);
    }

    public function deletePaiement($id_locataire, $id_paiement): Response {

       
            
          

            $entityManager = $this->getDoctrine()->getManager();
            $paiement = $entityManager->getRepository(Paiement::class)->find($id_paiement);

          $remSomme = $paiement->getSomme();

            $entityManager->remove($paiement);
            $entityManager->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQueryBuilder()
                ->select('s.id, s.somme, s.id_locataire, s.id_appartement')
                ->from(Soldes::class, 's')
                ->where('s.id_locataire = :id and s.id_appartement = :id_app')
                ->setParameter('id', $id_locataire)
                ->setParameter('id_app', $paiement->getIdAppartement())
                ->getQuery();

                $soldesid = $query->getOneOrNullResult();

                $entityManager = $this->getDoctrine()->getManager();
                $soldes = $entityManager->getRepository(Soldes::class)->find($soldesid['id']);
            
            // Vérification que l'entité existe
            if (!$soldes) {
                throw $this->createNotFoundException('Aucune entité trouvée pour cet id : ');
            }
            
            // Mise à jour de l'entité
            $val = $soldes->getSomme();
            $soldes->setSomme($val+$remSomme);
            
            // Enregistrement des modifications dans la base de données
            $entityManager->flush();





        
        return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);
    }


    public function quittanceLoyer($id_paiement, $id_locataire, MailerInterface $mailer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQueryBuilder()
            ->select('l.nom, l.prenom, l.id, l.mail, l.tel, l.id_agence')
            ->from(Locataire::class, 'l')
            ->where('l.id = :id')
            ->setParameter('id', $id_locataire)
            ->getQuery();

            $infosLocataires = $query->getOneOrNullResult();

            if ($infosLocataires) {
                $entityManager = $this->getDoctrine()->getManager();
                $query = $entityManager->createQueryBuilder()
                ->select('p.somme, p.emmeteur, p.id, p.date, p.id_locataire, p.id_appartement')
                ->from(Paiement::class, 'p')
                ->where('p.id = :id')
                ->setParameter('id', $id_paiement)
                ->getQuery();

                $infosPaiement = $query->getOneOrNullResult();

                if ($infosPaiement) {

                   

                    $englishMonths = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');


                    $date = $infosPaiement['date'];
                    $month = $date->format('m'); // renvoie '04' pour le mois d'avril

                    if ($month == '01') {
                        $mois = 'Janvier';
                    } elseif ($month == '02') {
                        $mois = 'Février';

                    } elseif ($month == '03') {
                        $mois = 'Mars';

                    } elseif ($month == '04') {
                    $mois = 'Avril';

                    } elseif ($month == '05') {
                        $mois = 'Mai';

                    } elseif ($month == '06') {
                        $mois = 'Juin';

                    } elseif ($month == '07') {
                        $mois = 'Juillet';
                    } elseif ($month == '08') {
                        $mois = 'Aout';

                    } elseif ($month == '09') {
                        $mois = 'Septembre';

                    } elseif ($month == '10') {
                        $mois = 'Octobre';

                    } elseif ($month == '11') {
                        $mois = 'Novembre';

                    } elseif ($month == '12') {
                        $mois = 'Decembre';
                    } 
                    $entityManager = $this->getDoctrine()->getManager();
                    $query = $entityManager->createQueryBuilder()
                    ->select('a.id, a.adresse, a.ville, a.code_postal, a.complement')
                    ->from(Appartement::class, 'a')
                    ->where('a.id = :id')
                    ->setParameter('id', $infosPaiement['id_appartement'])
                    ->getQuery();

                    $infoAppartement = $query->getOneOrNullResult();

                    if ($infoAppartement) {


                        $transport = Transport::fromDsn('smtp://5d7d6e1e1ea7db:4fb0ea3e43b6cf@sandbox.smtp.mailtrap.io:2525?encryption=tls&auth_mode=login');
                        $mailer = new Mailer($transport);
                
                        $email = (new Email())
                        ->from('admin@infeco.com')
                        ->to('you@example.com')
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('Time for Symfony Mailer!')
                        ->text('Sending emails is fun again!')
                        ->html('
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="utf-8">
                            <title>Quittance de loyer '. $infosLocataires['nom'] . ' ' . $infosLocataires['prenom'] .'</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;
                                    color: #333;
                                }
                                h1, h2, h3 {
                                    color: #0077c0;
                                }
                                .container {
                                    width: 80%;
                                    margin: 0 auto;
                                }
                                .message {
                                    background-color: #f5f5f5;
                                    border-radius: 10px;
                                    padding: 20px;
                                }
                                .message p {
                                    margin: 10px 0;
                                }
                                .message p.amount {
                                    font-weight: bold;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <h1>Quittance de loyer</h1>
                                <h2> Nom de l\'agence </h2>
                                <div class="message">
                                    <p>Bonjour ' . $infosLocataires['nom'] . ' ' . $infosLocataires['prenom'] . ',</p>
                                    <p>L\'Agence nom de lagance, atteste avoir reçu de votre part la somme de <span class="amount">'
                                    . $infosPaiement['somme'] . '</span> au titre du loyer du mois de ' . $mois .' pour l\'appartement situé au ' . $infoAppartement['adresse'] . ', '. $infoAppartement['complement'] .',  ' .$infoAppartement['ville'].', '. $infoAppartement['code_postal'].' </p>
                                    <p>Je vous prie d\'agréer, l\'expression de mes salutations distinguées.</p>
                                    <p> Agence nom</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    
                    
                    
                    
                        ');
            
                        $mailer->send($email);
            
            
                        return $this->redirectToRoute('fiche-locataire', ['id' => $id_locataire]);

                    }
                }
                else {
                    $infosPaiement = '';
                }
            }

            else {
                $infosLocataires='';
            }
            
        

   



    }








   
}
