<!-- index.php
Liste de mes vidéos favorites
-->

<?php

  use Cake\I18n\FrozenTime;

?>

<div class="mt-3 mb-3 fs-5 text-center"><span id="nbnotif"><?= $this->Paginator->params()['count']; ?></span> notification(s)</div>

<div id="notificationslist">

<?php

    if($notifications->isEmpty()) // si je ne suis personne
{
    ?>
    
    <div class="alert alert-primary" role="alert">

      Vous n'avez aucune notifications pour le moment.

    </div>

    <?php
}
    else
{

  foreach ($notifications as $notifications): 

    $created = new FrozenTime($notifications->created); // conversion du timestamp en un format de date spécifique

    $created = $created->timeAgoInWords([
      'accuracy' => ['month' => 'month'],
      'end' => '1 year'
  ]);

  ?>

<!-- affichage des vidéos -->

  <div id="notification<?= $notifications->id_notification ?>">

<?= $notifications->notification_content ;?>

<span class="text-secondary">

&nbsp;<?= $created ?><!-- nombre de vue + date de publication -->

  </span>

  <div class="float-end">

  <!-- bouton de suppression du favori -->

  <button type="button" title="Supprimer cette notification" class="btn btn-danger" onclick="removenotification(<?= $notifications->id_notification ;?>)">x</button>

</div>

<hr>

</div>



<?php

  endforeach; }

?>

</div>
