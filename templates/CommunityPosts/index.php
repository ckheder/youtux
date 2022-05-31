<!-- index.php
Affichage des messages communautaires du profil en cours de visite
-->

<?php

use Cake\I18n\FrozenTime;

  if($this->request->getParam('username')== $authName) // affichage du formulaire pour poster des messages communutaires
{

  ?>

  <div class="pt-1">
          
    <?= $this->Form->create(null,['id' => 'formaddcommunitypost', 'url' => '/c/new']); ?>

    <?= $this->Form->textarea('communitymessage',['id' => 'content','class' =>'form-control','label' =>'','required','placeholder'=>'Envoyer un message à votre communauté','resize' => 'none']); ?>
    

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

  <div class="pt-1 text-center">

    <?= $this->Form->submit('Poster',['class' => 'btn btn-primary']); ?>

  </div>

    <?=  $this->Form->end(); ?>

  <hr>

  </div>

<?php

}

?>

<!-- affichage des message communautaire -->

<div id="communitymessagelist">

<?php
  
  if ($this->Paginator->params()['count'] == 0) // si 0 message communautaire -> affichage d'un message
{
    echo '<div class="alert alert-primary nocommunitymessage" role="alert">
            Aucun message pour le moment.
          </div>';
}
  else
{

  foreach ($communityPosts as $communityPosts): 

  $created = new FrozenTime($communityPosts->created); // conversion du timestamp en un format de date spécifique

  $created = $created->timeAgoInWords([ // affichage 'il y'a x temps'
    'accuracy' => 'day'
   
  ]);

?>

<!-- affichage des messages communautaires -->

<div id="communitypost<?= $communityPosts->id_community_post;?>" class="m-3">

  <?php

    if($authName) // si auth, menu déroulant
  {
    ?>

<div class="dropdown float-end">

  <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
    ...
  </button>

  <ul class="dropdown-menu">

  <?php
    
        if($communityPosts->username_community_post == $authName) // si je suis l'auteur message communautaire
      {
        ?>

          <li> <!-- lien de suppression du message communautaire -->
            
            <a class="deletecommunitypost dropdown-item" href="#" onclick="return false;" data_idcommunitypost="<?= $communityPosts->id_community_post ?>"> Supprimer</a>
          
          </li>

          <li> <!-- lien de modification du message communautaire -->
            
            <a class="updatecommunitypost dropdown-item" href="#" onclick="return false;" data_idcommunitypost="<?= $communityPosts->id_community_post ?>"> Modifier</a>
          
          </li>

          <?php

      }

           ?>
  </ul>

</div> 

<?php

}

?>

<!-- avatar de l'auteur du message communautaire -->

<img src="/youtux/users/<?= $communityPosts->username_community_post ;?>/<?= $communityPosts->username_community_post ?>.jpg" alt="image utilisateur" width="48" height="48" class="d-inline rounded-circle me-1">
   
  <span class="ms-2 fs-6">

<!-- nom de l'auteur du message communautaire et date de parution du message communautaire ainsi que de la mention 'modifié' si le message communautaire à était modifié -->

    <?= $communityPosts->username_community_post; ?> - <span class="text-secondary"> <?= $created ?> <?= ($communityPosts->created < $communityPosts->modified) ? " · modifié" : ''; ?></span>

  </span>

<!-- affichage du message communautaire -->

  <p id="communitycontent<?= $communityPosts->id_community_post ?>" class="mt-2"><?= $communityPosts->message_community_post; ?></p>

  <!-- nombre de commentaire -->

  <a href="/youtux/c/<?= $communityPosts->id_community_post ?>" class="text-decoration-none">

  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
  </svg> 

  <?= $communityPosts->nb_comm ;?>

</a>
  
  <hr>

</div>

<?php

  endforeach; }

?>

</div>

