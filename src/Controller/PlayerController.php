<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PlayerController extends AbstractController
{
    #[Route('/player', name: 'player_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('player/index.html.twig');
    }

    #[Route('/player/add/{name}/{score}', name: 'app_player_add', methods: ['GET'])]
    public function add(string $name, int $score, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();
        $player->setName($name);
        $player->setScore($score);

        $entityManager->persist($player);
        $entityManager->flush();

        return new Response('Joueur ajouté : ' . $name . ' avec le score ' . $score);
    }

    #[Route('/player/new', name: 'app_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {

            $name = $request->request->get('name');
            $score = (int) $request->request->get('score');

            $player = new Player();
            $player->setName($name);
            $player->setScore($score);

            $entityManager->persist($player);
            $entityManager->flush();

            return new Response('Joueur ajouté : ' . $name . ' avec le score ' . $score);
        }

        return $this->render('player/new.html.twig');
    }

    #[Route('/player/show/{id}', name: 'app_player_show', methods: ['GET', 'POST'])]
    public function show(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('GET')) {

            $player = $entityManager->getRepository(Player::class)->find($id);

            if (!$player) {
                return new Response('Joueur non trouvé');
            }

            return new Response(
                'Nom : ' . $player->getName() . ' | Score : ' . $player->getScore()
            );
        }

        if ($request->isMethod('POST')) {
            return new Response('Requête POST reçue');
        }
    }
}