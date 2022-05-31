<!-- add.php
Formulaire d'inscription d'un nouvel utilisateur -->

<?php

use \App\Model\Entity\User;

$user = new User; // utilisé dans le formulaire d'inscription pour bénéficier de la validation du modèle User

?>

<div class="container bg-light mt-4 p-3 text-dark">

    <div class="row justify-content-start">

        <div class="col-5">

        <h4>Inscription</h4>

            <?= $this->Form->create($user) ?>

            <?= $this->Form->control('username',['class' =>'form-control','label' =>'','placeholder'=>'nom d\'utilisateur','required']); ?>

            <div id="usernamedHelpBlock" class="form-text p-1">

              Entre 5 et 20 caractères, les caractères spéciaux ne sont pas autorisés

            </div>

            <?= $this->Form->control('password',['id'=> 'password','class' =>'form-control','label' =>'','placeholder'=>'mot de passe','required']); ?>

            <div id="passwordHelpBlock" class="form-text p-1">

            Votre mot de passe doit faire entre 8 et 20 caracères et les caractères spéciaux suivants sont autorisés : ~ ! @ # $% ^ & * ().

            </div>

            <input type="checkbox" onclick="displayPassword('password')"> Voir le mot de passe <!-- affichage du mot de passe -->

            <?= $this->Form->control('email',['class' =>'form-control','label' =>'','placeholder'=>'adresse mail : name@example.com','required']); ?>

            <div id="emailHelp" class="form-text p-1">
              
              Nous ne partagerons votre adresse mail avec personne.
            
            </div>

            <?= $this->Form->control('description',['class' =>'form-control','label' =>'','placeholder'=>'Parler de moi','style' =>'resize:none','required']); ?>

            <div id="descriptionHelp" class="form-text p-1">
              
              Parler un peu de vous.
          
            </div>

            <?= $this->Form->control('pays',['class' =>'form-control','label' =>'','placeholder'=>'Mon pays, Ma ville','type' =>'text','required']); ?>

            <div id="paysHelp" class="form-text p-1">
              
              D'où venez-vous.
          
            </div>

            <div class="text-center">

              <?= $this->Form->button(__('Inscription'),['class' =>'btn btn-primary m-3']) ?>

            </div>

            <div id="registerinfo" class="form-text p-1">En cliquant sur Inscription, vous acceptez nos Conditions générales d'utilisation.</div>

            <?= $this->Form->end() ?>

</div>

<div class="col-7">

<h4 class="text-center">Rejoindre Youtux</h4>

    <ul class="p-3 list-unstyled">

      <li class="mb-4">
        
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-camera-reels-fill" viewBox="0 0 16 16">
      <path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
      <path d="M9 6a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
      <path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h7z"/>
      </svg> 
      
      Crée votre profil et partagez vos vidéos.
    
    </li>
      <li class="mb-4">

        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
        <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
        </svg> 
        
        Aimer des vidéos.
      
      </li>

      <li class="mb-4">

        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
        </svg> 
        
        Commenter des vidéos.
      
      </li>

      <li class="mb-4">
        
      <img src="img/director.svg" alt="" width="30" height="24" class="d-inline-block"> Suivre des auteurs.
    
      </li>

    </ul>

    </div>

  </div>

</div>
