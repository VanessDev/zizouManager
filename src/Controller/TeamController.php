<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TeamController extends AbstractController
{
    #[Route('/team', name: 'team_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams
        ]);
    }

    #[Route('/team/add/{name}', name: 'app_team_add', methods: ['GET'])]
    public function add(string $name, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $team->setName($name);

        $entityManager->persist($team);
        $entityManager->flush();

        return new Response("Équipe ajoutée : $name");
    }

    #[Route('/team/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/team/show/{id}', name: 'app_team_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            return new Response('Équipe non trouvée');
        }

        return $this->render('team/show.html.twig', [
            'team' => $team
        ]);
    }

    #[Route('/team/showall', name: 'app_team_showall', methods: ['GET'])]
    public function showall(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();

        if (!$teams) {
            return new Response('Aucune équipe');
        }

        return $this->render('team/showall.html.twig', [
            'teams' => $teams
        ]);
    }

    #[Route('/team/update/{id}', name: 'team_update', methods: ['GET', 'POST'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            return new Response('Équipe non trouvée');
        }

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
            'team' => $team
        ]);
    }

    #[Route('/team/delete/{id}', name: 'team_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Équipe non trouvée');
        }

        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_showall');
    }
}