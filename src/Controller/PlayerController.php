<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PlayerController extends AbstractController
{

    #[Route('/player', name: 'player_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $players = $entityManager->getRepository(Player::class)->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }


    #[Route('/player/add/{name}/{username}/{number}/{score}', name: 'app_player_add', methods: ['GET'])]
    public function add(
        string $name,
        string $username,
        int $number,
        int $score,
        EntityManagerInterface $entityManager
    ): Response {
        $player = new Player();
        $player->setName($name);
        $player->setUsername($username);
        $player->setNumber($number);
        $player->setScore($score);

        $entityManager->persist($player);
        $entityManager->flush();

        return new Response(
            "Joueur ajouté : $name $username | numéro : $number | score : $score"
        );
    }


    #[Route('/player/new', name: 'app_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }

        return $this->render('player/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/player/show/{id}', name: 'app_player_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return new Response('Joueur non trouvé');
        }

        return $this->render('player/show.html.twig', [
            'player' => $player
        ]);
    }

    #[Route('/player/showall', name: 'app_player_showall', methods: ['GET'])]
    public function showall(EntityManagerInterface $entityManager): Response
    {
        $players = $entityManager->getRepository(Player::class)->findAll();

        if (!$players) {
            return new Response('Aucun joueur');
        }

        return $this->render('player/showall.html.twig', [
            'players' => $players
        ]);
    }

    #[Route('/player/update/{id}', name: 'player_update', methods: ['GET', 'POST'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return new Response('Joueur non trouvé');
        }

        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('player_index');
        }

        return $this->render('player/new.html.twig', [
            'form' => $form->createView(),
            'player' => $player
        ]);
    }

    #[Route('/player/delete/{id}', name: 'player_delete', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return new Response('Joueur non trouvé');
        }

        $entityManager->remove($player);
        $entityManager->flush();

        return $this->redirectToRoute('app_player_showall');
    }
}