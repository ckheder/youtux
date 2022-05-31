<!-- Community Post view 

Affichage d'un message communautaire et de ses message communautaires 

-->

<?php

use Cake\I18n\FrozenTime;
  
?>

<div class="container mt-4 p-3">

<div class="row justify-content-start">

    <div class="col-10">

<?php


$created = new FrozenTime($communityPost->created); // conversion du timestamp en un format de date spécifique

$created = $created->timeAgoInWords([ // affichage 'il y'a x temps'
  'accuracy' => 'day'
 
]);

  if($communityPost->username_community_post == $authName) // si je suis l'auteur du message communautaire
{
?>

<div class="dropdown float-end">

  <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
    ...
  </button>

  <ul class="dropdown-menu">

          <li> <!-- lien de suppression du message communautaire -->
            
            <a class="deletecommunitypost dropdown-item" href="#" onclick="return false;" data_idcommunitypost="<?= $communityPost->id_community_post ?>"> Supprimer</a>
          
          </li>

  </ul>

</div>

<?php

}

     ?>

<!-- avatar de l'auteur du message communautaire -->

<img src="/youtux/users/<?= $communityPost->username_community_post ;?>/<?= $communityPost->username_community_post ?>.jpg" alt="image utilisateur" width="48" height="48" class="d-inline rounded-circle me-1">
   
  <span class="ms-2 fs-6">

<!-- nom de l'auteur du message communautaire et date de parution du message communautaire ainsi que de la mention 'modifié' si le message communautaire à était modifié -->

  <a href="/youtux/<?= $communityPost->username_community_post; ?>" class="text-decoration-none"><?= $communityPost->username_community_post; ?></a> - <span class="text-secondary"> <?= $created ?> <?= ($communityPost->created < $communityPost->modified) ? " · modifié" : ''; ?></span>

  </span>

<!-- affichage du message communautaire -->

  <p class="mt-2"><?= $communityPost->message_community_post; ?></p>

  <hr>

  <?php // formulaire ajout commentaire si auth et non bloqué

  if($authName)
{

    if(!isset($userblocked)) // si cette variable n'existe pas, affichage du formulaire de commentaire
  {
    echo $this->Form->create(null,['id' => 'formcommunitycomment', 'url' => '/c/comments/newcommunitycomment']);

    echo $this->Form->text('comment',['id' => 'content','class' =>'form-control','label' =>'','required','placeholder'=>'Ajouter un commentaire en tant que '.$authName.'']);

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

    else // affichage d'un message comme quoi l'auteur de la vidéo m'a bloqué
  {
    echo '<div class="alert alert-danger" role="alert">
          '.$communityPost->username_community_post.' vous à bloqué, vous ne pouvez pas commenter ce post communautaire.
          </div>';
  }

}
  else // message indiquant que je dois me connecter pour commenter
{
  echo '<div class="alert alert-primary" role="alert">
          Vous devez vous <a href="http://localhost/youtux/login?redirect=%2Fc%2F'.$this->request->getParam('idcommunitypost').'">connecter</a> pour commenter cette vidéo.
        </div>';
}

?>

<!-- zone commentaire -->

<!-- Div qui contiendra les commentaires chargés en AJAX -->

<div id="community_comments_zone">

  <div class="spinner-border text-success visually-hidden" role="status"></div> <!-- spinner de chargement -->

</div>

</div>

</div>

</div>

<script>

const idcommunitypost = "<?= $this->request->getParam('idcommunitypost') ;?>"; // id de la video en cours de visite

const authcommunitypost = "<?= $communityPost->username_community_post;?>"; // auteur du post communautaire

</script>

<?= $this->Html->script('communityposts.js'); ?>
