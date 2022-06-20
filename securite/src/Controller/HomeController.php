<?php 

namespace App\Controller ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route("/" , name:"home_index")]
    public function index():Response{
        return $this->render("home/index.html.twig");
    }

    #[Route("/article" , name:"home_article")]
    public function article():Response {
        return $this->render("home/article.html.twig");
    }

    #[Route("/edit" , name:"home_edit")]
    public function edit():Response{
        return $this->render("home/edit.html.twig");
    }
}