<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account;
use App\Repository\AccountRepository;

class AccountService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AccountRepository $accountRepository
    ) {}


    public function save(Account $account)
    {
        //Tester si les champs sont tous remplis
        if (
            $account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" &&
            $account->getPassword() != ""
        ) {
            //Test si le compte n'existe pas
            if(!$this->accountRepository->findOneBy(["email"=>$account->getEmail()])) {
                //Setter les paramètres 
                $account->setRoles("ROLE_USER");
                $this->em->persist($account);
                $this->em->flush();
            }
            else {
                throw new \Exception("Le compte existe déja");
            }
        }
        //Sinon les champs ne sont pas remplis
        else {
            throw new \Exception("Les champs ne sont pas tous remplis");
        }
    }

    public function getAll()
    {
        //Tester si les champs sont tous remplis
        $accounts = $this->accountRepository->findAll();
        if ($accounts) {
            return $accounts;
        } else {
            throw new \Exception("Aucune compte a ete recupere!");
        }
    }
 
    public function getById(int $id)
    {
        //Tester si les champs sont tous remplis
        $account = $this->accountRepository->findOneBy(["id"=>$id]);
        if ($account) {
            return $account;
        } else {
            throw new \Exception("Aucune compte a ete recupere!");
        }
    }
}