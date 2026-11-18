<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>MZOUTI Files</title>
  <meta name="description" content="Apprenez, découvrez et explorez avec MZOUTI Files.">
  <link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/mzouti.css">
  <link rel="icon" type="image/png" href="/images/mzouti-files.png">
  <link rel="apple-touch-icon" href="/images/mzouti-files.png">
</head>
<body>
<div class="app-shell">
  <header class="topbar">
    <a class="brand" href="/" aria-label="MZOUTI Files"><img src="/images/mzouti-files.png" alt="MZOUTI Files" class="site-logo"><span>MZOUTI <b>FILES</b></span></a>
    <div class="top-actions">
      <button class="ghost-btn" data-view="home">Bibliothèque</button>
      <button class="icon-btn" data-view="notifications" aria-label="Notifications">♧<i id="notifDot"></i></button>
      <button class="profile-chip" data-view="profile"><img id="topAvatar" src="https://placehold.co/36x36/25183d/c8b5ff?text=M" alt=""><span id="topName">Mon profil</span></button>
    </div>
  </header>

  <main id="main">
    <section id="view-home" class="view active">
      <div class="hero">
        <div class="hero-copy"><span class="eyebrow">VOTRE ESPACE NUMÉRIQUE</span><h1>Apprenez, découvrez<br>et <em>explorez.</em></h1><p>Une bibliothèque pour lire, apprendre et partager vos découvertes.</p></div>
        <div class="hero-orb"><span>MF</span></div>
      </div>
      <div class="search-wrap"><span>⌕</span><input id="searchInput" placeholder="Rechercher une connaissance, un livre, une histoire..."><kbd>⌘ K</kbd></div>
      <nav id="categories" class="categories" aria-label="Catégories"></nav>
      <div class="section-heading"><div><span class="eyebrow">À DÉCOUVRIR</span><h2>La bibliothèque</h2></div><button class="text-btn" id="refreshBtn">Actualiser ↗</button></div>
      <div id="libraryGrid" class="library-grid"><div class="empty-state">Chargement de la bibliothèque...</div></div>
    </section>

    <section id="view-profile" class="view">
      <div class="page-heading"><span class="eyebrow">ESPACE PERSONNEL</span><h1>Mon profil</h1></div>
      <div class="profile-layout">
        <div class="panel profile-card">
          <img id="profileAvatar" class="big-avatar" src="https://placehold.co/112x112/25183d/c8b5ff?text=M" alt="Photo de profil">
          <h2 id="profileHeading">Bienvenue</h2><p id="profileEmail" class="muted"></p>
          <label class="upload-label">Changer la photo<input id="avatarInput" type="file" accept="image/*"></label>
          <div class="stats-row"><div><b id="myWorksCount">0</b><span>Œuvres</span></div><div><b id="myFavCount">0</b><span>Favoris</span></div><div><b id="inviteTotal">0/15</b><span>Invitations</span></div></div>
        </div>
        <div class="panel form-panel"><h2>Informations personnelles</h2>
          <label>Nom affiché<input id="displayName" maxlength="60" placeholder="Nom affiché"></label>
          <label>Surnom / pseudonyme<input id="username" maxlength="30" placeholder="MonPseudo"></label>
          <label>Présentation<textarea id="bio" maxlength="240" rows="3" placeholder="Quelques mots sur vous..."></textarea></label>
          <button class="primary-btn" id="saveProfile">Enregistrer les modifications</button>
          <button class="danger-link" id="logoutBtn">Se déconnecter</button>
        </div>
      </div>
      <div class="panel invite-panel"><div><span class="eyebrow">FAITES GRANDIR LA COMMUNAUTÉ</span><h2>Inviter une personne</h2><p class="muted">Un code universel est attribué après 5, 10 et 15 inscriptions valides, soit 3 récompenses maximum.</p></div>
        <div class="invite-progress"><b id="inviteProgress">0 / 15</b><div class="progress-track"><i id="inviteBar"></i></div><small id="inviteStatus">Encore 5 invitations pour votre première récompense.</small><button class="secondary-btn" id="shareInvite">Partager mon invitation</button></div>
      </div>
      <div class="panel"><div class="section-heading"><h2>Mon espace créateur</h2><button class="primary-btn" data-view="creator">Créer une œuvre +</button></div><div id="myWorks" class="my-works muted">Vos publications apparaîtront ici.</div></div>
    </section>

    <section id="view-favorites" class="view"><div class="page-heading"><span class="eyebrow">VOTRE SÉLECTION</span><h1>Mes favoris</h1></div><div id="favoritesGrid" class="library-grid"></div></section>
    <section id="view-playlist" class="view"><div class="page-heading"><span class="eyebrow">À CONSULTER PLUS TARD</span><h1>Ma playlist</h1></div><div id="playlistGrid" class="library-grid"></div></section>
    <section id="view-notifications" class="view"><div class="page-heading"><span class="eyebrow">ACTUALITÉS</span><h1>Notifications</h1></div><div id="notificationsList" class="panel muted">Connectez-vous pour consulter vos notifications.</div></section>

    <section id="view-auth" class="view"><div class="auth-card panel"><div class="brand large"><span class="brand-mark">M</span><span>MZOUTI <b>FILES</b></span></div><span class="eyebrow">VOTRE ESPACE VOUS ATTEND</span><h1 id="authTitle">Bon retour parmi nous</h1><p class="muted">Connectez-vous pour retrouver votre bibliothèque personnelle.</p>
      <div id="authFormFields">
        <div id="signupFields" hidden>
          <label>
            Nom affiché
            <input id="authDisplayName" placeholder="Votre nom">
          </label>
          <label>
            Code d'invitation (facultatif)
            <input id="referralCode" placeholder="Code reçu">
          </label>
        </div>

        <label>
          Adresse e-mail
          <input id="authEmail" type="email" autocomplete="email" placeholder="vous@exemple.com">
        </label>

        <label>
          Mot de passe
          <input id="authPassword" type="password" autocomplete="current-password" placeholder="••••••••">
        </label>

        <button class="primary-btn full" id="authSubmit">Se connecter</button>
        <button class="secondary-btn full" id="googleLogin">Continuer avec Google</button>

        <div class="auth-switch">
          <button id="toggleAuth">Créer un compte</button>
          <button id="forgotPassword">Mot de passe oublié ?</button>
        </div>
      </div>

      <div id="emailConfirmation" style="display:none;text-align:center;padding:28px 18px">
        <div style="font-size:42px;margin-bottom:12px">✉</div>

        <h2 style="margin:0 0 12px">Compte créé avec succès !</h2>

        <p style="margin:0 0 22px">
          Veuillez consulter votre boîte e-mail et cliquer sur le lien de confirmation pour activer votre compte.
        </p>

        <button class="primary-btn full" id="openEmailApp">
          Ouvrir ma boîte e-mail
        </button>
      </div>
    </div></section>

    <section id="view-creator" class="view"><div class="page-heading"><span class="eyebrow">ESPACE CRÉATEUR</span><h1>Publier une œuvre</h1><p class="muted">Chaque publication est vérifiée par l'équipe avant d'être rendue publique.</p></div>
      <div class="panel form-panel">
        <label>Titre<input id="workTitle" maxlength="120" placeholder="Titre de votre œuvre"></label>
        <label>Catégorie<select id="workCategory"><option value="histoires">Histoires</option><option value="pensees">Pensées</option><option value="livres">Livres</option><option value="applications">Applications</option><option value="documents">Documents</option></select></label>
        <label>Image de couverture<input id="coverInput" type="file" accept="image/*"></label>
        <label>Description<textarea id="workDescription" rows="3" placeholder="Présentez votre œuvre..."></textarea></label>
        <label>Nom de l'auteur<input id="authorName" placeholder="Nom affiché comme auteur"></label>
        <div class="editor-toggle"><button class="active" data-editor="write">Écrire directement</button><button data-editor="upload">Téléverser un fichier</button></div>
        <div id="writeEditor"><label>Contenu<textarea id="workContent" rows="10" placeholder="Écrivez votre connaissance ici..."></textarea></label></div>
        <div id="uploadEditor" hidden>
    <label>Fichier de l'œuvre
        <input
            id="workFile"
            type="file"
            accept=".pdf,.doc,.docx,.txt,.epub,.zip,.apk,application/vnd.android.package-archive,application/octet-stream"
        >
    </label>
    <p class="muted small">Les fichiers sont stockés dans un espace privé jusqu'à validation.</p>
