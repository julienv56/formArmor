<?php

namespace FormArmorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FormArmorBundle\Form\ClientType;
use FormArmorBundle\Entity\Client;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends Controller
{
    public function authentifAction(Request $request) // Affichage du formulaire d'authentification
    {
        // Création du formulaire
        $client = new Client();
        $form = $this->get('form.factory')->create(ClientType::class, $client);


        // Contrôle du mdp si method POST ou affichage du formulaire dans le cas contraire
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
            if ($form->isValid()) {
                // Récupération des données saisies (le nom des controles sont du style nomDuFormulaire[nomDuChamp] (ex. : client[nom] pour le nom) )
                $donneePost = $request->request->get('client');
                $nom = $donneePost['nom'];
                $mdp = $donneePost['password'];
 
                // Controle du nom et du mdp
                $manager = $this->getDoctrine()->getManager();
                $rep = $manager->getRepository('FormArmorBundle:Client');

                $nbClient = $rep->verifMDP($nom, $mdp);

                if ($nbClient > 0) {
                    $session = $session = $request->getSession();
                    $session->set('name', $nom);

                    $leClient = $rep->getClient($nom);

                    if ($leClient[0]->getStatut()->getId() != 6) {
                        return self::listeFormationAction(1);
                    } else {
                        return AdminController::listeStatutAction(1);
                    }
                }
                $request->getSession()->getFlashBag()->add('connection', 'Login ou mot de passe incorrects');
            }
        }

        // Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
        return $this->render('FormArmorBundle:Client:connection.html.twig', array('form' => $form->createView()));
    }

    public function deconnexionAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove($session->get('name'));

        return $this->render('FormArmorBundle::layout.html.twig');
    }

    public function listeFormationAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On peut fixer le nombre de lignes avec la ligne suivante :
        // $nbParPage = 4;
        // Mais bien sûr il est préférable de définir un paramètre dans "app\config\parameters.yml", et d'y accéder comme ceci :
        $nbParPage = $this->container->getParameter('nb_par_page');

        // On récupère l'objet Paginator
        $manager = $this->getDoctrine()->getManager();
        $rep = $manager->getRepository('FormArmorBundle:Formation');
        $lesFormations = $rep->listeFormations($page, $nbParPage);

        // On calcule le nombre total de pages grâce au count($lesFormations) qui retourne le nombre total de formations
        $nbPages = ceil(count($lesFormations) / $nbParPage);

        // Si la page n'existe pas, on retourne une erreur 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render('FormArmorBundle:Client:formation.html.twig', array(
            'lesFormations' => $lesFormations,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    public function listeSessionAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On peut fixer le nombre de lignes avec la ligne suivante :
        // $nbParPage = 4;
        // Mais bien sûr il est préférable de définir un paramètre dans "app\config\parameters.yml", et d'y accéder comme ceci :
        $nbParPage = $this->container->getParameter('nb_par_page');

        // On récupère l'objet Paginator
        $manager = $this->getDoctrine()->getManager();
        $rep = $manager->getRepository('FormArmorBundle:Session_formation');
        $lesSessions = $rep->listeSessions($page, $nbParPage);

        // On calcule le nombre total de pages grâce au count($lesSessions) qui retourne le nombre total de sessions
        $nbPages = ceil(count($lesSessions) / $nbParPage);

        // Si la page n'existe pas, on retourne une erreur 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render('FormArmorBundle:Client:session.html.twig', array(
            'lesSessions' => $lesSessions,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    public function inscrireAction($idFormation, Request $request)
    {   
        $nomPrenom = $request->getSession()->get('name');
        
        $clients = $this->getDoctrine()->getRepository(Client::class)
                        ->getClient($nomPrenom);
        $client = $clients[0];
        $dateToday = Date

        $formation = new Inscription();
        $formation->setClient($client);
        $formation->setPrice(19.99);
        $formation->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
    }
}