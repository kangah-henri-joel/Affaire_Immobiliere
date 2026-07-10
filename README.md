# Projet_Affaire - Plateforme Immobilière & Marketing AI

Projet_Affaire est une application web complète de gestion immobilière et d'automatisation marketing, conçue pour le marché ivoirien. Elle permet de gérer des annonces (terrains, maisons, véhicules), de générer du contenu marketing via IA, et de suivre les leads clients.

## 🚀 Fonctionnalités

- **Gestion d'Annonces** : Création, modification et suppression de biens immobiliers et véhicules.
- **Tableau de Bord Admin** : Interface sécurisée pour la gestion globale.
- **Automatisation Marketing** : Génération de textes publicitaires assistée par IA (intégration Python/AI).
- **Suivi des Leads** : Gestion des contacts clients et réponses automatisées.
- **Statistiques** : Suivi des vues et clics sur les annonces.
- **Géolocalisation** : Visualisation des biens sur une carte interactive.

## 🛠️ Installation

### Prérequis
- XAMPP (Apache & MySQL)
- Navigateur Web moderne

### Étapes
1. **Clonage / Copie** : Placez le dossier du projet dans `C:\xampp\htdocs\Projet_Affaire`.
2. **Base de Données** (une seule base : `immo_affaire_db`) :
   - Lancez MySQL via XAMPP.
   - Accédez à [phpMyAdmin](http://localhost/phpmyadmin).
   - Importez le fichier `schema.sql` à la racine (schéma complet, toutes les tables et données initiales).
3. **Configuration** :
   - Vérifiez les paramètres de connexion dans `config/database.php`.
4. **Lancement** :
   - Accédez à `http://localhost/Projet_Affaire`.

## 🔐 Accès Administration

- **URL** : `http://localhost/Projet_Affaire/login`
- **Comptes par défaut** (créés automatiquement par `schema.sql`) :
  - Admin : `admin` / `admin123`
  - Super Admin : `superadmin` / `admin123`

## 📂 Structure du Projet

- `api/` : Endpoints API pour les interactions dynamiques.
- `assets/` : Ressources statiques (CSS, JS, Images).
- `config/` : Configuration de la base de données et outils (MailHelper).
- `controllers/` : Logique de l'application (MVC).
- `models/` : Interaction avec la base de données (MVC).
- `views/` : Fichiers d'interface utilisateur (MVC).
- `schema.sql` : Schéma complet unique (16 tables, base `immo_affaire_db`).
- `index.php` : Point d'entrée unique de l'application.

## 🛡️ Sécurité
- Mots de passe hachés avec BCRYPT.
- Protection contre les injections SQL via PDO.
- Gestion des sessions utilisateur.
