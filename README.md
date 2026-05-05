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
2. **Base de Données** :
   - Lancez MySQL via XAMPP.
   - Accédez à [phpMyAdmin](http://localhost/phpmyadmin).
   - Créez une base de données nommée `immo_affaire_db`.
   - Importez le fichier `schema.sql` fourni à la racine.
3. **Configuration** :
   - Vérifiez les paramètres de connexion dans `config/database.php`.
4. **Lancement** :
   - Accédez à `http://localhost/Projet_Affaire`.

## 🔐 Accès Administration

- **URL** : `http://localhost/Projet_Affaire/login`
- **Compte par défaut** (à créer via SQL si absent) :
  - **Login** : `admin`
  - **Password** : `admin123`

```sql
INSERT INTO users (username, password, full_name, role) 
VALUES ('admin', '$2y$10$w8.3f6yG0Y7x.y.u9.3r.Oe5gW.Q7zK9gG4g5g6g7g8g9g0g1g2g3', 'Administrateur', 'admin');
```

## 📂 Structure du Projet

- `api/` : Endpoints API pour les interactions dynamiques.
- `assets/` : Ressources statiques (CSS, JS, Images).
- `config/` : Configuration de la base de données et outils (MailHelper).
- `controllers/` : Logique de l'application (MVC).
- `models/` : Interaction avec la base de données (MVC).
- `views/` : Fichiers d'interface utilisateur (MVC).
- `schema.sql` : Script de création de la base de données.
- `index.php` : Point d'entrée unique de l'application.

## 🛡️ Sécurité
- Mots de passe hachés avec BCRYPT.
- Protection contre les injections SQL via PDO.
- Gestion des sessions utilisateur.
