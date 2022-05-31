<!-- index.php
Liste des vidéos du profil en cours de visite 
-->

<?php

  if(isset($unknownuser))
{
  ?>
  <div class="alert alert-primary" role="alert">
  Cette utilisateur n'existe pas.
        </div>

  <?php
}
else
{

$results = $movie->all();

    if($results->isEmpty())
{
    ?>
    
    <div class="alert alert-primary" role="alert">
    <?= ($this->request->getParam('username') == $authName) ? "Vous n'avez posté aucune vidéo." : "Aucune vidéo sur cette chaîne." ;?>
          </div>
    <?php
}
  else
{

  foreach ($movie as $movie): ?>

<!-- affichage des vidéos -->

<div class="p-3 float-start border border-1" style="width:345px; height:300px;">

<?php

  if($movie->type == 'released')
{
  ?>

  <video width="310" height="218">
  
    <source src="/youtux/users/<?= $this->request->getParam('username') ;?>/<?= $movie->filename?>" type="video/<?= pathinfo($movie->filename, PATHINFO_EXTENSION) ;?>">

      Your browser does not support the video tag.

  </video> 

  <br />

  <?php
}
else
{
  ?>
<video width="310" height="218" poster="/youtux/img/comingsoon.webp">
   
</video>
  <?php
}
?>
  

  <a href="/youtux/v/<?= $movie->id_movie ?>" class="text-decoration-none"><?= $movie->titre ?></a> <!-- titre + lien vers la vidéo pour lecture -->

<br />

  <?= $movie->nb_vues ?> vue(s)- <?= $movie->created->i18nformat('dd MMMM YYYY') ?> <!-- nombre de vue -->

</div>

<?php

  endforeach; }}


?>

