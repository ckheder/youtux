<!-- subscriptions.php
Liste des vidéos de mes abonnements 
-->

<?php

    if($moviecat->isEmpty()) // si je ne suis personne
{
    ?>
    
    <div class="alert alert-primary" role="alert">

      Aucune vidéo trouvée pour cette catégorie.

    </div>

    <?php
}
else
{


  ?>

<p class="fs-4 text-center"><?= count($moviecat) ?> vidéo(s) dans la catégorie <?= $this->request->getParam('channel');?>
  
  <div class="accordion-item" style="width: 20%;">
    <h2 class="accordion-header" id="flush-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-left" viewBox="0 0 16 16">
  <path d="M2 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
</svg> Filtres
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">

      <a href="/youtux/v/channel/<?= $this->request->getParam('channel');?>?sort=created&direction=desc" id="linkmostrecent" class="text-decoration-none">Voir les résultats les plus récents</a>

      <br />

      <br />

      <a href="/youtux/v/channel/<?= $this->request->getParam('channel');?>?sort=created&direction=asc" id="linkmostancient" class="text-decoration-none">Voir les résultats les plus anciens</a>
  
      </div>
    </div>
  </div>
  

</p>
  <hr>

<?php
  foreach ($moviecat as $moviecat): 
 
    $created = $moviecat->created->i18nformat('dd MMMM YYYY'); // conversion du timestamp en un format de date spécifique

    $extension = pathinfo($moviecat->filename, PATHINFO_EXTENSION); // récupération de l'extension de la vidéo

  ?>

<!-- affichage des vidéos -->

  <div class="d-flex align-items-start" style="width:600px;height:250px">

    <p>

<!-- avatar utilisateur -->

    <img src="/youtux/users/<?= $moviecat->auteur ?>/<?= $moviecat->auteur ?>.jpg" alt="image utilisateur" width="32" height="32" class="d-inline rounded-circle me-1">
  
<!-- nom utilisateur -->
  
<a href="/youtux/<?= $moviecat->auteur ?>" class="text-decoration-none text-white"><?= $moviecat->auteur ?></a>

<br />

<!-- affichage vidéo -->

  <video width="310" height="218" class="pe-none" tabindex="-1" aria-disabled="true">
  
    <source src="/youtux/users/<?= $moviecat->auteur ;?>/<?= $moviecat->filename?>" type="video/<?= $extension ;?>">

      Your browser does not support the video tag.

  </video> 

</p>

  <p class="mt-5 ms-3">

    <span class="fs-5 fw-bold">

    <!-- titre + lien vers la vidéo pour lecture -->
    
      <a href="/youtux/v/<?= $moviecat->id_movie ?>" class="text-decoration-none text-white"><?= $moviecat->titre ?></a>

    </span>

<br />

<span class="text-secondary">

  <br />

    <?= $moviecat->nb_vues ?> vue(s) - <?= $created ?> <!-- nombre de vue + date de publication -->

  <br />

  <br />

  </span>

  <!-- description video -->

    <?= $moviecat->description;?>

</p>

</div>

<hr>

<?php

  endforeach; }

?>

<script>

  var sorturl = "<?= $this->request->getQuery('direction');?>";

  </script>