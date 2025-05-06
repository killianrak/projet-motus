# Projet Motus

Ce projet est une implémentation du jeu Motus en utilisant PHP, JavaScript, HTML et CSS.

## Description

Motus est un jeu dans lequel il faut trouver un mot parmi une liste. Le joueur fait des propositions pour affiner ses choix et essayer de deviner le mot dans le nombre de tentatives imparties (6 chances). Le jeu se termine lorsque le joueur devine correctement le mot secret, ou lorsque toutes les tentatives sont épuisées sans succès. La première lettre du mot est affichée par défaut.

## Fonctionnalités

- Sélection aléatoire d'un mot dans une base de données
- Interaction par clavier sans rechargement de page
- Gestion des erreurs et de la victoire
- Visualisation des lettres :
  - Carrés rouges pour les lettres bien placées
  - Cercles jaunes pour les lettres présentes mais mal placées
  - Fond bleu pour les lettres absentes
- Système d'inscription et de connexion
- "Wall of Fame" pour le classement des joueurs
- Différents niveaux de difficulté

## Prérequis

- Docker
- Docker Compose

## Installation

1. Clonez ce dépôt :
   ```
   git clone https://github.com/killianrak/projet-motus.git
   cd projet-motus
   ```

2. Lancez l'application avec Docker Compose :
   ```
   docker-compose up --build
   ```

3. L'application sera accessible à :
   - Jeu : http://localhost:8080
   - PhpMyAdmin : http://localhost:8081 (utilisateur: root, mot de passe: root)

## Technologies utilisées

- PHP (Programmation Orientée Objet)
- JavaScript
- HTML5 / CSS3
- MySQL
- Docker

## Auteur

Killian Rakotonanahary
