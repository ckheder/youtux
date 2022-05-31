<!-- subscriptions.php
Liste des vidéos de mes abonnements 
-->

<?php

    if($subscriptions->isEmpty()) // si je ne suis personne
{
    ?>
    
    <div class="alert alert-primary" role="alert">

      Vous ne suivez aucune chaîne pour le moment ou vous suivez des utilisateurs n'ayant posté aucune vidéo pour le moment.

    </div>

    <?php
}
else
{

  ?>

  <div class="float-end"><a href="/youtux/f/list"><button type="button" class="btn btn-primary">Gérer</button></a></div>

  <?php

  foreach ($subscriptions as $subscriptions): 
 
    $created = $subscriptions->created->i18nformat('dd MMMM YYYY'); // conversion du timestamp en un format de date spécifique

    $extension = pathinfo($subscriptions->filename, PATHINFO_EXTENSION); // récupération de l'extension de la vidéo

  ?>

<!-- affichage des vidéos -->

  <div class="d-flex align-items-start" style="width:600px;height:250px">

    <p>

<!-- avatar utilisateur -->

    <img src="users/<?= $subscriptions->auteur ?>/<?= $subscriptions->auteur ?>.jpg" alt="image utilisateur" width="32" height="32" class="d-inline rounded-circle me-1">
  
<!-- nom utilisateur -->
  
<a href="/youtux/<?= $subscriptions->auteur ?>" class="text-decoration-none text-white"><?= $subscriptions->auteur ?></a>

<br />

<!-- affichage vidéo -->

  <video width="310" height="218" controls>
  
    <source src="/youtux/users/<?= $subscriptions->auteur ;?>/<?= $subscriptions->filename?>" type="video/<?= $extension ;?>">

      Your browser does not support the video tag.

  </video> 

</p>

  <p class="mt-5 ms-3">

    <span class="fs-5 fw-bold">

    <!-- titre + lien vers la vidéo pour lecture -->
    
      <a href="/youtux/v/<?= $subscriptions->id_movie ?>" class="text-decoration-none text-white"><?= $subscriptions->titre ?></a>

    </span>

<br />

<span class="text-secondary">

  <br />

    <?= $subscriptions->nb_vues ?> vue(s) - <?= $created ?> <!-- nombre de vue + date de publication -->

  <br />

  <br />

  </span>

  <!-- description video -->

    <?= $subscriptions->description;?>

</p>

</div>

<hr>

<?php

  endforeach; }

?>