<?php
use Cake\Routing\Router;
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $title ;?>
    </title>
    <?= $this->Html->meta('favicon.ico','img/favicon.ico', ['type' => 'icon']); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    

</head>

<body class="bg-dark text-white">

<?php 

  if($authName)
{
  echo $this->element('onlinetopnavbar');

  echo $this->Html->scriptStart(); 

  // génération d'un token CSRF pour l'envoi de données en AJAX
  
    echo  sprintf(
                'var csrfToken = %s;',
                json_encode($this->request->getAttribute('csrfToken'))
              ); 
  
  echo $this->Html->scriptEnd();
}
  else
{
  echo $this->element('offlinetopnavbar');

  echo $this->element('modalconnexion');
}

 ?>

<div class="container-fluid">

<div class="row">

<?= $this->element('leftnavmenu'); ?>
      
    <div class="col-10">

      <div id="notificationresultat"></div> <!-- zone de notification en Javascript AJAX -->

      <?= $this->Flash->render() ?> <!-- zone de notification connexion/déconnexion/inscription -->

    <div class="m-2">
      
    <?php

  if(isset($unknownuser))
{
 
  echo $this->fetch('content');

}
  else
{

  echo $this->element('profilheader'); 

  ?>

    <div class="d-flex justify-content-center">

      <div class="spinner-border text-success visually-hidden" role="status"></div>

    </div>

    <div id="profil_<?= $this->request->getParam('username') ?>">

      <?= $this->fetch('content') ?>

    </div>

</div>

</div>

</div>

</div>

<?php
}

?>
  <div aria-live="polite" aria-atomic="true">
      <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
        <!-- toasts are created dynamically -->
      </div>
    </div>

<!-- script JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<?= $this->Html->scriptStart(); ?>

var currentuser = "<?= $this->request->getParam('username') ?>"; <!-- utilisateur courant -->

<?= $this->Html->scriptEnd();?>

<?= $this->Html->script('profil.js'); ?>

</body>

</html>



