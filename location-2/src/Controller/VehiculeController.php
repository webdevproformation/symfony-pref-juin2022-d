<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/vehicule")]
class VehiculeController extends AbstractController{
    #[Route("/new" , name:"vehicule_new")]
    public function new(Request $request , EntityManagerInterface $em ) :Response{
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            // récupérer le fichier 
            // le nommer le déplacer 
            //$file = $request->files->get("vehicule")["photo"];
            $file = $form["photo"]->getData();

            $dossier_upload = $this->getParameter("upload_directory");
            $photo = md5(uniqid()) . "." . $file->guessExtension(); // .jpg
            
            $file->move( $dossier_upload , $photo  ); 

            $vehicule->setPhoto($photo);

            
            $em->persist($vehicule);
            $em->flush();
        }
        return $this->render("vehicule/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

}