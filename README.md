# MZOUTI Files — V1

Base Laravel + Supabase pour la plateforme MZOUTI Files.

## Contenu
- Interface responsive sombre violet/cyan.
- Navigation inférieure : Profil, Favoris, Accueil, Playlist, Notifications.
- Catégories : Tous, Histoires, Pensées, Livres, Applications, Documents.
- Authentification Supabase (e-mail/mot de passe, Google si activé dans Supabase).
- Profil modifiable : nom affiché, pseudonyme, bio et avatar.
- Bibliothèque, recherche, favoris, playlist « À voir plus tard » et partage.
- Espace créateur avec rédaction/téléversement et validation obligatoire.
- Messagerie privée par recherche exacte (e-mail ou pseudonyme exact).
- Groupe général automatique, permanent, sans compteur de membres public.
- Invitations : récompense à 5, 10 et 15 inscriptions valides, un code à chaque palier.
- Notifications et administration des publications/groupe.

## Installation
1. Copier les fichiers de ce paquet dans le projet Laravel existant en conservant les fichiers propres au projet.
2. Installer les dépendances Laravel : `composer install`.
3. Copier `.env.example` vers `.env`, puis `php artisan key:generate`.
4. Renseigner `SUPABASE_URL`, `SUPABASE_PUBLISHABLE_KEY` et `SUPABASE_STORAGE_BUCKET`.
5. Exécuter le SQL `supabase/migrations/202611180001_mzouti_files_v1.sql` dans Supabase SQL Editor.
6. Dans Supabase Auth, configurer les URL autorisées et activer les fournisseurs voulus (e-mail, Google).
7. Créer un compte administrateur, puis promouvoir ce compte depuis SQL :
   `update public.profiles set role = 'admin' where id = 'UUID_DU_COMPTE';`
8. Créer dans Supabase Storage les buckets `avatars` (public) et `mzouti-private` (privé).
9. Démarrer : `php artisan serve`.

## Variables .env
```env
APP_NAME="MZOUTI Files"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine
SUPABASE_URL=https://PROJECT.supabase.co
SUPABASE_PUBLISHABLE_KEY=sb_publishable_...
SUPABASE_STORAGE_BUCKET=mzouti-private
```

## Déploiement Render
Le Dockerfile du projet existant peut être conservé s'il installe les extensions PHP nécessaires à votre version Laravel. Définir les variables ci-dessus dans Render, puis déployer.

## Important — état de cette livraison
Ceci est une première livraison complète du code applicatif et du schéma SQL, mais elle n'est pas encore vérifiée sur votre projet Supabase réel ni testée de bout en bout en production. Il faut appliquer la migration, configurer Auth/Storage et tester les parcours avant d'accepter des paiements ou de distribuer des codes Premium. Les paiements ne sont volontairement pas activés dans cette version.

## Sécurité
- Les autorisations sont imposées par RLS dans Supabase, pas uniquement par l'interface.
- Les recherches de membres passent par une fonction SQL d'exact-match ; aucun annuaire public n'est exposé.
- Le groupe général est ajouté automatiquement à chaque nouveau profil et ne peut pas être quitté par l'interface.
- Les récompenses d'invitation sont créées côté base de données à chaque palier.
- Le code de récompense est globalement à usage unique : son utilisation doit être intégrée au produit concerné avant toute promesse d'accès Premium.
