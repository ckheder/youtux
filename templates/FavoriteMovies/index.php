<!-- index.php
Liste de mes vidéos favorites
-->

<?php

  use Cake\I18n\FrozenTime;

?>

<div id="favoritemovielist">

<?php

    if($favoriteMovies->isEmpty()) // si je ne suis personne
{
    ?>
    
    <div class="alert alert-primary" role="alert">

      Vous n'avez aucune vidéo favorite pour le moment.

    </div>

    <?php
}
    else
{

  foreach ($favoriteMovies as $favoriteMovies): 

    $datemovie = FrozenTime::parse($favoriteMovies->Movies['created']); // création du timestamp

    $created = $datemovie->i18nFormat('dd MMMM YYYY'); // conversion du timestamp en un format de date spécifique

    $extension = pathinfo($favoriteMovies->Movies['filename'], PATHINFO_EXTENSION); // récupération de l'extension de la vidéo

  ?>

<!-- affichage des vidéos -->

  <div class="d-flex align-items-start"  id="favorite<?= $favoriteMovies->favorite_movies ?>">

    <p>

<!-- avatar utilisateur -->

    <img src="/youtux/users/<?= $favoriteMovies->Movies['auteur'] ?>/<?= $favoriteMovies->Movies['auteur'] ?>.jpg" alt="image utilisateur" width="32" height="32" class="d-inline rounded-circle me-1">
  
<!-- nom utilisateur -->
  
    <a href="/youtux/<?= $favoriteMovies->Movies['auteur'] ?>" class="text-decoration-none text-white"><?= $favoriteMovies->Movies['auteur'] ?></a>

      <br />

<!-- affichage vidéo -->

  <video width="310" height="218" controls>
  
    <source src="/youtux/users/<?= $favoriteMovies->Movies['auteur'] ;?>/<?= $favoriteMovies->Movies['filename']?>" type="video/<?= $extension ;?>">

      Your browser does not support the video tag.

  </video> 

</p>

  <p class="mt-5 ms-3">

    <span class="fs-5 fw-bold">

    <!-- titre + lien vers la vidéo pour lecture -->
    
      <a href="/youtux/v/<?= $favoriteMovies->Movies['id_movie'] ?>" class="text-decoration-none text-white"><?= $favoriteMovies->Movies['titre'] ?></a>

    </span>

      <br />

<span class="text-secondary">

  <br />

    <?= $favoriteMovies->Movies['nb_vues'] ?> vue(s) - <?= $favoriteMovies->Movies['nb_comment'] ?> commentaire(s) - <?= $favoriteMovies->Movies['nb_like'] ?> j'aime(s) - <?= $created ?><!-- nombre de vue + date de publication -->

  <br />

  <br />

  </span>

  <!-- description video -->

    <?= $favoriteMovies->Movies['description'];?>

    <br />

    <br />

  <!-- bouton de suppression du favori -->

    <button type="button" class="btn btn-primary" onclick="removefavoritemovie(<?= $favoriteMovies->favorite_movies ;?>,<?= $favoriteMovies->id_favorite_movies ;?>)">Supprimer ce favori</button>

  </p>

</div>

<hr>

<?php

  endforeach; }

?>

</div>
