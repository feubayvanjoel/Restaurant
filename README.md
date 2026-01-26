# 🍽️ Système de Gestion de Restaurant

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

Application web complète de gestion de restaurant développée avec Laravel, offrant une solution tout-en-un pour la gestion des commandes, réservations, stock, personnel et encaissements.

---

## 📋 Table des Matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies Utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Comptes de Test](#-comptes-de-test)
- [Structure du Projet](#-structure-du-projet)
- [Architecture](#-architecture)
- [Contribution](#-contribution)
- [Licence](#-licence)

---

## ✨ Fonctionnalités

### 🔐 Système d'Authentification
- ✅ Inscription et connexion sécurisées
- ✅ Réinitialisation de mot de passe
- ✅ Gestion basée sur les rôles (RBAC)
- ✅ 5 rôles distincts : CLIENT, ADMIN, CUISINIER, SERVEUR, CAISSIER

### 👤 Interface CLIENT
- ✅ **Menu Interactif** : Parcourir plats et boissons avec panier dynamique
- ✅ **Gestion des Commandes** : Créer, consulter, annuler des commandes
- ✅ **Téléchargement PDF** : Tickets de commande en PDF
- ✅ **Réservations** : Système de réservation avec vérification de disponibilité en temps réel
- ✅ **Suivi en Direct** : Voir le statut des commandes (En attente, En cours, Prête, Servie)
- ✅ **Dashboard Personnalisé** : Vue d'ensemble des activités

### 👨‍💼 Interface ADMIN
- ✅ **Dashboard Complet** : Statistiques temps réel (CA, commandes, stock)
- ✅ **Gestion Utilisateurs** : CRUD complet avec soft/hard delete
- ✅ **Gestion Menu** : Ajout, modification, suppression de plats et boissons
- ✅ **Gestion Stock** : Suivi des quantités avec alertes de stock faible
- ✅ **Rapports & Analytics** : 
  - Chiffre d'affaires par période
  - Top 10 clients
  - Graphiques d'activité
  - Statistiques détaillées

### 👨‍🍳 Interface CUISINIER
- ✅ **Kanban Board** : Vue en colonnes (En attente, En cours, Prêtes)
- ✅ **Gestion Préparation** : Démarrer et terminer la préparation
- ✅ **Actualisation Auto** : Rafraîchissement toutes les 30 secondes
- ✅ **Détails Complets** : Vue détaillée de chaque commande

### 🍽️ Interface SERVEUR
- ✅ **Plan des Tables** : Vue visuelle de toutes les tables
- ✅ **Gestion Statuts** : Marquer tables comme Libre/Occupée/Réservée
- ✅ **Service Commandes** : Marquer commandes comme servies
- ✅ **Vue d'Ensemble** : Dashboard avec commandes prêtes

### 💰 Interface CAISSIER
- ✅ **Encaissements** : Traitement des paiements (Carte, Espèces, Mobile)
- ✅ **Historique Complet** : Tous les encaissements du jour
- ✅ **Statistiques** : CA journalier, ticket moyen, nombre de transactions
- ✅ **Analyse** : Répartition par heure et par table

---

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 10.x** : Framework PHP moderne
- **PHP 8.1+** : Langage serveur
- **MySQL 8.0+** : Base de données relationnelle
- **Eloquent ORM** : Gestion des relations

### Frontend
- **Blade Templates** : Moteur de templates Laravel
- **Tailwind CSS 3.x** : Framework CSS utility-first
- **Alpine.js 3.x** : Framework JavaScript léger
- **Vite** : Build tool moderne

### Autres
- **DomPDF** : Génération de PDF
- **XAMPP/WAMP** : Environnement de développement
- **Composer** : Gestionnaire de dépendances PHP
- **NPM** : Gestionnaire de paquets Node.js

---

## 📦 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 18.x et NPM >= 9.x
- **MySQL** >= 8.0
- **XAMPP/WAMP** (ou un autre serveur local)

---

## 🚀 Installation

### 1. Cloner le Projet

```bash
cd c:\xampp\htdocs
git clone <repository-url> restaurant
cd restaurant
```

### 2. Installer les Dépendances PHP

```bash
composer install
```

### 3. Installer les Dépendances JavaScript

```bash
npm install
```

### 4. Configuration de l'Environnement

Copier le fichier `.env.example` :

```bash
copy .env.example .env
```

Générer la clé d'application :

```bash
php artisan key:generate
```

### 5. Configuration de la Base de Données

Éditer le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Créer la Base de Données

Via phpMyAdmin ou ligne de commande MySQL :

```sql
CREATE DATABASE restaurant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Exécuter les Migrations et Seeders

```bash
php artisan migrate:fresh --seed
```

Cette commande va :
- Créer toutes les tables
- Insérer les données de test (tables, plats, boissons, utilisateurs)

### 8. Compiler les Assets

Pour le développement :
```bash
npm run dev
```

Pour la production :
```bash
npm run build
```

### 9. Lancer le Serveur

```bash
php artisan serve
```

L'application sera accessible sur : `http://localhost:8000`

---

## ⚙️ Configuration

### Configuration Email (Optionnel)

Pour activer la réinitialisation de mot de passe par email, configurer dans `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@restaurant.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 💡 Utilisation

### Accéder à l'Application

1. **Page d'Accueil** : `http://localhost:8000`
2. **Connexion** : `http://localhost:8000/login`
3. **Inscription** : `http://localhost:8000/register`

### Workflow Typique

#### Pour un Client :
1. S'inscrire ou se connecter
2. Parcourir le menu
3. Ajouter des articles au panier
4. Créer une commande en sélectionnant une table
5. Suivre l'état de la commande
6. Télécharger le ticket PDF
7. Créer des réservations

#### Pour le Personnel :

**Cuisinier** :
1. Se connecter
2. Voir les commandes en attente sur le Kanban
3. Démarrer la préparation (passe en "En cours")
4. Marquer comme prête une fois terminée

**Serveur** :
1. Se connecter
2. Gérer les statuts des tables
3. Voir les commandes prêtes
4. Marquer comme servies

**Caissier** :
1. Se connecter
2. Voir les commandes servies
3. Traiter les paiements (Carte/Espèces/Mobile)
4. Consulter l'historique et les statistiques

**Admin** :
1. Se connecter
2. Gérer les utilisateurs (clients et personnel)
3. Gérer le menu (plats et boissons)
4. Surveiller le stock
5. Consulter les rapports et statistiques

---

## 🔑 Comptes de Test

Après avoir exécuté les seeders, les comptes suivants sont disponibles :

### Administrateur
- **Email** : `admin@resto.be`
- **Mot de passe** : `password`
- **Rôle** : ADMIN

### Client (3 comptes disponibles)
- **Email** : `client1@gmail.com`
- **Mot de passe** : `password`
- **Rôle** : CLIENT

### Cuisinier (2 comptes disponibles)
- **Email** : `cuisinier1@resto.be`
- **Mot de passe** : `password`
- **Rôle** : CUISINIER

### Serveur (2 comptes disponibles)
- **Email** : `serveur1@resto.be`
- **Mot de passe** : `password`
- **Rôle** : SERVEUR

### Caissier
- **Email** : `caissier1@resto.be`
- **Mot de passe** : `password`
- **Rôle** : CAISSIER

---

## 📁 Structure du Projet

```
restaurant/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Contrôleurs Admin (5)
│   │   │   ├── Auth/               # Authentification (3)
│   │   │   ├── Caissier/           # Contrôleurs Caissier (2)
│   │   │   ├── Client/             # Contrôleurs Client (4)
│   │   │   ├── Cuisinier/          # Contrôleurs Cuisinier (2)
│   │   │   └── Serveur/            # Contrôleurs Serveur (3)
│   │   └── Middleware/
│   │       └── CheckRole.php       # Middleware RBAC
│   └── Models/                     # Modèles Eloquent (13)
├── database/
│   ├── migrations/                 # Migrations (15+)
│   └── seeders/                    # Seeders (8)
├── resources/
│   ├── css/
│   │   └── app.css                 # Styles Tailwind + custom
│   ├── js/
│   │   └── app.js                  # Alpine.js + helpers
│   └── views/
│       ├── layouts/
│       ├── auth/                   # 3 vues
│       ├── admin/                  # 11 vues
│       ├── client/                 # 11 vues
│       ├── cuisinier/              # 2 vues
│       ├── serveur/                # 4 vues
│       ├── caissier/               # 4 vues
│       └── pdf/                    # 1 vue
├── routes/
│   └── web.php                     # 85+ routes
└── README.md
```

---

## 🏗️ Architecture

### Modèle MVC

L'application suit strictement le pattern MVC de Laravel :

- **Models** : 13 modèles Eloquent avec relations
- **Views** : 36+ vues Blade organisées par rôle
- **Controllers** : 22 contrôleurs organisés par namespace

### Base de Données

#### Tables Principales

1. **COMPTE** : Authentification et rôles
2. **CLIENT** : Informations clients
3. **PERSONNEL** : Employés du restaurant
4. **GESTION_SALLE** : Tables du restaurant
5. **COMMANDE** : Commandes clients
6. **TICKET** : Tickets/Factures
7. **PLATS** : Menu - Plats
8. **BOISSONS** : Menu - Boissons
9. **COMPOSER** : Pivot commande-plats
10. **CONTENIR** : Pivot commande-boissons
11. **HORAIRE_RESERVATION** : Réservations

### Workflow des Commandes

```
CLIENT              CUISINIER           SERVEUR            CAISSIER
  |                    |                   |                   |
  |-- Créer commande ->|                   |                   |
  |          [En attente]                  |                   |
  |                    |-- Démarrer ------>|                   |
  |          [En cours]                    |                   |
  |                    |-- Marquer prête ->|                   |
  |          [Prête]                       |                   |
  |                    |                   |-- Servir -------->|
  |          [Servie]                      |                   |
  |                    |                   |                   |-- Encaisser
  |          [Terminée]                    |                   |
```

---

## 🎨 Design System

### Couleurs Principales

```css
primary: #3B82F6 (Bleu)
secondary: #10B981 (Vert)
accent: #F59E0B (Orange)
danger: #EF4444 (Rouge)
```

### Composants Réutilisables

- **Boutons** : `.btn`, `.btn-primary`, `.btn-secondary`
- **Cartes** : `.card`
- **Badges** : `.badge`, `.badge-success`, `.badge-warning`
- **Formulaires** : `.input`, `.select`, `.textarea`
- **Tables** : `.table`

---

## 🔧 Commandes Utiles

```bash
# Effacer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recréer la base de données
php artisan migrate:fresh --seed

# Build production
npm run build

# Watch mode (dev)
npm run dev

# Vérifier les routes
php artisan route:list
```

---

## 📝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

---

## 🚀 Roadmap

### Améliorations Futures

- [ ] Export Excel des rapports
- [ ] Notifications Push temps réel
- [ ] API REST pour application mobile
- [ ] Multi-langue (FR/EN/NL)
- [ ] Système de fidélité clients
- [ ] Intégration paiement en ligne
- [ ] Progressive Web App (PWA)
- [ ] QR Code pour menu

---

## 📄 Licence

Ce projet est sous licence **MIT**.

---

## ⚡ Quick Start

```bash
# Installation rapide
cd c:\xampp\htdocs\restaurant
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Puis ouvrir `http://localhost:8000` et se connecter avec `admin@resto.be` / `password`

---

**🎉 Projet développé avec ❤️ en utilisant Laravel, Tailwind CSS et Alpine.js**

*Dernière mise à jour : Décembre 2025*