</div>
        <button class="primary-btn" id="submitWork">Envoyer pour validation</button>
      </div>
    </section>

    <section id="view-messages" class="view"><div class="page-heading"><span class="eyebrow">COMMUNAUTÉ</span><h1>Messages</h1></div>
      <div class="messages-layout"><aside class="panel conversation-list"><div class="pinned-group"><span>📌 GROUPE ÉPINGLÉ</span><b>Groupe général MZOUTI</b><small id="groupState">Discussion ouverte</small></div><div class="exact-search"><label>Nouvelle conversation<input id="userLookup" placeholder="E-mail ou pseudonyme exact"></label><button class="secondary-btn" id="findUser">Rechercher</button><div id="lookupResult"></div></div><div id="conversationItems"></div></aside>
      <div class="panel chat-panel"><div id="chatHeader" class="chat-header">Sélectionnez une conversation</div><div id="chatMessages" class="chat-messages"></div><form id="sendMessage" class="chat-compose"><input id="messageText" placeholder="Écrire un message..." autocomplete="off"><button class="primary-btn">Envoyer</button></form></div></div>
    </section>

  </main>

  <nav class="bottom-nav" aria-label="Navigation principale">
    <button data-view="profile"><span>♙</span><small>Profil</small></button>
    <button data-view="favorites"><span>♡</span><small>Favoris</small></button>
    <button class="home-nav active" data-view="home"><span>⌂</span><small>Accueil</small></button>
    <button data-view="playlist"><span>▤</span><small>Playlist</small></button>
    <button data-view="messages"><span>✉</span><small>Messages</small></button>
  </nav>
</div>
<div id="toast" class="toast" role="status"></div>
<div id="detailModal" class="modal" hidden><div class="modal-card"><button class="modal-close" id="closeDetail">×</button><div id="detailContent"></div></div></div>
<script>
window.MZOUTI = {
  url: @json(config('services.supabase.url')),
  key: @json(config('services.supabase.publishable_key')),
  bucket: @json(config('services.supabase.bucket', 'mzouti-private')),
  sharedSlug: @json($sharedSlug ?? null)
};
</script>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script src="/js/mzouti.js" defer></script>
</body></html>
