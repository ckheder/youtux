<!-- view.php
Liste des commentaires d'une vidéo
-->

<?php

use Cake\I18n\FrozenTime;

?>

<hr>

<!-- affichage du nombre de commentaire -->

<div class="mt-3 mb-3 fs-5 text-center"><span id="nb_comm"><?= $this->Paginator->params()['count']; ?></span> commentaire(s)</div>

  <hr class="testcomm">

<?php

  if ($this->Paginator->params()['count'] == 0) // si 0 commentaire -> affichage d'un message
{
    echo '<div class="alert alert-primary nocomm" role="alert">
            Aucun commentaire pour cette vidéo.
          </div>';
}
  else
{

  foreach ($comments as $comments): 

  $created = new FrozenTime($comments->created); // conversion du timestamp en un format de date spécifique

  $created = $created->timeAgoInWords([ // affichage 'il y'a x temps'
    'accuracy' => 'day'
   
  ]);

?>

<!-- affichage des commentaires -->

<div id="comm<?= $comments->id_comm;?>">

<?php

  if($authName) // si non auth, pas de menu déroulant
{
  
?>

<div class="dropdown float-end">

  <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">

  </button>

  <ul class="dropdown-menu">

  <?php
    
        if($comments->user_comm == $authName OR $comments->Movies['auteur'] == $authName) // si je suis l'auteur du commentaire ou l'auteur de la vidéo
      {
        ?>

          <li> <!-- lien de suppression du commentaire -->
            
            <a class="deletecomm dropdown-item" href="#" onclick="return false;" data_idcomm="<?= $comments->id_comm ?>"> Supprimer</a>
          
          </li>

          <?php

          if($comments->user_comm == $authName) // si je suis l'auteur du commentaire, ajout d'un lien de modification
        {
          ?>

          <li>
            
            <a class="updatecomment dropdown-item" href="#" onclick="return false;" data_idcomm="<?= $comments->id_comm ?>"> Modifier</a>
          
          </li>

        <?php

        }

      }

          if($comments->user_comm != $authName) // si je ne suis ni l'auteur du commentaire ni l'auteur de la vidéo
        {

          ?>

          <li> <!-- ajout d'un lien de blocage -->
            
            <a class="actionblock dropdown-item" href="" onclick="return false;" data_actionblock ="add" data_userconcerned="<?= $comments->user_comm ?>">Bloquer <?= $comments->user_comm ?></a>
          
          </li>

          <li> <!-- ajout d'un lien de signalement -->
            
            <a class="signalcomm dropdown-item" href="" onclick="return false;"> Signaler</a>
          
          </li>

          <?php

        }

           ?>
  </ul>

</div> 

<?php

}

?>

<!-- avatar de l'auteur du commentaire -->

<img src="/youtux/users/<?= $comments->user_comm ;?>/<?= $comments->user_comm ?>.jpg" alt="image utilisateur" width="48" height="48" class="d-inline rounded-circle me-1">
   
  <span class="ms-2 fs-6">

<!-- nom de l'auteur du commentaire et date de parution du commentaire ainsi que de la mention 'modifié' si le commentaire à était modifié -->

  <a href="/youtux/<?= $comments->user_comm; ?>" class="text-decoration-none"><?= $comments->user_comm; ?></a> <span class="text-secondary"> <?= $created ?> <?= ($comments->created < $comments->modified) ? " · modifié" : ''; ?></span>

  </span>

<!-- affichage du commentaire -->

  <p id="commcontent<?= $comments->id_comm ?>" class="mt-2"><?= $comments->commentaire; ?></p>
  
  <hr>

</div>

<?php

  endforeach; }

?>
