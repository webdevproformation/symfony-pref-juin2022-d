<?php 


namespace App\Controller ;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class SearchController extends AbstractController{
    private $pagination ;
    public function __construct(PaginatorInterface $pagination){
        $this->pagination = $pagination;
    }
    #[Route("/resultat" , name:"search_result")]
    public function resultat(Request $request, ArticleRepository $articleRepo):Response{
        //$motCle = $request->request->all();
        $motCle = $request->request->get("search");
        $session = $request->getSession();
        if(strlen($motCle) > 0){
            $session->set("search" , $motCle);
        }
        // SELECT * FROM article WHERE titre LIKE %$motCle% OR contenu LIKE %$motCle%
        $resultats = $articleRepo->rechercher($session->get("search"));
        $pagination = $this->pagination->paginate(
            $resultats ,
            $request->query->getInt('page', 1) ,
            5
        );
        return $this->render("search/resultat.html.twig" , [
            "motCle" => $session->get("search") ,
            "resultats" => $pagination
        ]); 
    }
}