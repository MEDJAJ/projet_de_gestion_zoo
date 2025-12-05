📋 Contexte du Projet
Une crèche souhaite encourager ses petits élèves à mémoriser les animaux de manière ludique. Cette application web interactive aide les enfants à apprendre sur le zoo : les animaux, leurs habitats, les types alimentaires et les images.

L'objectif est de créer un site web simple et interactif pour tester les connaissances des tout-petits de manière éducative et amusante.

🎯 Objectifs
Créer une application web éducative pour les enfants

Faciliter la mémorisation des animaux et leurs caractéristiques

Offrir une interface simple et intuitive pour les éducateurs

Rendre l'apprentissage ludique et visuel

👥 User Stories
En tant que concepteur :
Créer un diagramme de cas d'utilisation pour les fonctions principales

Dessiner la base de données (diagramme ERD)

En tant que développeur Back-End :
Créer une base de données structurée

Écrire des requêtes SQL pour les opérations CRUD

Coder en PHP pour les fonctionnalités principales

Intégrer des graphiques statistiques

En tant que développeur Front-End :
Créer une interface utilisateur responsive

Implémenter des filtres de recherche

Afficher dynamiquement les données

🗃️ Structure de la Base de Données
Table animal
text
ID (INT, PRIMARY KEY, AUTO_INCREMENT)
Nom (VARCHAR)
Type_alimentaire (ENUM: Carnivore, Herbivore, Omnivore)
Image (VARCHAR)
IdHab (INT, FOREIGN KEY)
Table habitats
text
IdHab (INT, PRIMARY KEY, AUTO_INCREMENT)
NomHab (VARCHAR)
Description_Hab (TEXT)
Image (VARCHAR)
🔧 Fonctionnalités
Gestion des Animaux
✅ Ajout d'un nouvel animal

✅ Modification des informations d'un animal

✅ Suppression d'un animal

✅ Affichage de la liste des animaux

✅ Filtrage par type alimentaire

✅ Filtrage par habitat

✅ Affichage des images

Gestion des Habitats
✅ Ajout d'un nouvel habitat

✅ Modification des informations d'un habitat

✅ Suppression d'un habitat

✅ Affichage de la liste des habitats

✅ Association animaux-habitats

Fonctionnalités Avancées
✅ Interface responsive (mobile/desktop)

✅ Validation des formulaires

✅ Upload d'images

✅ Graphiques statistiques

✅ Modal de visualisation des détails

✅ Recherche et filtrage

📊 Types Alimentaires
Carnivore : Mange de la viande

Herbivore : Mange des plantes

Omnivore : Mange viande et plantes

🌍 Types d'Habitats
Savane

Jungle

Désert

Océan

🛠️ Technologies Utilisées
Back-End
PHP 7.4+

MySQL

Apache Server

Front-End
HTML5

CSS3 (Tailwind CSS)

JavaScript (Vanilla)

Chart.js pour les graphiques

Outils
XAMPP/WAMP pour l'environnement de développement

phpMyAdmin pour la gestion de la base de données

Git pour le contrôle de version
