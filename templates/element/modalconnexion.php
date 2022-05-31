<!-- modal connexion

Fenêtre modale de connexion 

-->

<div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
  <div class="modal-dialog">

     <div class="modal-content bg-dark text-white">

      <div class="modal-header">

        <h5 class="modal-title w-100 text-center" id="exampleModalLabel"><img src="/youtux/img/logo.png" alt="" width="30" height="24"> Se connecter à Youtux</h5>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        
      </div>

      <div class="modal-body ">

  <!-- formulaire de connexion -->

      <?= $this->Form->create(null, [
                                'url' => '/login'
                              ]);

      ?>

      <?= $this->Form->control('username',['class' =>'form-control','label' =>'','placeholder' =>'nom d\'utilisateur']) ?>

    <br />

      <?= $this->Form->password('password',['class' =>'form-control','label' =>'','placeholder' =>'mot de passe']) ?>

    <br  />

    <div class="form-check">

        <?= $this->Form->control('remember_me', ['class' =>'form-check-input','type' => 'checkbox','id' =>'flexCheckDefault']); ?>

                            </div>

    <div class="text-center">

          <button class="btn btn-primary">Connexion</button>

    </div>

        <?= $this->Form->end() ?>

      </div>

      <div class="modal-footer">

      <p>Mot de passe <a href="#">oublié?</a> </p>
        
      </div>

    </div>

  </div>

</div>