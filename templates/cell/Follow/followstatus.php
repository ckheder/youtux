<!-- followstatus.php

Vue cell affichant un bouton d'état d'un abonnement

La couleur , le texte et l'action du bouton dépendent du résultat du test d'abonnement depuis la cell.

-->

<button type="button" 

    class="btn <?= ($followstatus == "nofollow") ? "btn-danger" : "btn-secondary" ;?>  
    
    actionfollow" data_userconcerned="<?= $username ?>" data_actionfollow="<?= ($followstatus == "nofollow") ? "add" : "delete" ;?>">
    
    <?= ($followstatus == "nofollow") ? "S'ABONNER" : "ABONNE" ;?>

</button>

