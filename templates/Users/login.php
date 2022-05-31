<!-- login.php
Formulaire de connexion et d'inscription en cas de tentative d'accès à une page nécessitant une authentification -->

<div class="container bg-light mt-4 p-3">

    <div class="row justify-content-start">

    <!-- connexion -->

        <div class="col-6 text-center text-dark">

            <h4>Connexion</h4>

            <?= $this->Form->create();

            ?>

            <?= $this->Form->control('username',['class' =>'form-control','label' =>'','id'=>'username','placeholder' =>'nom d\'utilisateur']) ?>

    <br />

            <?= $this->Form->password('password',['class' =>'form-control','label' =>'','id'=>'password','placeholder' =>'mot de passe']) ?>

    <br  />

            <div class="form-check">

                <?= $this->Form->control('remember_me', ['class' =>'form-check-input','type' => 'checkbox','id' =>'flexCheckDefault']); ?>

            </div>


            <div class="text-center">

                <button class="btn btn-primary m-3">Connexion</button>

            </div>

            <?= $this->Form->hidden('origin', ['value' => $this->request->getParam('redirect')]) ?>

<?= $this->Form->end() ?>

        <p>

          Mot de passe <a href="#">oublié?</a>
    
        </p>
        
</div>

<!-- zone inscription -->

    <div class="col-6 text-dark">

        <h4 class="text-center">Pas de compte ? Inscrivez-vous gratuitement !</h4>

        <?= $this->Form->create(null,['url' => '/register']) ?>

        <?= $this->Form->control('username',['class' =>'form-control','label' =>'','placeholder'=>'nom d\'utilisateur']); ?>

        <div id="usernamedHelpBlock" class="form-text p-1">

            Entre 5 et 20 caractères, les caractères spéciaux ne sont pas autorisés

        </div>

        <?= $this->Form->control('password',['class' =>'form-control','label' =>'','placeholder'=>'mot de passe']); ?>

        <div id="passwordHelpBlock" class="form-text p-1">

            Votre mot de passe doit faire entre 8 et 20 caractères, contenir des lettres et des chiffres, et ne doit pas avoir d'espace ni de caeactères spéciaux.

        </div>

        <?= $this->Form->control('email',['class' =>'form-control','label' =>'','placeholder'=>'adresse mail : name@example.com']); ?>

        <div id="emailHelp" class="form-text p-1">

            Nous ne partagerons votre adresse mail avec personne.

        </div>

        <?= $this->Form->control('description',['class' =>'form-control','label' =>'','placeholder'=>'Parler de moi','style' =>'resize:none']); ?>

        <div id="descriptionHelp" class="form-text p-1">
        
            Parler un peu de vous (Facultatif).

        </div>

        <?= $this->Form->control('pays',['class' =>'form-control','label' =>'','placeholder'=>'Mon pays','type' =>'text']); ?>

        <div id="paysHelp" class="form-text p-1">
        
            D'où venez-vous (Facultatif).
        
        </div>

        <div class="text-center">

            <?= $this->Form->button(__('Inscription'),['class' =>'btn btn-primary m-3']) ?>

        </div>

        <div id="registerinfo" class="form-text p-1">
            
            En cliquant sur Inscription, vous acceptez nos Conditions générales d'utilisation.
        
        </div>

        <?= $this->Form->end() ?>

        </div> 

    </div>

</div>