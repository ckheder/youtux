<!-- edit.php
Modification des informations d'un utilisateur et configuration des notifications
-->

<div class="text-center">

    <h4>Paramètres de mon compte</h4>

</div>

<div class="row justify-content-start">

<div class="col-6">

  <!-- mes informations -->

<div class="text-center">

<h3>Mes informations</h3>

</div>

    <hr>

<!-- création du formulaire de mise à jour des informations -->

<?= $this->Form->create(null,['id' =>'form_settings','url' => ['controller' => 'Users', 'action' => 'edit'],['enctype' => 'multipart/form-data']]);?>

<!-- section avatar -->

<div class="mb-3">

  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
  </svg>
  
  Changer ma photo

</div>

<?= $this->Form->file('submittedfile',['id' => 'submittedfile']) ?> <!-- input avatar -->

<span class="text-secondary">(jpg/jpeg) 3mo maximum </span>

<div class="text-center">

  <p class="text-secondary">Prévisualisation</p>

  <!-- zone de preview -->

<?= $this->Html->image('default.png', ['alt' => '','id' => 'previewHolder', 'width' =>128, 'height'=> 'auto','class'=>'rounded-circle']); ?>

</div>

<hr>

<!-- input mot de passe -->

<div>

  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
    <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
    <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
  </svg>

  Votre mot de passe doit faire entre 8 et 20 caracères et les caractères spéciaux suivants sont autorisés : ~ ! @ # $% ^ & * ().

  <?= $this->Form->control('password',['class' =>'form-control ','label' =>'','id'=>'password','placeholder'=>'Nouveau mot de passe']); ?>

  <br />

  <input type="checkbox" onclick="displayPassword('password')"> Voir le mot de passe <!-- affichage du mot de passe -->

</div>

<br />  

<!-- input confirmation mot de passe -->

<div>
  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
    <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
    <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
  </svg>
  
  Confirmer mon nouveau mot de passe

  <?= $this->Form->control('confirmpassword',['type'=>'password','class' =>'form-control ','id'=>'confirmpassword','label' =>'','placeholder'=>'Confirmer nouveau mot de passe']); ?>

  <br />

  <input type="checkbox" onclick="displayPassword('confirmpassword')"> Voir le mot de passe <!-- affichage du mot de passe -->

</div>

<br />    

<!-- input adresse mail -->


<div>

  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
  </svg>
  
  Nouvelle adresse mail

  <?= $this->Form->control('email',['class' =>'form-control ','label' =>'','id'=>'mail','placeholder'=>'Nouvelle adresse mail']); ?>

</div>

<br />

<!-- input confirmation adresse mail -->

<div>
  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
  </svg>
  
  Confirmer ma nouvelle adresse mail

<?= $this->Form->control('confirmemail',['class' =>'form-control ','id' => 'confirmemail','label' =>'','placeholder'=>'Confirmer nouvelle adresse mail']); ?>    

</div>

<br />

<!-- input description -->

<div>
<svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-file-person" viewBox="0 0 16 16">
  <path d="M12 1a1 1 0 0 1 1 1v10.755S12 11 8 11s-5 1.755-5 1.755V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
  <path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
</svg>

Parler de moi

<br />

<br />

<?= $this->Form->textarea('description',['class' =>'form-control ','id' => 'description','label' =>'','placeholder'=>'Brève description de moi-même']); ?>

</div>

<br />

<!-- input lieu -->

<div>
<svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
  <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
</svg>

Localisation

<?= $this->Form->control('pays',['type' => 'text','class' =>'form-control ','id' => 'pays','label' =>'','placeholder'=>'Ex : Paris, New York, France,Canada,...']); ?>

</div>

<br />

    <div class="text-center">

            <?= $this->Form->button('Mise à jour',['class' =>'btn btn-primary m-3']) ?>

    </div>

            <?= $this->Form->end() ?>

         

</div>

<div class="col-6">

<div class="text-center">

<h3>Notifications</h3>

</div>

<hr>

<!-- case à cocher pour choisir si on veut recevoir des notifications de nouveaux commentaires -->

<svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
</svg>

<span class="text-secondary">Recevoir des notifications de nouveaux commentaires</span>

  <div class="form-check mt-3">

    <input class="form-check-input" type="radio" <?= ($notif_comm == 'oui') ? "checked" : ''; ?> onchange="setupnotif(this.name)" value="oui" name="notif_comm">

      <label class="form-check-label" for="flexCheckChecked">

        Oui

      </label>

  </div>

  <div class="form-check">

    <input class="form-check-input" type="radio" value="non" name="notif_comm" <?= ($notif_comm == 'non') ? "checked" : ''; ?> onchange="setupnotif(this.name)" value="non">

      <label class="form-check-label" for="flexCheckChecked">

        Non

      </label>

  </div>

<br />

<!-- case à cocher pour choisir si on veut recevoir des notifications de nouveaux abonnés -->

<svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
  <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
  <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
</svg>

<span class="text-secondary">Recevoir des notifications de nouveaux abonnement</span>

  <div class="form-check mt-3">

    <input class="form-check-input" type="radio" <?= ($notif_follow == 'oui') ? "checked" : ''; ?> onchange="setupnotif(this.name)" value="oui" name="notif_follow">

      <label class="form-check-label" for="flexCheckChecked">

        Oui

    </label>

  </div>

<div class="form-check">

  <input class="form-check-input" type="radio" <?= ($notif_follow == 'non') ? "checked" : ''; ?> onchange="setupnotif(this.name)" value="non" name="notif_follow">

    <label class="form-check-label" for="flexCheckChecked">

      Non

    </label>

</div>

<hr>

<!-- delete compte -->

<div class="text-center">

<h3>Supprimer mon compte</h3>

</div>

<div class="alert alert-primary" role="alert">

  Effacer votre compte supprimera définitivement toutes vos vidéos, commentaires et toutes vos données de profil sans possibilités de les récupérer par la suite.

    <br />

    <br />

  Rien ne sera conservée et vous pourrez toujours profiter des vidéos de Youtux par la suite.

</div>

  <div class="text-center">

    <a href="/youtux/deletemyaccount" onclick="return confirm(
                                                          'Etes vous sur de vouloir supprimer votre compte ? Cette action est irréversible.'
                                                          );"class="btn btn-danger">
    
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">

        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>

        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>

      </svg> Supprimer mon compte</a>

  </div>

</div>

</div>

</div>

</div>

<?= $this->Html->script('settings.js'); ?>  