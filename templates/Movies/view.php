<!-- Movie view 

Affichage d'une vidéo et de ses commentaires 

-->

<div class="container  mt-4 p-3">

<div class="row justify-content-start">

    <div class="col-10">

<!-- affichage de la vidéo -->

<?php

  if($movie->type == 'released') // si la vidéo à était publié, affichage de celle-ci
{

?>

<video width="1074" height="672" controls>
  
  <source src="/youtux/users/<?= $movie->auteur ;?>/<?= $movie->filename?>" type="video/<?= pathinfo($movie->filename, PATHINFO_EXTENSION) ;?>">

  Your browser does not support the video tag.

</video> 

<?php

}
  else // affichage d'une image indiquant que la vidéo n'a pas était encore publié par son auteur
{
  ?>
    <video width="1074" height="672" poster="/youtux/img/comingsoon.webp">

      Your browser does not support the video tag.
   
   </video>

    <?php
}

?>

<!-- information de la vidéo -->

<p class="fs-3">

  <?= $movie->titre ?> <!-- titre -->

</p>

<p class="fs-5">

  <?= $movie->description ?> <!-- description -->

</p> 

<p class="text-secondary">

  <?= $movie->nb_vues ?> vue(s) - <span class="nblike"><?= $movie->nb_like ?></span> j'aime(s) - Ajouté le <?= $movie->created->i18nformat('dd MMMM YYYY'); ?> <!-- nombre de vue et date d'ajout -->

</p>

<hr>

<div class="float-end">

<?php

  if($movie->auteur == $authName) // si je suis l'auteur de la vidéo : affichage d'un bouton d'option
{
  ?>
    <div class="dropdown">

      <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">

        Options
    
      </button>

  <ul class="dropdown-menu"><!-- liste des options : activer/désactiver les commentaires et supprimée une vidéo -->

    <li>

      <a class="dropdown-item" id="actioncomm" data-idmovie ="<?= $movie->id_movie ;?>" data-actioncomm = "<?= ($movie->allow_comment == 0) ? 1 : 0 ;?>" href="#" onclick="return false;"> <?= ($movie->allow_comment == 0) ? 'Désactiver les commentaires' : 'Activer les commentaires' ;?></a></li>

    <li>

      <a class="dropdown-item" onclick="deletemovie(<?= $movie->id_movie ;?>)" href="#">Supprimer cette vidéo</a>

    </li>

  </ul>

</div> 

<?php

}
  else // sinon un bouton d'abonnement, uniquement si je suis connecté

{

    if($authName)
  {
    echo $this->cell('Follow::followstatus',[$authName, $movie->auteur]);

    echo $this->cell('Movies::checkfavoritemovie',[$authName, $movie->id_movie]);
  }
  
}

?>

</div>

<!-- avatar de l'utilisateur -->

<img src="/youtux/users/<?= $movie->auteur ;?>/<?= $movie->auteur ?>.jpg" alt="image utilisateur" width="88" height="88" class="d-inline rounded-circle me-1">

<!-- nom de l'utilisateur -->
    
<span class="ms-2 fs-4 fw-bold">

  <a href="/youtux/<?= $movie->auteur; ?>" class="text-decoration-none"><?= $movie->auteur; ?></a>

</span>

<!-- nombre d'abonné(s) de l'utilisateur -->

  <p class="ms-2 mt-2 fs-6 text-secondary">
    
  <?= $this->cell('Follow::countfollowers',[$movie->auteur]); ?>

  </p>

<hr>

<?php

  if($movie->allow_comment == 1) // si les commentaires sont désactivés
{
  ?>

    <div class="alert alert-danger" role="alert">

              Les commentaires sont désactivés pour cette vidéo.

    </div>
    
  <?php
}
  else
{

// formulaire ajout commentaire si auth et non bloqué

      if($authName)
    {

        if(!isset($userblocked)) // si cette variable n'existe pas , affichage d'un formulaire pour commenter cette vidéo
      {
        echo $this->Form->create(null,['id' => 'formcomment', 'url' => '/v/newcomment']);

        echo $this->Form->text('comment',['id' => 'content','class' =>'form-control','label' =>'','required','placeholder'=>'Ajouter un commentaire en tant que '.$authName.'',($movie->allow_comment != 1) ? '' : 'disabled']);

        echo $this->Form->hidden('allowcomm',['id' => 'allowcomm','value' => $movie->allow_comment]);

      ?>

    <!-- affichage emoji -->

    <div class="dropdown">

      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

        <img src='/youtux/img/emoji/smile.png' style="width:30px;height:24px;" data_code='$img'>
        
      </button>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

          <?php // parcours du dossier contenant les emojis et affichage dans la div

            $dir = WWW_ROOT . 'img/emoji'; // chemin du dossier

            $iterator = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);

            foreach($iterator as $file)
          {
            $img = $file->getFilename();

            echo "<img src='/youtux/img/emoji/$img' class='emoji' style='width:30px;height:24px;' data_code='$img'>";
          }

        ?>

      </div>

</div>

        <div id="commentHelp" class="form-text p-1">
              
              Appuyez sur Entrée pour envoyer votre commentaire.
          
            </div>

            <?php

      echo $this->Form->end();
    }
      else // message de blocage
    {
      echo '<div class="alert alert-danger" role="alert">
              '.$movie->auteur.' vous à bloqué, vous ne pouvez pas commenter cette vidéo.
            </div>';
    }
  }
      else // message indiquant une connexion nécessaire pour pouvoir commenter
    {
      echo '<div class="alert alert-primary" role="alert">
              Vous devez vous <a href="http://localhost/youtux/login?redirect=%2Fv%2F'.$this->request->getParam('idmovie').'">connecter</a> pour commenter cette vidéo.
            </div>';
    }
  }

?>

<!-- zone commentaire -->

<!-- Div qui contiendra les commentaires chargés en AJAX -->

<div id="comments_zone">

  <div class="spinner-border text-success visually-hidden" role="status"></div> <!-- spinner de chargement -->

</div>

</div>

</div>

</div>

<!-- fin -->

<script>

  const idvideo = "<?= $this->request->getParam('idmovie') ;?>"; // id de la video en cours de visite

  const allowcomm = "<?= $movie->allow_comment;?>"; // état d'autorisation ou non des commentaires

  const authvideo = "<?= $movie->auteur;?>"; // auteur de la vidéo

</script>

<?= $this->Html->script('videopage.js'); ?>

