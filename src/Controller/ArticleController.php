<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController{

    public function __construct(Private readonly ArticleRepository $articleRepository){}

    #[Route(path: '/articles', name: 'app_article_all')]
    public function showAllArticles(){
        return $this->render('/articles.html.twig',[
            'articles' => $this->articleRepository->findAll(),
        ]);
    }

    #[Route(path: '/articles/id', name: 'app_article_id')]
    public function article(){
        return $this->render('/article.html.twig');
    }
}