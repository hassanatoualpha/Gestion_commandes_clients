# 🛒 Application de Gestion de Commandes Clients

Projet Symfony 7 développé dans le cadre de la Licence 3 à l’Université Gamal Abdel Nasser de Conakry, Faculté du Centre Informatique.

- **Auteur** : Hassanatou Diallo  
- **Encadrement** : UGANC – Centre Informatique  
- **Technologie** : Symfony 7  
- **Durée estimée** : 2 à 3 jours  

---

## 🎯 Objectif

Concevoir une **application web de gestion de commandes** pour un mini système de e-commerce. L’objectif principal est de permettre aux **clients de passer des commandes** et aux **administrateurs de gérer les produits** et **valider les commandes**. L’application respecte une architecture **MVC**, utilise **Symfony 7**, est sécurisée par des rôles, et suit les conventions de bonnes pratiques du framework.

---

## 🧩 Fonctionnalités

### 🔐 Authentification
- Inscription, connexion et déconnexion
- Gestion des rôles : `ROLE_USER` (client) et `ROLE_ADMIN` (admin)
- Sécurisation des routes selon le rôle

### 📦 Gestion des entités (CRUD)
- **Produit** : CRUD complet, **restreint aux administrateurs**
- **Commande** : création par le client, validation par l’admin
- **Client** : CRUD associé à l'utilisateur connecté

### 🔁 Relations entre entités
- Une commande est liée à un **client** (`ManyToOne`)
- Une commande contient plusieurs **produits** (`ManyToMany`)

### 📄 Affichages dynamiques
- Badge **“Validée”** ou **“En attente”** selon le statut de la commande
- Liste des commandes uniquement visibles par leur auteur (client connecté)

### ⚙️ Logique métier
- Lorsqu'une commande est validée par l’administrateur, son **statut** est mis à jour automatiquement
- Mise à jour du **stock produit** après validation si applicable

---

## 🗂️ Modèle de Données

### 🧑‍💼 `User`
- `email`  
- `password`  
- `roles` (ex: `ROLE_USER`, `ROLE_ADMIN`)

### 🧍 `Client`
- `nom`  
- `téléphone`  
- `utilisateur_id` (relation `OneToOne` avec `User`)

### 📦 `Produit`
- `id`  
- `nom`  
- `prix`  
- `stock`  
- `description`

### 📝 `Commande`
- `id`  
- `date`  
- `statut` (boolean : validée ou non)
- `client_id` (relation `ManyToOne`)  
- `produits` (relation `ManyToMany`)

---

## 🛠️ Technologies utilisées

| Outil            | Version recommandée         |
|------------------|-----------------------------|
| Symfony          | 7.x                         |
| PHP              | 8.1 ou supérieur            |
| Doctrine ORM     | Intégré avec Symfony        |
| Base de données  | MySQL ou SQLite             |
| Moteur de vues   | Twig                        |
| Design           | CSS / Bootstrap             |

---

## 🧪 Procédure d’installation

### 1. Cloner le projet

```bash
git clone https://github.com/hassanatoualpha/Gestion_commandes_clients
cd Gestion_commandes_clients

2. Installer les dépendances PHP
composer install

3. Créer le fichier .env.local pour configurer la base de données
DATABASE_URL="mysql://root:6218@127.0.0.1:3306/hasna_pharmacie?serverVersion=8.0.32&charset=utf8mb4"

4. Créer la base de données et appliquer les migrations
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
