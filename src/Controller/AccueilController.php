<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController {
    #[Route(path:'/calcul/{method}/{num1}/{num2}', name: 'app_accueil_addition')]

    public function calcul(string $method, int $num1, int $num2): Response {

        if(!is_int($num1) || !is_int($num2)){
            return new Response('Not a Number');
        }

        if($method === "add"){

            $result = $num1 + $num2;

        } elseif ($method === "sous"){

            $result = $num1 - $num2;

        } elseif($method === "multi"){

            $result = $num1 * $num2;

        } elseif($method === "div"){

            if($num2 === 0){
                return new Response('Division impossible by 0');
            }

            $result = $num1 / $num2;

        } else {

            return new Response('L\'operateur est incorrect');

        }

        return new Response('Le '.$method.' de ' . $num1 . ' et ' . $num2 . ' est égale à : ' . $result);
    }
}