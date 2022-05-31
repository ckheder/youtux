<!-- add.php

Page d'envoi d'une nouvelle vidéo

-->

<div class="bg-light p-3">

        <div class="row justify-content-start">

                <div class="col-6">

                        <h4 class="text-dark text-center">Nouvelle vidéo</h4>

                                <?= $this->Form->create(null,['id' =>'formaddvideo','url' => ['controller' => 'Movies', 'action' => 'add'],['enctype' => 'multipart/form-data']]);?>

                                <?= $this->Form->control('titre' , ['class' =>'form-control','label' =>'','placeholder'=>'Titre de la vidéo','required']); ?>
 
                                <?= $this->Form->file('videofilename',['class' => 'form-control', 'id' =>'formFileVideo']); ?>

                                <?= $this->Form->textarea('description',['class' =>'form-control','id' => 'content','label' =>'','placeholder'=>'Bref descriptif de votre vidéo','style' =>'resize:none','required']); ?>

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

                                <?= $this->Form->select('type', [
                                                                'released' => 'Vidéo publiée',
                                                                'unreleased' => 'Vidéo publiée plus tard'
                                                                ], 
                                                ['class' =>'form-select']); ?>

                                <?= $this->Form->select('allow_comment', [
                                                                '0' => 'Autoriser les commentaires',
                                                                '1' => 'Désactiver les commentaires'
                                                                ], 
                                                ['class' =>'form-select']); ?>

                                <?= $this->Form->select('categorie', [  'uncategorized' => 'Aucune catégorie',                                            
                                                                        'musique' => 'Musique',
                                                                        'sport' => 'Sport',
                                                                        'jeuxvideo' => 'Jeux',
                                                                        'filmtv' => 'Film et TV',
                                                                        'actualités' => 'Actualites',
                                                                        'modeetbeaute' => 'Mode et Beuaté',
                                                                        'savoirs' => 'Savoirs'
                                                                        ], 
                                                ['class' =>'form-select']); ?>
                    

                <div class="text-center">
          
                        <?= $this->Form->button(__('Poster'), ['class' =>'btn btn-primary m-3']) ?>

                </div>

            <?= $this->Form->end() ?>

        <div class="progress bg-success" style="height: 40px; display:none;">

                <div class="progress-bar bg-success" role="progressbar" style="height: 40px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
        
                </div>
        </div>

</div>

<div class="col-6">

<h4 class="text-center text-dark">Informations</h4>

        <div id="titrevideoHelp" class="form-text m-4"> 
                
                - Utiliser un titre explicite pour mieux identifier le contenu de votre vidéo.

        </div>

        <div id="formatvideoHelp" class="form-text m-4">

                - Votre vidéo doit être au format MP4 ou WebM. 

        </div>

        <div id="descriptionvideoHelp" class="form-text m-4">

                - Faites un bref descriptif du contenu de votre vidéo.
        </div>

        <div id="typevideoHelp" class="form-text m-4"> 
                
                - Une vidéo publiée sera visible un fois postés et trouvable par le moteur de recherche alors qu'une vidéo non publiée le sera quand vous le déciderez et non trouvable par moteur de recherche.

        </div>

        <div id="commentHelp" class="form-text m-4"> 
                
                - Vous pouvez décider, dès la publication de la vidéo, si les commentaires concernant celle-ci sont autorisés ou non. Vous pourrez changer d'avis plus tard si vous le voulez.

        </div>

        <div id="categorievideoHelp" class="form-text m-4"> 
        
                - Vous pouvez tout à fait ne pas donner de catégorie à votre vidéo, dans le cas contraire elle sera trouvable via les liens de catégories et publique.

        </div>

                </div>
        
        </div>

</div>

<?=  $this->Html->script('addvideo'); ?>