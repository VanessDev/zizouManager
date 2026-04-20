Projet Symfony CRUD et relations

Dans ce projet, j’ai réalisé deux exercices pour comprendre les bases de Symfony avec Doctrine.

Le premier exercice consiste à créer une relation entre deux entités Player et Team.
Le second exercice consiste à créer une application de listes de tâches avec TodoList et TodoItem.

Exercice 1 Player et Team

Le but est de comprendre comment relier deux entités.

Un Player appartient à une seule Team
Une Team peut avoir plusieurs Players

On utilise donc une relation ManyToOne côté Player et OneToMany côté Team.

Pour créer la relation, j’utilise la commande suivante dans le terminal :

docker compose exec php php bin/console make:entity Player

Puis j’ajoute un champ team de type relation et je choisis Team. Symfony me guide automatiquement pour créer la relation.

Ensuite je mets à jour la base de données :

docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate

Pour permettre à l’utilisateur de choisir une équipe dans le formulaire, j’ajoute un champ EntityType dans PlayerType.

Exercice 2 TodoList et TodoItem

Le but est de créer une petite application de tâches.

Une TodoList contient plusieurs TodoItem
Un TodoItem appartient à une seule TodoList

Je commence par créer les entités :

docker compose exec php php bin/console make:entity TodoList

J’ajoute un champ name

Puis :

docker compose exec php php bin/console make:entity TodoItem

J’ajoute les champs title et isDone
Ensuite j’ajoute une relation vers TodoList

Symfony me propose automatiquement de créer la relation entre les deux entités.

Après ça, je mets à jour la base :

docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate

Ensuite je génère les interfaces avec :

docker compose exec php php bin/console make:crud TodoList
docker compose exec php php bin/console make:crud TodoItem

Utilisation

Pour créer une liste :

http://localhost:8080/todo/list/new

Pour créer une tâche :

http://localhost:8080/todo/item/new

Dans le formulaire de tâche, on peut choisir à quelle liste elle appartient.

Conclusion

Ces deux exercices m’ont permis de comprendre comment créer des entités, faire des relations entre elles et utiliser Symfony pour générer rapidement des formulaires et des pages CRUD.
