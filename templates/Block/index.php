<!-- index.php
Liste des utilisateurs que j'ai bloqué
-->

<div id="mylistuserblocked">

  <?php

      if(count($userblocked) == 0) // si je ne suis personne
  {
    ?>
    
    <div class="alert alert-primary nocomm" role="alert">

    Vous n'avez bloqué aucun utilisateur.

    </div>

    <?php
  }
    else
  {

    foreach ($userblocked as $userblocked): ?>
    
    <div class="p-2 float-start text-center" id="<?= $userblocked->bloque ?>">

    <!-- avatar bloqué -->

      <img src="/youtux/users/<?= $userblocked->bloque ?>/<?= $userblocked->bloque ?>.jpg" class="d-inline rounded-circle me-1" alt="image utilisateur" width="103" height="103" title="Profil de <?= $userblocked->bloque ;?>">

      <p class="ms-2 mt-2 p-2">

    <!-- nom du bloqué -->

        <a href="/youtux/<?= $userblocked->bloque ?>" class="text-decoration-none text-white"><?= $userblocked->bloque ;?></a>

      <br />

      <br />

    <!-- description bloqué -->

  <span class="text-secondary">

          <!-- affichage du nombre d'abonné et du nombre de vidéos -->

          <?= $this->cell('Follow::countfollowers',[$userblocked->bloque]); ?>

              &nbsp;•&nbsp;

          <?= $this->cell('Movies::countmovies',[$userblocked->bloque]); ?>

      <br />

      <br />

  </span>

    <!-- bouton de déblocage -->

      <button type="button" class="btn btn-danger actionblock" data_userconcerned="<?= $userblocked->bloque; ?>" data_actionblock="delete">DEBLOQUER</button>

    </p>

</div>

<?php

  endforeach;

}

?>

</div>