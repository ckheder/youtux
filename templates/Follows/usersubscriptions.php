<!-- usersubscription.php
Liste des abonnements du profil en cours de visite 
-->
<?php

    if(count($usersubscription) == 0) // si moi ou le profil que je visite ne suis personne, affichage d'un message
{
    ?>
    
    <div class="alert alert-primary nocomm" role="alert">

      <?= ($this->request->getParam('username') == $authName) ? "Vous ne suivez personne." : "Aucun abonnement pour cette chaîne." ;?>

    </div>

    <?php
}
else
{

    foreach ($usersubscription as $usersubscription): ?>
    
<div class="p-2 float-start text-center">

<!-- avatar utilisateur -->

  <img src="/youtux/users/<?= $usersubscription->following ?>/<?= $usersubscription->following ?>.jpg" class="rounded" alt="image utilisateur" width="103" height="103" title="Profil de <?= $usersubscription->following ;?>">

    <br />

    <p class="ms-2 mt-2">

      <a href="/youtux/<?= $usersubscription->following ?>" class="text-decoration-none text-white"><?= $usersubscription->following ;?></a>

    <br />

<!-- affichage du nombre d'abonnés -->

  <span class="text-secondary">

    <?= $this->cell('Follow::countfollowers',[$usersubscription->following]); ?>

  </span>

  </p>

  <?php

  // affichage de la cell des abonnements si je connecté et si le profil que je visite n'est pas le mien

    if($authName AND $this->request->getParam('username') != $authName)
{
    if($authName != $usersubscription->following) // si je ne fais pas partie des résultats de recherche, affichage de la cell de statut d'un abonnement
  {
    echo $this->cell('Follow::followstatus',[$authName, $usersubscription->following]); 
  }
}

   ?> 

</div>

<?php

  endforeach;

}

?>