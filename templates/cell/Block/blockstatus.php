<!-- blockstatus.php

Vue cell affichant un bouton d'état d'un blocage

La couleur , le texte et l'action du bouton dépendent du résultat du test de blocage depuis la cell.

-->

<button type="button" 

    class="btn <?= ($blockstatus == "noblock") ? "btn-danger" : "btn-secondary" ;?>  
    
    actionblock" data_userconcerned="<?= $username ?>" data_actionblock="<?= ($blockstatus == "noblock") ? "add" : "delete" ;?>">
    
    <?= ($blockstatus == "noblock") ? "BLOQUER" : "DEBLOQUER" ;?>

</button>


