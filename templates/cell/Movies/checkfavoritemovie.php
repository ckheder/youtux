<!-- followstatus.php

Vue cell affichant un bouton d'état d'un abonnement

La couleur , le texte et l'action du bouton dépendent du résultat du test d'existence depuis la cell.

-->

<button type="button" 

    id="btnfavorite"

    class="btn <?= ($favoritestatus == "nofavorite") ? "btn-primary" : "btn-secondary" ;?>"  
    
    onclick="<?= ($favoritestatus == "nofavorite") ? "addfavoritemovie($idmovie)" : "removefavoritemovie($idmovie,$idfavoritemovie)" ;?>"

    title="<?= ($favoritestatus == "nofavorite") ? "Ajouter cette vidéo à vos favoris" : "Supprimer cette vidéo de vos favoris" ;?>">
    
    FAVORI

</button>