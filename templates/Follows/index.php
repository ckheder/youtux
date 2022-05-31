<!-- index.php
Liste de mes abonnements
-->

<div id="mysubscriptions">

  <?php

      if(count($mysubscriptions) == 0) // si je ne suis personne
  {
    ?>
    
    <div class="alert alert-primary nocomm" role="alert">

    Vous ne suivez aucune chaîne pour le moment.

    </div>


    <?php
  }
    else
  {

    foreach ($mysubscriptions as $mysubscriptions): ?>
    
    <div class="p-2 float-start text-center" id="<?= $mysubscriptions->following ?>">

    <!-- avatar abonnement -->

      <img src="/youtux/users/<?= $mysubscriptions->following ?>/<?= $mysubscriptions->following ?>.jpg" class="d-inline rounded-circle me-1" alt="image utilisateur" width="103" height="103" title="Profil de <?= $mysubscriptions->following ;?>">

      <p class="ms-2 mt-2 p-2">

    <!-- nom de l'abonnement -->

      <span class="text-secondary">

        <a href="/youtux/<?= $mysubscriptions->following ?>" class="text-decoration-none text-white"><?= $mysubscriptions->following ;?></a>

      <br />

      <br />

    <!-- affichage du nombre d'abonné de mon abonnment -->

        <?= $this->cell('Follow::countfollowers',[$mysubscriptions->following]); ?>

          &nbsp;•&nbsp;

        <?= $this->cell('Movies::countmovies',[$mysubscriptions->following]); ?>

      <br />

      <br />

      </span>

      <br />

      <br />

    <!-- bouton de désabonnement -->

      <button type="button" class="btn btn-danger actionfollow" data_userconcerned="<?= $mysubscriptions->following; ?>" data_actionfollow="delete">SE DESABONNER</button>

    </p>

</div>

<?php

  endforeach;

}

?>

</div>