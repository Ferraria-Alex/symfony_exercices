<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Account;
use App\Form\AccountType;
use App\Service\AccountService;


class AccountController extends AbstractController{

    public function __construct(
        private readonly AccountService $accountService
    ) {}

    #[Route(path: '/allAccounts', name: 'app_account_allAccounts')]
    public function showAllAccounts(){
        return $this->render('/accounts.html.twig',[
            'accounts' => $this->accountService->getAll(),
        ]);
    }

    
    #[Route('/accounts/add', name:'app_account_add')]
    public function addAccount(Request $request): Response
    {
        $user = new Account();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        $type = "";
        $msg = "";
        //test si le formulaire est submit
        if($form->isSubmitted() && $form->isValid()) {
            try {
                //Appel de la méthode save d'AccountService
                $this->accountService->save($user);
                $type = "success";
                $msg = "Le compte a ete ajoute en BDD";
            } 
            //Capturer les exceptions (erreurs)
            catch (\Exception $e) {
                $type = "danger";
                $msg = $e->getMessage();
            }  
        }
        $this->addFlash($type, $msg);
        return $this->render('/add_account.html.twig',[
            'form' =>$form
        ]);
    }
}