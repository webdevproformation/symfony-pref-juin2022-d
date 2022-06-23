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

    #[Route("/admin/commande/new" , name:"commande_new")]
    public function new(Request $request , EntityManagerInterface $em):Response{

        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $dt_debut = $form->get("date_heure_depart")->getData();
            $dt_fin = $form->get("date_heure_fin")->getData();
            $interval = $dt_debut->diff($dt_fin);
            $interval->format("%d");
            $nbJours = $interval->days ; 

            $vehicule = $form->get("vehicule")->getData();
            $prix_journalier = $vehicule->getPrixJournalier();

            $commande->setPrixTotal($nbJours * $prix_journalier);
            $em->persist($commande);
            $em->flush();
            // regarder dans la base de données 
        }

        // nbjour = récupérer dt_ debut // date de fin 
        // prix journalier qui vient de le véhicule choisi 

        // multiplication = nbjour * prix journalier 

        return $this->render("commande/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

}