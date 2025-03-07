<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class ApiAccountController extends AbstractController{

    public function __construct(
        private readonly AccountRepository $accountRepository, 
        private readonly ArticleRepository $articleRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em, 
        private readonly SerializerInterface $serializer
        ){}

    #[Route('/api/accounts', name: 'api_account_all')]
    public function getAllAccounts(){
        return $this->json($this->accountRepository->findAll(),
        200,
        [],
        ['groups' => 'account:read']
        );
    }

    #[Route('/api/accounts/add', name: 'api_account_add', methods: ['POST'])]
    public function addAccount(Request $request){
        $request = $request->getContent();
        $account = $this->serializer->deserialize($request, Account::class, 'json');

        if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
            $this->em->persist($account);
            $this->em->flush();
            $code = 201;
        } else {
            $account = "La compte existe déjà";
            $code = 400;
        }

        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ["groups" => "account:read"]);
    }

    #[Route('/api/accounts/update/{email}', name: 'api_account_update', methods: ['PUT'])]
    public function updateAccount(Request $request, String $email){

        $request = $request->getContent();
        $updateInfo = $this->serializer->deserialize($request, Account::class, 'json');

        if ($updateInfo->getFirstname() && $updateInfo->getLastname() && $email) {

            $account = $this->accountRepository->findOneBy(['email' => $email]);

            if($account){
                $account->setFirstname($updateInfo->getFirstname());
                $account->setLastname($updateInfo->getLastname());
                $this->em->flush();
                $code = 200;
            } else {
                $account = "La compte n'existe pas";
                $code = 400;
            }
        } else {
            $account = "Champs non remplis";
            $code = 400;
        }

        return $this->json(
            $account,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'account:read']
        );
    }

    #[Route('/api/accounts/delete/{id}', name: 'api_account_delete', methods: ['DELETE'])]
    public function deleteAccount(int $id){

        $account = $this->accountRepository->findOneBy(['id' => $id]);
        $articles = $this->articleRepository->findBy(['author' => $account]);

        if(!$articles){
            if($account){
                $this->em->remove($account);
                $this->em->flush();
                $account = "La compte a ete eliminé avec success";
                $code = 200;
            } else {
                $account = "La compte n'existe pas";
                $code = 400;
            }
        } else {
            $account = "La compte ne peux pas etre eliminé. Elle contient des articles associes!";
            $code = 409;
        }
        return $this->json(
            $account,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'account:read']
        );
    }

    #[Route('/api/accounts/patch/{email}', name: 'api_account_patch', methods: ['PATCH'])]
    public function patchAccount(Request $request, String $email){

        $request = $request->getContent();
        $updateInfo = $this->serializer->deserialize($request, Account::class, 'json');

        if ($updateInfo->getPassword() && $email) {
            if($updateInfo->getPassword() > 8){

                $account = $this->accountRepository->findOneBy(['email' => $email]);

                if($account){

                    $hashedPassword = password_hash($updateInfo->getPassword(), PASSWORD_BCRYPT);

                    $account->setPassword($hashedPassword);
                    $this->em->flush();
                    $account = "The password has been succesfully updated";
                    $code = 200;
                } else {
                    $account = "La compte n'existe pas";
                    $code = 400;
                }
            } else {
                $account = "Weak Password";
                $code = 400;
            }
        } else {
            $account = "Champs non remplis";
            $code = 400;
        }

        return $this->json(
            $account,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'account:read']
        );
    }
}