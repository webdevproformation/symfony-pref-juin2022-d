<?php 

namespace App\Controller ;

use DateTime;
use App\Entity\User;
use App\Entity\Search;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\SearchType;
use App\Form\CommandeType;
use App\Form\LoginFormType;
use App\Form\InscriptionFormType;
use App\Security\AppAuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController{

    public function __construct(private TokenStorageInterface $tokenStorage)
    {
        
    }


    #[Route("/" , name:"home_index")]
    public function index (Request $request, EntityManagerInterface $em) :Response{
        
        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);

        $listevehiculeDisponible = $em->getRepository(Vehicule::class)->findAll();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $listevehiculeLoue = $em->getRepository(Commande::class)->listeVehiculeLoue($search->getDtDebut() ,$search->getDtFin() );
            $listevehiculeDisponible = $em->getRepository(Vehicule::class)->findByVehiculeDisponibles( $listevehiculeLoue );

            $session = $request->getSession();
            $session->set("dt_debut" , $search->getDtDebut());
            $session->set("dt_fin" , $search->getDtFin());
        }

        return $this->render("front/index.html.twig", [
            "form" => $form->createView(),
            "vehicules" => $listevehiculeDisponible
        ]);
    }

    #[Route("/louer" , name:"home_rent" , methods:["POST", "GET"])]
    public function rent( 
        Request $request, 
        EntityManagerInterface $em , 
        UserPasswordHasherInterface $userPasswordHasher ,
        AppAuthAuthenticator $formAuthenticator,
        UserAuthenticatorInterface $authenticator,
        AuthenticationUtils $authenticationUtils
    ){

        $session = $request->getSession();
        $session->set("id_vehicule", $request->query->get("id"));

        if($this->getUser()){
            return $this->redirectToRoute('home_commande');
        }

        $user = new User();
        $formInscription = $this->createForm(InscriptionFormType::class , $user);
       
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $formInscription->handleRequest($request);

        if ( $formInscription->isSubmitted() && $formInscription->isValid()) {
            // encode the plain password
            // dd("ici");
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $formInscription->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_MEMBRE"]);

            $em->persist($user);
            $em->flush();

            return $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request
            ); 
        }

        return $this->render("front/inscription.html.twig" , [
            "formInscription" => $formInscription->createView(),
            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

    #[Route("/front/login" , name:"home_front_login")]
    public function front_login (AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/inscription.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    #[Route("/commande" , name:"home_commande")]
    public function commande(EntityManagerInterface $em , Request $request ){

        $session = $request->getSession();
        $d1 = $session->get("dt_debut");
        $d2 = $session->get("dt_fin");
        $d3 = $session->get("id_vehicule");
        dump($d1, $d2, $d3);
        if( $d1 === null || $d2 === null || $d3 === null ){
            $this->redirectToRoute("home_index");
        }
       
        $vehiculeALouer = $em->getRepository(Vehicule::class)->find($session->get("id_vehicule"));

        $commande = new Commande();
        
        $commande->setUser($this->getUser())
                 ->setDateHeureDepart($session->get("dt_debut"))
                 ->setDateHeureFin($session->get("dt_fin"))
                 ->setVehicule($vehiculeALouer);

        $form = $this->createForm(CommandeType::class , $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


        }

        return $this->render("front/commande.html.twig" , [
            "form" => $form->createView()
        ] );
    }

}