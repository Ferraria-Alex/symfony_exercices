<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalculController extends AbstractController{

    #[Route(path:'/calculatrice/{num1}{operateur}{num2}', name: 'app_calcul_calculatrice')]
    public function calculatrice(string $operateur, int $num1, int $num2) {
        switch($operateur){
            case "+":
                $result = $num1 + $num2;
                break;
            case "*":
                $result = $num1 * $num2;
                break;
            case "-":
                $result = $num1 - $num2;
                break;
            case ":":
                $result = $num1 / $num2;
                break;
            case "**":
                $result = $num1 ** $num2;
                break;
            default:
                $result = "NaN";
                break;
        }

        return $this->render('/calculatrice.html.twig',[
            'num1' => $num1,
            'num2' => $num2,
            'operateur' => $operateur,
            'resultat' => $result
        ]);
    }
}