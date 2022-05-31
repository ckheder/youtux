<!-- view.php
Liste des commentaires d'un message communautaire
-->

<?php

use Cake\I18n\FrozenTime;
  
?>

<hr>

<!-- affichage du nombre de commentaire -->

<div class="mt-3 mb-3 fs-5 text-center"><span id="nb_community_comment"><?= $this->Paginator->params()['count']; ?></span> commentaire(s)</div>

  <hr class="startcommunitycomment">

<?php

  if ($this->Paginator->params()['count'] == 0) // si 0 commentaire -> affichage d'un message
{
    echo '<div class="alert alert-primary nocommunitycomment" role="alert">
            Aucun commentaire pour ce message communautaire.
          </div>';
}
  else
{

  foreach ($communitycomments as $communitycomments): 

  $created = new FrozenTime($communitycomments->created); // conversion du timestamp en un format de date spécifique

  $created = $created->timeAgoInWords([ // affichage 'il y'a x temps'
    'accuracy' => 'day'
   
  ]);

?>

<!-- affichage des commentaires -->

<div id="communitycomments<?= $communitycomments->id_community_comment;?>">

<?php

  if($authName) // si non auth, pas de menu déroulant
{

?>

  <div class="dropdown float-end">

    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">

    </button>

  <ul class="dropdown-menu">

  <?php

        // si je suis l'auteur du commentaire communautaire ou l'auteur du message communautaire en cours de visite : affichage d'un lien de suppression du commentaire communautaire
    
        if($communitycomments->username_community_comment == $authName OR $communitycomments->CommunityPosts['username_community_post'] == $authName)
      {
        ?>

          <li> <!-- lien de suppression du commentaire -->
            
            <a class="deletecommunitycomment dropdown-item" href="#" onclick="return false;" data_idcommunitycomment="<?= $communitycomments->id_community_comment ?>"> Supprimer</a>
          
          </li>

          <?php

          if($communitycomments->username_community_comment == $authName) // si je suis l'auteur du commentaire communautaire, ajout d'un lien de modification
        {
          ?>

          <li>
            
            <a class="updatecommunitycomment dropdown-item" href="#" onclick="return false;" data_idcommunitycomment="<?= $communitycomments->id_community_comment ?>"> Modifier</a>
          
          </li>

        <?php

        }

      }

          if($communitycomments->username_community_comment != $authName) // si je ne suis ni l'auteur du commentaire communautaire ni l'auteur du message communautaire en cours de visite
        {

          ?>

          <li> <!-- ajout d'un lien de blocage -->
            <a class="actionblock dropdown-item" href="" onclick="return false;" data_actionblock ="add" data_userconcerned="<?= $communitycomments->username_community_comment ?>">Bloquer <?= $communitycomments->user_comm ?></a>
          </li>

          <li> <!-- ajout d'un lien de signalement -->
            
            <a class="signalcommunitycomment dropdown-item" href="" onclick="return false;"> Signaler</a>
          
          </li>

          <?php

        }

           ?>
  </ul>

</div> 

<?php

}

?>

<!-- avatar de l'auteur du commentaire communautaire -->

<img src="/youtux/users/<?= $communitycomments->username_community_comment ;?>/<?= $communitycomments->username_community_comment ?>.jpg" alt="image utilisateur" width="48" height="48" class="d-inline rounded-circle me-1">
   
  <span class="ms-2 fs-6">

<!-- nom de l'auteur du commentaire communautaire et date de parution du commentaire communautaire ainsi que de la mention 'modifié' si le commentaire à était modifié -->

  <a href="/youtux/<?= $communitycomments->username_community_comment; ?>" class="text-decoration-none"><?= $communitycomments->username_community_comment; ?></a> <span class="text-secondary"> <?= $created ?> <?= ($communitycomments->created < $communitycomments->modified) ? " · modifié" : ''; ?></span>

  </span>

<!-- affichage du commentaire communautaire -->

  <p id="communitycommentcontent<?= $communitycomments->id_community_comment ?>" class="mt-2"><?= $communitycomments->community_comment; ?></p>
  
  <hr>

</div>

<?php

  endforeach; }

?>
