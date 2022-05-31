/**
 * settings.js
 *
 * Mise à jour des paramètres
 *
 */


// désactivation du copier coller pour les champs mail et mot de passe de confirmation

document.querySelectorAll("#confirmemail, #confirmpassword").forEach(item => {
  item.addEventListener('paste', event => {

    event.preventDefault();

    return false;

  })
})

// mise à jour informations

  //variables

let form_settings = document.querySelector('#form_settings'); // récupération du formulaire

let inputavatar = document.querySelector('#submittedfile'); // input file (avatar)

//preview de l'Avatar

inputavatar.addEventListener('change', (event) => {

     var imgPath = event.target;

     var size = imgPath.files[0].size; // taille fichier

     var extn = imgPath.files[0].type; // extension fichier

      if (extn == "image/jpg" || extn == "image/jpeg")
     { // fichier jpg/jpeg

        if(size <= 3047171) // taille inférieur ou égale à 3mo
       {
          if (typeof (FileReader) != "undefined") // si vieux navigateur
         {

             var reader = new FileReader();

              reader.onload = function()
             {
                var output = document.getElementById('previewHolder');
                output.src = reader.result;
             }

             reader.readAsDataURL(imgPath.files[0]);

         } 
          else //notification vieux navigateur
         {
              showNotification('Votre navigateur ne permet pas de lire ce fichier.', 'danger');
         }
       }
        else 
       {

          //notification fichier trop gros

          showNotification('Ce fichier est trop gros.', 'danger');

          inputavatar.value = ""; 

       }
     } 
      else 
     {

      //notification extension fichier

      showNotification('Seuls les fichiers Jpeg sont autorisés.', 'danger');

      inputavatar.value = ""; // on vide l'input

     }
 });

//envoi formulaire d'information

form_settings.addEventListener('submit', function (e) { // on capte l'envoi du formulaire

  e.preventDefault();

// vérification de l'égalité entre les mots de passe

//vérification si mot de passe envoyé

  if(document.querySelector('#password').value.length != 0)
{
  // test si le confirme passsword est pas vide

      if(document.querySelector('#confirmpassword').value.length == 0)
    {
      showNotification('Vous devez rentrer la confirmation du nouveau mot de passe.', 'danger');

      return;

    }

      else if(document.querySelector('#password').value != document.querySelector('#confirmpassword').value) // comparaison entre les 2
    {
      showNotification('Les 2 mots de passe ne corespondent pas.', 'danger');

      return;

    }
}

// si l'utilisateur rentre une confirmation de mot de passe sans mot de passe

    if(document.querySelector('#confirmpassword').value.length != 0 && document.querySelector('#password').value.length == 0)
  {
    showNotification('Les 2 mots de passe ne corespondent pas.', 'danger');

    return;
  }


// vérification de l'égalité entre les adresse mail

  if(document.querySelector('#mail').value.length != 0)
{
// test si le confirme mail est pas vide

        if(document.querySelector('#confirmemail').value.length == 0)
      {
        showNotification('Vous devez rentrer la confirmation de la nouvelle adresse mail.', 'danger');
        return;
      }

        else if(document.querySelector('#mail').value != document.querySelector('#confirmemail').value) // comparaison entre les 2
      {
        showNotification('Les 2 adresses mail ne corespondent pas.', 'danger');
        return;
      }
}
// si l'utilisateur rentre une confirmation d'adresse mail sans adresse mail

  if(document.querySelector('#confirmemail').value.length != 0 && document.querySelector('#mail').value.length == 0)
{
  showNotification('Les 2 adresses mail ne corespondent pas.', 'danger');
  
  return;
}

    let data = new FormData(this); // on récupère les données du formulaire

    let request = new XMLHttpRequest();

    request.onreadystatechange = function() 
  {
      if (request.readyState == 4 && request.status == 200) // si tout est bon
    {

     switch(request.response)
   {

    case "updateok": // mise à jour d'information réussie

                    //on vide la formulaire

                    form_settings.reset();

                    inputavatar.value = ""; // on vide l'input file

                    //notification de réussite

                    showNotification('Mise à jour de vos informations réussies.', 'success');

                    break;

    case "passwordformat": // échec mise en forme du mot de passe

                          showNotification('Votre mot de passe doit faire entre 8 et 20 caracères et les caractères spéciaux suivants sont autorisés : ~ ! @ # $% ^ & * ().', 'danger');

                    break;

    case "updateokandpassword": // mise à jour d'informations réussie dont le mot de passe

                                // on redirige vers la page des settings

                                window.location.href = '/youtux/login?redirect=%2Fsettings'; 

                                break;

    case "probleme": // problème de mise à jour

                    showNotification('Impossible de mettre à jour vos informations.', 'danger');

                    break;

    case "existingmail" : // mail existant

                          showNotification('Cette adresse mail est déjà utilisée.', 'danger');

                          break;

    case "notsamemail" : // mail ne correspondant pas

                        showNotification('Les 2 adresses mail ne corespondent pas.', 'danger');

                        break;

    case "notsamepassword" : // mail ne correspondant pas

                            showNotification('Les 2 mots de passe ne corespondent pas.', 'danger');

                            break;

    case "sizenotok" : // image trop lourde

                      showNotification('Cette image est trop lourde.', 'danger');

                      break;

    case "typenotok" : // pas de type jpeg/jpg

                      showNotification('Seules les images au format Jpeg sopnt autorisées.', 'danger');

                      break;
                  }

}
  }


  // envoi des données

  request.open('POST', form_settings.getAttribute('action'),true); 

  request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  request.send(data);
});


/** setup notification **/

  function setupnotif(typenotif) // type_notif : notif_comm ou notif_follow
{
        let select = document.querySelector('input[name="'+typenotif+'"]:checked').value; // choix coché : oui /non

        let data = { typenotif: typenotif, select: select }; // tableau de données

        let request = new XMLHttpRequest();

        request.onreadystatechange = function() 
      {
          if (request.readyState == 4 && request.status == 200) // si tout est bon
        {
    
            switch(request.response)
          {
    
            case "setupok": // mise à jour d'information réussie
    
                            showNotification('Mise à jour de vos préférences de notification réussie.', 'success');
    
                            break;
    
            case "setupnotok": // échec mise à jour d'information
    
                            showNotification('Un problème technique empêche la mise à jour de vos préférences de notification.Veuillez réessayer plus tard.', 'danger');
    
                            break;
    
        }
    
      }
    }
    
    
      // envoi des données
    
      request.open('POST', '/youtux/n/setup',true); 
    
      request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

      request.setRequestHeader("X-CSRF-Token", csrfToken);
    
      request.send(JSON.stringify(data));
    }

/** fin setup notification **/
