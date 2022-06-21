<?php 


namespace App\Controller ;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController{

    #[Route("/resultat" , name:"search_result")]
    public function resultat(Request $request, ArticleRepository $articleRepo):Response{

        //$motCle = $request->request->all();
        $motCle = $request->request->get("search");
        
        // SELECT * FROM article WHERE titre LIKE %$motCle% OR contenu LIKE %$motCle%
        $resultats = $articleRepo->rechercher($motCle);

        return $this->render("search/resultat.html.twig" , [
            "motCle" => $motCle ,
            "resultats" => $resultats
        ]); 
        //dd($motCle , $resultats );

    }
}