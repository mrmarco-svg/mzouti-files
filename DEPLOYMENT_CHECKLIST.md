# Contrôles obligatoires avant production

- [ ] Vérifier la version Laravel et fusionner les fichiers sans écraser les réglages existants.
- [ ] Appliquer le SQL sur le bon projet Supabase.
- [ ] Créer buckets `avatars` (public) et `mzouti-private` (privé) et leurs policies Storage.
- [ ] Activer Auth e-mail et configurer confirmation d'e-mail.
- [ ] Configurer Google OAuth uniquement avec de vrais identifiants.
- [ ] Créer un compte puis lui attribuer `role='admin'`.
- [ ] Tester inscription sans invitation, puis 5/10/15 inscriptions de test.
- [ ] Vérifier l'unicité et l'usage unique des codes de récompense.
- [ ] Tester DM entre deux comptes et s'assurer qu'un tiers ne lit rien.
- [ ] Tester groupe ouvert/fermé et absence de compteur public.
- [ ] Tester validation/refus de publications.
- [ ] Tester liens publics et partage mobile.
- [ ] Vérifier les politiques RLS et Storage.
- [ ] Activer Supabase Realtime pour `direct_messages`, `group_messages`, `notifications` si le temps réel est souhaité.
- [ ] Tester sur téléphone Android et ordinateur.
- [ ] Ne pas activer de paiement Premium avant une implémentation et des tests spécifiques.
