<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController{

    #[Route(path: '/articles', name: 'app_article_all')]
    public function allArticles(){
        return $this->render('/articles.html.twig');
    }

    #[Route(path: '/articles/id', name: 'app_article_id')]
    public function article(){
        return $this->render('/article.html.twig');
    }
}