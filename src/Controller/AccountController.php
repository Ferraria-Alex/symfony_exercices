<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AccountRepository;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController{

    public function __construct(Private readonly AccountRepository $accountRepository){}

    #[Route(path: '/allAccounts', name: 'app_account_allAccounts')]
    public function showAllAccounts(){
        return $this->render('/accounts.html.twig',[
            'accounts' => $this->accountRepository->findAll(),
        ]);
    }
}