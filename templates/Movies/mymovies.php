<!-- mymovies.php
Liste de mes vidéos en vue d'une suppression ou d'une publication 
-->

<?php

  $result_movie = $movie->all(); // récupération des résultats

?>

  <h5 class="text-center"> <span class="nbmovies"><?= count($result_movie) ;?></span> vidéo(s)</h5> <!-- affichage du nombre de résultat -->

<div id="mymovieslist">

<?php

    if($result_movie->isEmpty()) // si je n'ais rien uploadé
{
    ?>
    
    <div class="alert alert-primary" role="alert">

      Aucune vidéo à afficher.

    </div>

    <?php
}
  else
{

  foreach ($movie as $movie): 
 
  ?>

<!-- affichage des vidéos -->

  <div id="movie<?= $movie->id_movie ?>">

    <span class="fs-5 fw-bold">

    <!-- titre + lien vers la vidéo pour lecture -->
    
      <a href="/youtux/v/<?= $movie->id_movie ?>" class="text-decoration-none text-white"><?= $movie->titre ?></a>

    </span>

<span class="text-secondary">

    <?= $movie->nb_vues ?> vue(s) - <?= $movie->created->i18nformat('dd MMMM YYYY') ?> <!-- nombre de vue + date de publication -->

</span>

<div class="float-end">

      <?php 

        if($movie->type == "unreleased") // si la vidéo n'a pas était publiée, affichage d'un bouton pour le faire
      {
        ?>

          <button type="button" class="btn btn-primary" id="releasedmovie<?= $movie->id_movie ;?>" onclick="releasedmovie(<?= $movie->id_movie ;?>)">Publier</button>

        <?php
      }

      ?>

  <!-- bouton de suppression de la vidéo -->

  <button type="button" id="removemovie<?= $movie->id_movie ;?>" class="btn btn-danger" onclick="deletemovie(<?= $movie->id_movie ;?>)">Supprimer cette vidéo</button>

    </div>

</div>

<hr>

<?php

  endforeach; }

?>

</div>