<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ApiArticleController extends AbstractController{

    public function __construct(Private readonly ArticleRepository $articleRepository){}

    #[Route('/api/articles', name: 'api_article_all')]
    public function getAllArticles(){
        return $this->json($this->articleRepository->findAll(),
        200,
        [],
        ['groups' => 'article:read']
        );
    }

    #[Route('/api/articles/{id}', name: 'api_article_byId')]
    public function getArticleById(int $id){
        return $this->json($this->articleRepository->find($id),
        200,
        [],
        ['groups' => 'articlebyid:read']
        );
    }
}