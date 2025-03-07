<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
#use phpDocumentor\Reflection\DocBlock\Serializer;
#use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
#use Symfony\Component\Serializer\Encoder\DecoderInterface;
#use Symfony\Component\Serializer\Encoder\EncoderInterface;
#use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
#use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiArticleController extends AbstractController{

    public function __construct(Private readonly ArticleRepository $articleRepository,
    private readonly AccountRepository $accountRepository,
    private readonly CategoryRepository $categoryRepository,
    private readonly EntityManagerInterface $em,
    private readonly SerializerInterface $serializer){}

    #[Route('/api/articles', name: 'api_article_all')]
    public function getAllArticles(){
        return $this->json($this->articleRepository->findAll(),
        200,
        [],
        ['groups' => 'article:read']
        );
    }

    #[Route('/api/articles/{id}', name: 'api_article_byid')]
    public function getArticleById(int $id){
        return $this->json($this->articleRepository->find($id),
        200,
        [],
        ['groups' => 'articlebyid:read']
        );
    }

    #[Route('/api/articles-add', name: 'app_articles_add', methods: ['POST'])]
    public function addArticle(Request $request){
        $request = $request->getContent();
        $article = $this->serializer->deserialize($request, Article::class, 'json');

        if ($article->getTitle() && $article->getContent() && $article->getAuthor()) {

            $article->setAuthor($this->accountRepository->findOneBy(["email" => $article->getAuthor()->getEmail()]));

            foreach ($article->getCategories() as $key => $value) {
                $cat = $value->getName();
                $article->removeCategory($value);
                $cat = $this->categoryRepository->findOneBy(["name" => $cat]);
                $article->addCategory($cat);
            }

            //Test l'article n'existe pas
            if (!$this->articleRepository->findOneBy(["title" => $article->getTitle(), "content" => $article->getContent()])) {
                $this->em->persist($article);
                $this->em->flush();
                $code = 201;
            } else {
                $code = 400;
                $article = "Article existe déjà";
            }
        } else {
            $code = 400;
            $article = "Champs non remplis";
        }

        return $this->json(
            $article,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'article:read']
        );

    }
}