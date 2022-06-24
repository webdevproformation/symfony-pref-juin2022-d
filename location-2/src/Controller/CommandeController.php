<?php 


namespace App\Controller ;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController{

    #[Route("/admin/commande/list" , name:"commande_list")]
    public function list(EntityManagerInterface $em):Response{
        $commandes = $em->getRepository(Commande::class)->findAll();

        return $this->render("commande/list.html.twig" , compact("commandes"));
        // ["commandes" => $commandes  ]
    }

    #[Route("/admin/commande/new" , name:"commande_new")]
    public function new(Request $request , EntityManagerInterface $em):Response{

        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // nbjour = récupérer dt_ debut // date de fin 
            // prix journalier qui vient de le véhicule choisi 

            // multiplication = nbjour * prix journalier 
            $dt_debut = $form->get("date_heure_depart")->getData();
            $dt_fin = $form->get("date_heure_fin")->getData();
            $interval = $dt_debut->diff($dt_fin);
            $interval->format("%d");
            $nbJours = $interval->days ; 

            if($nbJours < 1){
                dd("erreur");
            }

            $vehicule = $form->get("vehicule")->getData();
            $prix_journalier = $vehicule->getPrixJournalier();

            $commande->setPrixTotal($nbJours * $prix_journalier);
            $em->persist($commande);
            $em->flush();
            // regarder dans la base de données 
        }

        return $this->render("commande/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

}