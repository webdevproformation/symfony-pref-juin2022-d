<?php 

namespace App\Controller ;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route("/" , name:"home_index")]
    public function index(Request $request , ManagerRegistry $doctrine):Response{
       
        $articles = $doctrine->getRepository(Article::class)->findAll();

        return $this->render("home/index.html.twig", [ "articles" => $articles ]);
    }

    #[Route("/article" , name:"home_article")]
    public function article():Response {
        return $this->render("home/article.html.twig");
    }

    #[Route("/edit" , name:"home_edit")]
    public function edit():Response{
        return $this->render("home/edit.html.twig");
    }

    #[Route("/new/article" , name:"home_new_article")]
    public function new_article(Request $request , EntityManagerInterface $em ) :Response{

       /*  dump($this->getUser()); */
        $article = new Article();
        $article->setUser($this->getUser());

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute("home_index");
        }

        return $this->render("home/new-article.html.twig", [
            "form" => $form->createView()
        ]);
    }
}