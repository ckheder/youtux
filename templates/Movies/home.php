<!-- home.php
Liste des vidéos de tous
-->

<?php

  use Cake\I18n\FrozenTime;

$results = $movie->all();

  foreach ($movie as $movie): 
  
    $created = new FrozenTime($movie->created); // conversion du timestamp en un format de date spécifique

    $created = $created->timeAgoInWords([
                                          'accuracy' => ['month' => 'month'],
                                          'end' => '1 year'
                                        ]);
  
  ?>

<!-- affichage des vidéos -->

<div class="p-3 float-start" style="width:345px;">

  <video width="335" height="188">
  
    <source src="/youtux/users/<?= $movie->auteur ;?>/<?= $movie->filename?>" type="video/<?= pathinfo($movie->filename, PATHINFO_EXTENSION) ;?>">

      Your browser does not support the video tag.

  </video> 

  <img src="/youtux/users/<?= $movie->auteur ;?>/<?= $movie->auteur ?>.jpg" alt="image utilisateur" width="48" height="48" class="d-inline rounded-circle me-2">

<!-- nom de l'utilisateur -->

<span class="fw-bold">

  <a href="/youtux/v/<?= $movie->id_movie ?>" class="text-decoration-none link-light"><?= $movie->titre ?></a> <!-- titre + lien vers la vidéo pour lecture -->

</span>

<br />

<p class="mt-2 text-secondary">

  <a href="/youtux/<?= $movie->auteur; ?>" class="text-decoration-none"><?= $movie->auteur; ?></a>

  <br />

  <?= $movie->nb_vues ?> vue(s) - il y'a <?= $created ;?> <!-- nombre de vue -->

</p>

</div>

<?php

  endforeach; 

?>