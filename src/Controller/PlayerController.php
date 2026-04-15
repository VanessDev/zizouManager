<?php

//J’ai un controller avec 3 routes : une pour afficher, une pour ajouter via l’URL, et une via formulaire.
// J’utilise l’EntityManager pour enregistrer en base, et Twig pour afficher les pages

namespace App\Controller;
//  Je définis dans quel dossier se trouve mon controller

use App\Entity\Player;
//  J'importe mon entité Player pour pouvoir créer des joueurs

use Doctrine\ORM\EntityManagerInterface;
// J'importe l'EntityManager pour parler avec la base de données

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//  J'hérite du controller de base Symfony pour avoir des fonctions utiles

use Symfony\Component\HttpFoundation\Response;
//  Ça me permet de retourner une réponse (texte, HTML…)

use Symfony\Component\HttpFoundation\Request;
//  Ça me permet de récupérer les données envoyées (formulaire par exemple)

use Symfony\Component\Routing\Attribute\Route;
//  Ça sert à définir les routes (les URL)

final class PlayerController extends AbstractController
    //  Je crée mon controller Player qui va gérer les joueurs
{
    #[Route('/player', name: 'player_index', methods: ['GET'])]
    //  Je crée une route /player accessible en GET
    public function index(): Response
    {
        //  Ici j’affiche simplement une page Twig
        return $this->render('player/index.html.twig');
    }

    #[Route('/player/add/{name}/{score}', name: 'app_player_add', methods: ['GET'])]
    //  Je crée une route avec des paramètres dans l’URL (name et score)
    public function add(string $name, int $score, EntityManagerInterface $entityManager): Response
    {
        //  Je crée un nouveau joueur
        $player = new Player();

        //  Je lui donne un nom
        $player->setName($name);

        //  Je lui donne un score
        $player->setScore($score);

        //  Je prépare l’enregistrement en base
        $entityManager->persist($player);

        //  J’envoie les données en base de données
        $entityManager->flush();

        //  Je retourne un message pour dire que ça a marché
        return new Response('Joueur ajouté : ' . $name . ' avec le score ' . $score);
    }

    #[Route('/player/new', name: 'app_player_new', methods: ['GET', 'POST'])]
    //  Je crée une route pour afficher un formulaire ET envoyer les données
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //  Je vérifie si le formulaire a été envoyé (POST)
        if ($request->isMethod('POST')) {

            //  Je récupère le nom envoyé dans le formulaire
            $name = $request->request->get('name');

            //  Je récupère le score et je le transforme en nombre
            $score = (int) $request->request->get('score');

            //  Je crée un nouveau joueur
            $player = new Player();

            //  Je mets le nom
            $player->setName($name);

            //  Je mets le score
            $player->setScore($score);

            //  Je prépare l’enregistrement
            $entityManager->persist($player);

            //  J’enregistre en base
            $entityManager->flush();

            // Je retourne un message de confirmation
            return new Response(' Joueur ajouté : ' . $name . ' avec le score ' . $score);


        }

        // Si c’est un GET, j’affiche juste le formulaire
        return $this->render('player/new.html.twig');


    }
    #[Route('/player/show/{id}', name: 'app_player_show', methods: ['GET'])]
    //  Je crée une route pour afficher un joueur grâce à son id
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        //  Je vais chercher le joueur en base avec son id
        $player = $entityManager->getRepository(Player::class)->find($id);

        //  Si le joueur n’existe pas, je retourne une erreur
        if (!$player) {
            return new Response('Joueur non trouvé');
        }

        //  Sinon j’affiche ses infos
        return new Response(
            'Nom : ' . $player->getName() . ' | Score : ' . $player->getScore()
        );
    }
}