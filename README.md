# Boutique Vanille & Épices Exotiques

Site e-commerce Symfony pour la vente de vanille et de produits exotiques (épices, sirops, produits dérivés). Catalogue avec photos/vidéos, gestion de stock, panier, paiement en ligne via Stripe, espace client et back-office admin.

Projet réalisé dans le cadre d'un TP de groupe (ESGI).

## Stack technique

| Composant | Choix |
|---|---|
| Framework | Symfony 7.4 LTS |
| PHP | 8.3 ou 8.4 |
| Base de données | PostgreSQL |
| ORM | Doctrine ORM 3.x + Doctrine Migrations |
| Templates | Twig 3.x |
| CSS | Tailwind CSS (via AssetMapper, `symfonycasts/tailwind-bundle`) |
| Formulaires | Symfony Form + Validator |
| Authentification | Symfony Security |
| Upload fichiers | VichUploaderBundle |
| Paiement | Stripe (`stripe/stripe-php`, Stripe Checkout) |
| Tests | PHPUnit |

## Prérequis

- PHP 8.3+ (extensions : `ctype`, `iconv`)
- Composer 2.x
- PostgreSQL 13+ installé et démarré en local
- [Symfony CLI](https://symfony.com/download) (recommandé pour `symfony serve`)
- [Stripe CLI](https://docs.stripe.com/stripe-cli) (pour tester le webhook en local)

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
   Cette commande **vide et recrée** les tables à chaque exécution — à relancer si vos données de test sont dans un état incohérent.

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

Le site est ensuite accessible sur `https://localhost:8000` (Symfony CLI sert en HTTPS avec un certificat local auto-signé).

## Configuration et test de Stripe

### 1. Récupérer des clés de test

Demander les clés Stripe de **test** de l'équipe (`pk_test_...` / `sk_test_...`), ou en créer sur [dashboard.stripe.com](https://dashboard.stripe.com) (mode test, gratuit). Les ajouter dans `.env.local` :
```
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```
Ne jamais mettre de clés Stripe en dur dans le code ni dans `.env` (versionné) — uniquement dans `.env.local`.

### 2. Installer le Stripe CLI

Nécessaire pour recevoir les webhooks Stripe en local (Stripe ne peut pas contacter `localhost` directement depuis internet).
```bash
winget install --id Stripe.StripeCli -e
```
(ou voir [docs.stripe.com/stripe-cli](https://docs.stripe.com/stripe-cli) pour les autres OS). Ouvrir un **nouveau terminal** après l'installation.

### 3. Lancer l'écoute du webhook

Pas besoin de `stripe login` / compte personnel : on s'authentifie directement avec la clé secrète déjà en main. **Important** : `symfony serve` tourne en HTTPS, donc l'URL cible doit être en `https://` avec `--skip-verify` (certificat local auto-signé) :
```bash
stripe listen --api-key sk_test_... --forward-to https://localhost:8000/webhook/stripe --skip-verify
```
Ça affiche un `whsec_...` : vérifier qu'il correspond bien à `STRIPE_WEBHOOK_SECRET` dans `.env.local` (le mettre à jour sinon). Garder ce terminal ouvert pendant les tests — c'est un **3e processus** en plus de Tailwind et `symfony serve`.

### 4. Tester un paiement

Passer une commande sur le site et payer avec la carte de test Stripe :
```
Numéro : 4242 4242 4242 4242
Date : n'importe quelle date future
CVC : n'importe quel code à 3 chiffres
```
Dans le terminal `stripe listen`, l'événement `checkout.session.completed` doit recevoir un `200` (pas un `307` : signe que Stripe est bien redirigé vers l'HTTPS local). Vérifier ensuite dans `/admin` que la commande passe au statut "Payée" et que le stock du produit acheté a diminué.

## Tests

Les tests utilisent une base **SQLite** dédiée (pas PostgreSQL), pour rester simples et ne jamais toucher à la base de développement.

1. Créer `.env.test.local` (non versionné) :
   ```
   DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_test.db"
   ```
2. Construire le schéma de test à partir des entités (pas de migrations, elles contiennent du SQL spécifique PostgreSQL) :
   ```bash
   php bin/console --env=test doctrine:schema:create
   ```
3. Lancer les tests :
   ```bash
   php bin/phpunit
   ```

3 tests fonctionnels minimaux (catalogue accessible, accès admin refusé sans rôle, ajout au panier)
