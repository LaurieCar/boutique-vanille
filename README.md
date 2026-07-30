# Boutique Vanille & Épices Exotiques

Site e-commerce Symfony pour la vente de vanille et de produits exotiques (épices, sirops, produits dérivés). Catalogue avec photos/vidéos, gestion de stock, panier, paiement en ligne via Stripe, espace client et back-office admin.

Projet réalisé dans le cadre d'un TP de groupe (ESGI).

## Stack technique

| Composant | Choix |
|---|---|
| Framework | Symfony 7.4 LTS |
| PHP | 8.3 ou 8.4 (voir note ci-dessous) |
| Base de données | PostgreSQL |
| ORM | Doctrine ORM 3.x + Doctrine Migrations |
| Templates | Twig 3.x |
| CSS | Tailwind CSS (via AssetMapper, `symfonycasts/tailwind-bundle`) |
| Formulaires | Symfony Form + Validator |
| Authentification | Symfony Security |
| Upload fichiers | VichUploaderBundle |
| Paiement | Stripe (`stripe/stripe-php`, Stripe Checkout) |
| Tests | PHPUnit |

> **Note PHP** : le cahier des charges impose PHP 8.3 ou 8.4. L'environnement de développement actuel tourne en 8.2.12 — à aligner sur 8.3+ si possible avant la mise en production ou la soutenance.

## Prérequis

- PHP 8.3+ (extensions : `ctype`, `iconv`)
- Composer 2.x
- PostgreSQL 13+ installé et démarré en local
- [Symfony CLI](https://symfony.com/download) (recommandé pour `symfony serve`)
- [Stripe CLI](https://docs.stripe.com/stripe-cli) (pour tester le webhook en local, voir plus bas)

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/LaurieCar/boutique-vanille.git
   cd boutique-vanille
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement local**

   Créer un fichier `.env.local` à la racine (non versionné) avec vos identifiants de base de données locaux :
   ```
   APP_SECRET=une_chaine_aleatoire
   DATABASE_URL="postgresql://<user>:<password>@127.0.0.1:5432/<nom_de_la_base>?serverVersion=<votre_version_pg>&charset=utf8"
   ```

   Le rôle PostgreSQL utilisé doit avoir le droit **"Can create databases"** (via pgAdmin4 : Login/Group Roles → clic droit sur le rôle → Properties → onglet Privileges).

4. **Créer la base de données**
   ```bash
   php bin/console doctrine:database:create
   ```

5. **Exécuter les migrations**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. **Charger les fixtures** (données de démo : 3 catégories, 12 produits)
   ```bash
   php bin/console doctrine:fixtures:load
   ```
   ⚠️ Cette commande **vide et recrée** les tables à chaque exécution — à relancer si vos données de test sont dans un état incohérent.

## Travail en groupe : après chaque `git pull`

Le projet évolue en parallèle sur plusieurs machines. Après avoir récupéré des changements (`git pull`), toujours vérifier :

1. **Dépendances PHP** (si `composer.json`/`composer.lock` a changé) :
   ```bash
   composer install
   ```
2. **Nouvelles migrations** (si de nouvelles entités/colonnes ont été ajoutées) :
   ```bash
   php bin/console doctrine:migrations:status
   php bin/console doctrine:migrations:migrate
   ```

Oublier ces deux étapes est la cause la plus fréquente d'erreurs du type "classe introuvable" ou "colonne/table inexistante" après un pull.

## Lancer le projet en local

Le projet nécessite **deux processus en parallèle** pendant le développement :

**Terminal 1 — compilation Tailwind CSS (à laisser tourner) :**
```bash
php bin/console tailwind:build --watch
```

**Terminal 2 — serveur Symfony :**
```bash
symfony serve
```

Le site est ensuite accessible sur `http://localhost:8000`.

## Configuration Stripe *(à venir)*

Cette section sera complétée lors de l'intégration du paiement. Elle décrira :
- les variables `STRIPE_PUBLIC_KEY` / `STRIPE_SECRET_KEY` / `STRIPE_WEBHOOK_SECRET` à ajouter dans `.env.local` (clés de **test** uniquement, jamais en dur dans le code)
- l'utilisation du Stripe CLI pour recevoir les webhooks en local :
  ```bash
  stripe listen --forward-to localhost:8000/webhook/stripe
  ```

## Tests

```bash
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migrations:migrate
php bin/phpunit
```

## Avancement du projet

- [x] Socle Symfony + PostgreSQL configuré
- [x] Intégration Tailwind CSS via AssetMapper
- [x] Layout de base (header/footer/navigation)
- [x] Entités `Category` / `Product` + migrations
- [x] Upload images/vidéos (VichUploaderBundle) — mappings configurés, câblé dans les fixtures (formulaire d'upload admin à venir)
- [x] Fixtures (3 catégories, 12 produits, images réelles — vidéos pas encore ajoutées)
- [x] Page catalogue avec filtrage par catégorie (`/produits`)
- [x] Page détail produit (`/produits/{slug}`)
- [x] Page Recettes avec vidéos YouTube (`/recettes`)
- [x] Panier en session + vérification du stock
- [x] Authentification (inscription / connexion / mot de passe oublié)
- [~] Intégration Stripe Checkout + webhook — en cours, clés de test à configurer
- [ ] Espace client (historique des commandes)
- [ ] Back-office admin (CRUD produits, gestion commandes)
- [ ] Responsive design
- [ ] Tests PHPUnit fonctionnels
