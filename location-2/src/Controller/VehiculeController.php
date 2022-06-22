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

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route("/list" , name:"vehicule_list")]
    public function list():Response{
       $vehicules = $this->em->getRepository(Vehicule::class)->findAll();
       return $this->render( "vehicule/list.html.twig" , ["vehicules" => $vehicules]);
    }

    #[Route("/supp/{id}" , name:"vehicule_suppr")]
    public function suppr($id){

        $vehiculeASupprimer = $this->em->getRepository(Vehicule::class)->find($id);

        if($vehiculeASupprimer){
            // suppression du fichier dans le dossier upload
            $dossier_upload = $this->getParameter("upload_directory");
            $photo = $vehiculeASupprimer->getPhoto();
            unlink($dossier_upload . "/" . $photo); 
            // fin suppression du fichier dans le dossier upload

            $this->em->remove($vehiculeASupprimer);
            $this->em->flush();
        }

        return $this->redirectToRoute("vehicule_list");

    }

    #[Route("/new" , name:"vehicule_new")]
    public function new(Request $request  ) :Response{
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

            $this->em->persist($vehicule);
            $this->em->flush();
            return $this->redirectToRoute("vehicule_list");
        }
        return $this->render("vehicule/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

}