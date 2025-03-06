<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AccountRepository;
use Symfony\Component\Routing\Annotation\Route;



class ApiAccountController extends AbstractController{

    public function __construct(Private readonly AccountRepository $accountRepository){}

    #[Route('/api/accounts', name: 'api_account_all')]
    public function getAllAccounts(){
        return $this->json($this->accountRepository->findAll(),
        200,
        [],
        ['groups' => 'account:read']
        );
    }
}