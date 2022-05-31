  <!-- headerprofil.php
    En-tête de la page de profil d'un utilisateur + navigation par onglet
  -->

<!-- avatar utilisateur -->
      
  <img src="users/<?= $this->request->getParam('username') ?>/<?= $this->request->getParam('username') ?>.jpg" alt="image utilisateur" width="88" height="88" class="d-inline rounded-circle me-1">
  
<!-- nom utilisatzur -->
  
<span class="ms-2 fs-4 fw-bold">

  <?= $this->request->getParam('username'); ?>

</span>

<!-- nombre d'abonnés + bouton d'abonnement/désabonnement -->

<p class="ms-2 mt-2 fs-5">
    
  <?php

    // affichage du nombre d'abonnés

    echo $this->cell('Follow::countfollowers',[$this->request->getParam('username')]);

    // si je suis connecté et que je ne visite pas mon profil, affichage d'un test d'abonnement et de blocage

    if($authName AND $authName != $this->request->getParam('username'))
  {
    echo '&nbsp;•'.$this->cell('Follow::followstatus',[$authName, $this->request->getParam('username')]);

    echo '•'.$this->cell('Block::blockstatus',[$authName, $this->request->getParam('username'),'yes']);
  }

  ?>

</p>

<!-- barre de navigation par onglet -->

<ul class="nav nav-tabs">

  <li class="nav-item">

    <a class="nav-link  fs-4 active" id="videos" role="button" onclick="loadProfilItem('videos')">Vidéos</a>

  </li>

  <li class="nav-item">

    <a class="nav-link text-white fs-4" id="communaute" role="button" onclick="loadProfilItem('communaute')">Communauté</a>

  </li>

  <li class="nav-item">

    <a class="nav-link text-white fs-4" id="chaines" role="button" onclick="loadProfilItem('chaines')">Chaînes</a>

  </li>

  <li class="nav-item">

    <a class="nav-link text-white fs-4" id="apropos" role="button" onclick="loadProfilItem('apropos')">A propos</a>
    
  </li>

</ul>
