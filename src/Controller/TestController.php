<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function hello(): Response
    {
        return $this->render('test/hello.html.twig');
    }

    #[Route('/message', name: 'app_message')]
    public function message(): Response
    {
        $message = "Hello World";

        dump($message);
        // dd($message);

        return $this->render('test/message.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/cars', name: 'app_cars')]
    public function cars(): Response
    {
        $cars = ['BMW', 'Audi', 'Mercedes', 'Tesla'];

        dump($cars);

        return $this->render('test/cars.html.twig', [
            'cars' => $cars,
        ]);
    }
}