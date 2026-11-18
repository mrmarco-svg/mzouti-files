<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<title>Administration sécurisée · MZOUTI Files</title>
<link rel="stylesheet" href="/css/mzouti.css">
<style>
.admin-shell{max-width:1050px;margin:0 auto;padding:28px 18px 70px}.admin-head{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:26px}.admin-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.admin-card{background:var(--panel,#171426);border:1px solid var(--line,#302a40);border-radius:18px;padding:20px;margin-bottom:18px}.admin-row{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;padding:14px 0;border-bottom:1px solid var(--line,#302a40)}.admin-row:last-child{border:0}.admin-row p{margin:6px 0;color:var(--muted,#aaa)}.admin-actions{display:flex;gap:8px;flex-wrap:wrap}.admin-actions button{white-space:nowrap}.admin-status{padding:8px 12px;border-radius:30px;background:#29243b;display:inline-block}.admin-login{max-width:460px;margin:12vh auto}.admin-login input{width:100%;margin:8px 0 16px}.admin-alert{padding:12px;border-radius:12px;background:#38232b;color:#ffdce4;margin:12px 0}.admin-shell[hidden],#adminLogin[hidden],#adminDenied[hidden]{display:none}@media(max-width:720px){.admin-grid{grid-template-columns:1fr}.admin-head{align-items:flex-start;flex-direction:column}.admin-row{flex-direction:column}}
</style>
</head>
<body>
<div class="admin-shell">
<section id="adminLogin" class="admin-card admin-login">
<h1>Accès administration</h1><p class="muted">Connectez-vous avec votre compte administrateur MZOUTI Files.</p>
<label>E-mail<input id="adminEmail" type="email" autocomplete="username"></label>
<label>Mot de passe<input id="adminPassword" type="password" autocomplete="current-password"></label>
<button class="primary-btn full" id="adminSignIn">Se connecter</button><p id="adminLoginError" class="admin-alert" hidden></p>
</section>
<section id="adminDenied" class="admin-card" hidden><h1>Accès refusé</h1><p>Ce compte ne possède pas le rôle administrateur.</p><button class="secondary-btn" id="adminSignOut">Se déconnecter</button></section>
<section id="adminPanel" hidden>
<header class="admin-head"><div><span class="eyebrow">ZONE PRIVÉE</span><h1>Administration MZOUTI Files</h1><p id="adminIdentity" class="muted"></p></div><div class="admin-actions"><a class="secondary-btn" href="/">Retour au site</a><button class="secondary-btn" id="adminLogout">Déconnexion</button></div></header>
<div class="admin-grid">
<div class="admin-card"><h2>Publications en attente</h2><p class="muted">Vérifiez chaque œuvre avant publication.</p><div id="adminPending">Chargement…</div></div>
<div class="admin-card"><h2>Groupe général</h2><p class="muted">La fermeture bloque l'envoi, mais conserve la lecture de l'historique.</p><p id="adminGroupStatus" class="admin-status">Chargement…</p><button class="secondary-btn" id="adminToggleGroup">Ouvrir / fermer les discussions</button><h3>Derniers messages</h3><div id="adminGroupMessages"></div></div>
</div>
<div id="adminNotice" class="muted" role="status"></div>
</section>
</div>
<script>window.MZOUTI={url:@json(config('services.supabase.url')),key:@json(config('services.supabase.publishable_key')),bucket:@json(config('services.supabase.bucket','mzouti-private'))};</script>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script><script src="/js/admin.js" defer></script>
</body></html>
