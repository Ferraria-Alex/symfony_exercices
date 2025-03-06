<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AccountRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;



class ApiAccountController extends AbstractController{

    public function __construct(private readonly AccountRepository $accountRepository, private readonly EntityManagerInterface $em, private readonly SerializerInterface $serializer){}

    #[Route('/api/accounts', name: 'api_account_all')]
    public function getAllAccounts(){
        return $this->json($this->accountRepository->findAll(),
        200,
        [],
        ['groups' => 'account:read']
        );
    }

    #[Route('/api/accounts/add', name: 'app_account_add', methods: ['POST'])]
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
        ], []);

    }
}