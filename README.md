# Retro Gaming

> **Projet archivé — n'est plus maintenu.**
> Projet de fin de formation WebForce3, réalisé en avril 2019. Le code est conservé tel quel, à titre d'historique.

Place de marché dédiée aux jeux vidéo rétro : des vendeurs publient des annonces, des acheteurs les achètent et constituent leur collection, et la communauté commente les jeux et les membres.

## Équipe

Projet réalisé en deux semaines (11 → 25 avril 2019) par :

- François Ben Abdellah
- Louis Grandclerc
- guillaume77910

## Fonctionnalités

- **Inscription / connexion** avec trois profils : acheteur, vendeur, administrateur
- **Annonces** : publication par les vendeurs (prix, état, description), consultation publique, suivi « mes annonces »
- **Catalogue de jeux** : titre, plateforme, date de sortie, jaquette
- **Recherche de jeux via l'API IGDB**
- **Profils** acheteurs et vendeurs (boutique, présentation, avatar) et **collection** de jeux par membre
- **Commentaires** sur les jeux et sur les membres
- **Formulaire de contact** (Swiftmailer)
- **Back-office** `/admin` : gestion des membres, vendeurs, annonces, jeux et commentaires (chargement AJAX)

## Stack

- PHP 7.1, Symfony 4.2
- Doctrine ORM, MySQL
- Twig, Bootstrap 4, jQuery
- IGDB API (`emanuilnikolov/igdb-api-bundle`, Buzz, Guzzle)

## Modèle de données

| Entité    | Rôle                                                                 |
|-----------|----------------------------------------------------------------------|
| `User`    | Compte unique avec un rôle (`ROLE_BUYER`, `ROLE_SELLER`, `ROLE_ADMIN`) |
| `Game`    | Jeu du catalogue, lié aux annonces, commentaires et collections       |
| `Ad`      | Annonce : vendeur, acheteur, jeu, prix, état, statut                  |
| `Comment` | Commentaire d'un membre sur un jeu ou sur un autre membre             |

## État du projet

Le projet ne fonctionne plus en l'état et n'a pas vocation à être relancé :

- PHP 7.1 et Symfony 4.2 sont en fin de vie ; les dépendances de 2019 ne s'installent plus sur un PHP récent.
- L'API IGDB utilisée (v3, clé simple) a été remplacée par une authentification Twitch OAuth.
- Aucune migration Doctrine n'est versionnée.
- Le contrôle d'accès est incomplet (seul `/admin` est protégé) : ce code ne doit pas être déployé.
