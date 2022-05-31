 /**
 * default.js
 *
 * Liste des fonctions qui seront utilisées partout
 *
 */

 /** variable **/

 const inputsearch = document.querySelector('#inputsearch');

 /** alert 
  * 
  * Affichage de notifications flash avec Bootstrap 5
  * 
  * Paramètres : message -> message à afficher | type : success,info,warning,...
  * 
 */
 
        /** 
       * Usage: showNotification('hey!', 'success'); 
       *
       * Affichage d'une notification d'action : ajouter une vidéo, supprimer un commentaire,...
       */
       function showNotification(toastBody, color) {

        var delay = 3000;

        var html = 
                    `<div class="toast align-items-center text-white bg-${color} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="d-flex">
                        <div class="toast-body">
                          ${toastBody}
                    </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>`;
        var toastElement = htmlToElement(html);
        var toastConainerElement = document.getElementById("toast-container");
        toastConainerElement.appendChild(toastElement);       
        var toast = new bootstrap.Toast(toastElement, {delay:delay, animation:true}); 
        toast.show();

        setTimeout(() => toastElement.remove(), delay+3000); // let a certain margin to allow the "hiding toast animation"
      }

      /**
       * @param {String} HTML representing a single element
       * @return {Element}
       */
      function htmlToElement(html) {
          var template = document.createElement('template');
          html = html.trim(); // Never return a text node of whitespace as the result
          template.innerHTML = html;
          return template.content.firstChild;
      }

      //emoji

 // traitement des emoji dans le textarea des commentaires
 
  document.addEventListener('click',function(e)
 {
   // si on détecte un emoji avec la class emoji

    if(e.target && e.target.className == 'emoji')
   {
     //on récupère le code emoji

     var code = e.target.getAttribute('data_code');

     //suppression de l'extension du fichier

     code  = code.replace(/\.[^/.]+$/, "");

     code = ' :'+code+': ';

     //ajout au textarea/input

    document.querySelector('#content').value += code;

    // focus sur la textarea/input

    document.querySelector('#content').focus();
  }
 });

 /* Afficher le mot de passe 
 Param : contient l'id du champ à afficher
 */

  function displayPassword(param) 
 {
  var x = document.querySelector("#"+param);

    if (x.type === "password") 
  {
    x.type = "text";
  } 
    else 
  {
    x.type = "password";
  }
}

// #Abonnement#

      // ajouter /supprimer un abonnement

document.addEventListener('click',function(e){

    if(e.target && e.target.classList.contains('actionfollow'))
  {

    var userconcerned = e.target.getAttribute('data_userconcerned') // utilisateur concerné par le bouton
      
    let URL;

      if(e.target.getAttribute('data_actionfollow') == 'add') // URL de création d'un abonnement
    {
      URL = '/youtux/f/new';
    }
      else if(e.target.getAttribute('data_actionfollow') == 'delete') // URL de suppression d'un abonnement
    {
      URL = '/youtux/f/delete';
    }
                
                let request = new XMLHttpRequest();

                request.responseType = 'json';

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {

                  const data = request.response;

                      switch(data.response)
                    {

                    case "newfollowok" : // nouvel abonnement ajouté
                      
                      // notification

                      showNotification('Abonnement ajouté.', 'success');

                     // mise à jour actionfollow

                      e.target.setAttribute('data_actionfollow', 'delete');

                      // mise à jour class button

                      e.target.className = e.target.className.replace("btn-danger", "btn-secondary");

                      // mise à jour text button

                      e.target.textContent = 'ABONNE';
                      
                      break;
              
                    case "newfollownotok" : // impossible d'ajouter un abonnement (serveur,bdd,...) 

                       // notification
                       
                       showNotification('Impossible d\'ajouter cet abonnement.', 'danger');
              
                        break;

                    case "alreadyfollow" : // abonnement existant 

                       // notification
                       
                       showNotification('Vous suivez déjà '+userconcerned+'', 'danger');
              
                        break;

                    case "removefollowok" : // suppression d'un abonnement réussie hors page gestion des abonnements

                        // notification

                        showNotification('Abonnement supprimé.', 'success');

                        // mise à jour actionfollow

                        e.target.setAttribute('data_actionfollow', 'add');

                        // mise à jour class button

                        e.target.className = e.target.className.replace("btn-secondary", "btn-danger");

                        // mise à jour text button

                        e.target.textContent = 'S\'ABONNE';
               
                         break;

                    case "removefollowfromlistok" : // suppression d'un abonnement réussie depuis la page de gestion des abonnements

                         document.querySelector('#'+userconcerned+'').remove();

                          if(document.querySelector('#mysubscriptions').textContent.trim() === '') // si la div contenant mes abonnements est vide , affichage d'un message
                         {
                          document.querySelector('#mysubscriptions').innerHTML = "<div class=\"alert alert-primary\" role=\"alert\">Vous ne suivez aucune chaîne pour le moment.</div>";
                            
                         }

                         showNotification('Abonnement supprimé.', 'success');

                         break;

                    case "removefollownotok" : // impossible de supprimer un abonnement (serveur,bdd,...) 

                         // notification
                         
                         showNotification('Impossible de supprimer cet abonnement.', 'danger');
                
                          break;
                      
                    }
                }
              }

              // envoi des données

                request.open('POST', URL,true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader("X-CSRF-Token", csrfToken);
                
                request.send(userconcerned);
  
              }
            })

//*Vidéo Favorite*//

// Ajouter une vidéo favorite

  function addfavoritemovie(idfavoritemovie) // paramètre : identifiant de la vidéo à mettre en favori
{

  let requesturl = new XMLHttpRequest();

  requesturl.responseType = 'json';

  requesturl.onreadystatechange = function() 
{
  if (requesturl.readyState == 4 && requesturl.status == 200) {

    const data = requesturl.response; // récupération de la réponse

      switch(data.response)
    {
      case "newfavoriteok":  // création de favori réussi

        // mise à jour de la fonction onclick du bouton pour passer à la fonction de suppression d'un favori

        document.querySelector('#btnfavorite').setAttribute('onclick','removefavoritemovie('+idfavoritemovie+','+data.idfavorite+')');

        // mise à jour de l'attribut title du bouton

        document.querySelector('#btnfavorite').setAttribute('title','Supprimer cette vidéo de vos favoris');

        // mise à jour de la class du button

        document.querySelector('#btnfavorite').className = document.querySelector('#btnfavorite').className.replace("btn-primary", "btn-secondary");

        // incrémentation du nombre de like

        document.querySelector('.nblike').textContent ++;
        
        // affichage d'une notification

        showNotification('Favori crée.', 'success');
    
          break;

      case "alreadyfavorite": // favori existant

        // affichage d'une notification

        showNotification('Cette vidéo fait déjà parti de vos favoris.', 'info');

          break;

      case "newfavoritenotok": // création de favori impossible : problème serveur, bdd,...

        // affichage d'une notification

        showNotification('Un problème technique empêche la création de cet favori.Veuillez réessayer plus tard.', 'danger');

          break;
    }
      
  }
}

  // appel de l'URL

  requesturl.open('POST', '/youtux/v/favorite/add', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send(idfavoritemovie);

}

  // Supprimer une vidéo favorite

  function removefavoritemovie(idfavoritemovie, idfavorite) // paramètres : identifiant de la vidéo pour suppression de la div | identifiant du favori en bdd pour suppression de l'entité côté serveur
{

  let requesturl = new XMLHttpRequest();

  requesturl.onreadystatechange = function() 
{
  if (requesturl.readyState == 4 && requesturl.status == 200) {

      switch(requesturl.response)
    {
      case "deletefavoritemovieok":  // suppresion de favori réussie

            if(document.querySelector('#favorite'+idfavoritemovie+'')) // si je suis sur la page de mes vidéos favorites
          {
             // suppression de la balise <hr> suivant la div à supprimer

              document.querySelector('#favorite'+idfavoritemovie+'').parentNode.removeChild(document.getElementsByTagName('hr')[0]);

             // suppression de la div contenant le favori

            document.querySelector('#favorite'+idfavoritemovie+'').remove();

            // si la div contenant les favoris est vide , affichage d'un message

            if(document.querySelector('#favoritemovielist').textContent.trim() === '') 
          {

            document.querySelector('#favoritemovielist').innerHTML = "<div class=\"alert alert-primary\" role=\"alert\">Vous n\'avez aucune vidéo favorite pour le moment.</div>";
        
          }
        }
        else // je suis sur la page view des vidéos
      {

        // mise à jour de la fonction onclick du bouton pour passer à la fonction d'ajout d'un favori

        document.querySelector('#btnfavorite').setAttribute('onclick','addfavoritemovie('+idfavoritemovie+')');

        // mise à jour de l'attribut title du bouton

        document.querySelector('#btnfavorite').setAttribute('title','Ajouter cette vidéo à vos favoris');

        // mise à jour de la class du bouton
  
        document.querySelector('#btnfavorite').className = document.querySelector('#btnfavorite').className.replace("btn-secondary", "btn-primary");

        // décrémentation du compteur de like

        document.querySelector('.nblike').textContent --;
      }

        // affichage d'une notification

        showNotification('Favori supprimé.', 'success');
    
          break;

      case "deletefavoritemovienotok": // suppression impossible d'un favori : problème serveur, bdd,...

        //affichage d'une notification

        showNotification('Un problème technique empêche la suppression de ce favori.Veuillez réessayer plus tard.', 'danger');

        break;
    }
      
  }
}

// appel de l'URL

  requesturl.open('POST', '/youtux/v/favorite/delete', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send(idfavorite);

}

// supprimer une vidéo

  function deletemovie(idmovie)
{

  let requesturl = new XMLHttpRequest();

  requesturl.onreadystatechange = function() 
{
  if (requesturl.readyState == 4 && requesturl.status == 200) {

      switch(requesturl.response)
    {
      case "deletemovieok":  // suppresion de vidéo réussie : affichage d'un message

            if( document.querySelector('#movie'+idmovie+'')) // page des vidéos
          {

            document.querySelector('#movie'+idmovie+'').parentNode.removeChild(document.getElementsByTagName('hr')[0]);

            document.querySelector('#movie'+idmovie+'').remove();

            // décremente le compteur

            document.querySelector('.nbmovies').textContent --;

              if(document.querySelector('#mymovieslist').textContent.trim() === '') // si la div contenant mes vidéos est vide , affichage d'un message
            {
              document.querySelector('#mymovieslist').innerHTML = "<div class=\"alert alert-primary nocommunitymessage\" role=\"alert\">Aucune vidéo à afficher.</div>";
            
            }
        }
          else // page view movie
        {
          document.querySelector('.container').innerHTML = '<div class="alert alert-success" role="alert">'+
                                                              'Vidéo supprimée avec succès.'+
                                                            '</div>';
        }

          
          showNotification('Vidéo supprimée.', 'success');
    
        break;

      case "notownermovie": // la vidéo appartient à quelqu'un d'autre

        showNotification('Impossible de supprimer cette vidéo, elle ne vous appartient pas.', 'danger');

        break;

      case "deletemovienotok": // suppression impossible : problème serveur, bdd,...

        showNotification('Un problème technique empêche la suppression de cette vidéo.Veuillez réessayer plus tard.', 'danger');

        break;
    }
      
  }
}

// appel de l'URL

  requesturl.open('POST', '/youtux/v/delete', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send(idmovie);

}

// publier une vidéo

  function releasedmovie(idmovie)
{

  let requesturl = new XMLHttpRequest();

  requesturl.onreadystatechange = function() 
{
  if (requesturl.readyState == 4 && requesturl.status == 200) {

      switch(requesturl.response)
    {
      case "releasedmovieok":  // vidéo publiée avec succès
     
          showNotification('Vidéo publiée.', 'success');

          // suppression du bouton de publication

          document.querySelector('#releasedmovie'+idmovie+'').remove();
    
        break;

      case "notownermovie": // la vidéo appartient à quelqu'un d'autre

        showNotification('Impossible de publier cette vidéo, elle ne vous appartient pas.', 'danger');

        break;

      case "releasedmovienotok": // suppression impossible : problème serveur, bdd,...

        showNotification('Un problème technique empêche la publication de cette vidéo.Veuillez réessayer plus tard.', 'danger');

        break;

        case "alreadyreleased": // la vidéo à déjà était publiée

        showNotification('Cette vidéo à déjà était publiée.', 'info');

        break;
    }
      
  }
}

// appel de l'URL

  requesturl.open('POST', '/youtux/v/released', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send(idmovie);

}

// supprimer ma chaîne (toutes mes vidéos)

  function deletemychannel()
{

  let requesturl = new XMLHttpRequest();

  requesturl.onreadystatechange = function() 
{
    if (requesturl.readyState == 4 && requesturl.status == 200) 
  {

      switch(requesturl.response)
    {
      case "deletechannelok":  // vidéo publiée avec succès
     
          showNotification('Votre chaîne à bien était supprimée.', 'success');


        break;

      case "deletechannelnotok": // suppression impossible : problème serveur, bdd,...

        showNotification('Impossible de supprimer votre chaîne. Soit vous n\'avez pas posté assez de contenu soit un problème technique est en cause.', 'danger');

        break;

    }
      
  }

}

// appel de l'URL

  requesturl.open('POST', '/youtux/v/deletechannel', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send();

}

//# recherche

// on capte l'appui sur la touche 'Entrée' après une recherche

inputsearch.addEventListener('keydown', function (e) {

  if (e.keyCode === 13) 
{

    if (inputsearch.value.length <= 2 ) // si le nombre de caractère est égal à 0 , on affiche un message
  {

    showNotification('Recherche trop courte.', 'danger');

  }

  else if(inputsearch.value.startsWith("#")) // si la recherche commence par #

{
    if(inputsearch.value.length >= 2) // 2 caractères minimum
  {
    inputsearch.value = inputsearch.value.replace("#", ""); // on supprime la caractère #

    window.location.href = '/youtux/search/hashtag/'+inputsearch.value+''; // on redirige vers la recherche hashtag
  }
  else // recherche trop courte
{
  showNotification('Recherche trop courte.', 'danger');
}

}

  else // on redirige vers la recherche classique
{
  window.location.href = '/youtux/search/'+inputsearch.value+''; 
}

}

})

// si la variable sorturl existe-> on à donc cliqué sur un lien de tri : affichage d'une icône pour indiquer sur quel lien on à cliqué

  if (typeof sorturl !== 'undefined' && sorturl) 
{

    if (sorturl == 'desc')
  {
    document.querySelector('#linkmostrecent').innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">'+
    '<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>'+
    '</svg>';
  }
    else
  {
    document.querySelector('#linkmostancient').innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">'+
    '<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>'+
    '</svg>';
  }
}

// #Blocage#

// Création/Suppression d'un blocage

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('actionblock')){

    var userconcerned = e.target.getAttribute('data_userconcerned') // utilisateur concerné par le bouton
      
    let URL;

      if(e.target.getAttribute('data_actionblock') == 'add')
    {
      URL = '/youtux/b/new';
    }
      else if(e.target.getAttribute('data_actionblock') == 'delete')
    {
      URL = '/youtux/b/delete';
    }
                
                let request = new XMLHttpRequest();

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {

                      switch(request.response)
                    {

                      case "newblockok" : // nouveau blocage ajouté
                      
                      // notification

                      showNotification(''+userconcerned+' est désormais bloqué, il ne peut plus commenter votre contenu et tous ses commentaires ont été supprimés sur vos vidéos.', 'success');

                     // mise à jour actionblock

                      if(e.target.tagName == "BUTTON")
                     {
                      e.target.setAttribute('data_actionblock', 'delete');

                      // mise à jour class button

                      e.target.className = e.target.className.replace("btn-danger", "btn-secondary");

                      // mise à jour text button

                      e.target.textContent = 'DEBLOQUER';
                     }

                       break;
              
                       case "newblocknotok" : // impossible d'ajouter un blocage (serveur,bdd,...) 

                       // notification
                       
                       showNotification('Impossible de bloquer cet utilisateur.', 'danger');
              
                        break;

                        case "alreadyblock" : // blocage existant 

                       // notification
                       
                       showNotification('Vous avez déjà bloqué '+userconcerned+'', 'danger');
              
                        break;

                        case "removeblockok" : // suppression d'un blocage réussie 

                        // notification

                        showNotification('Utilisateur débloqué.', 'success');

                        // mise à jour actionfollow

                        e.target.setAttribute('data_actionblock', 'add');

                        // mise à jour class button

                        e.target.className = e.target.className.replace("btn-secondary", "btn-danger");

                        // mise à jour text button

                        e.target.textContent = 'BLOQUER ';
               
                         break;

                         case "removeblockfromlistok" : // suppression d'un blocage réussie depuis la page de gestion des blocage

                         document.querySelector('#'+userconcerned+'').remove(); // suppression de la div contenant l'utilisateur

                          if(document.querySelector('#mylistuserblocked').textContent.trim() === '') // si la div contenant mes blocage est vide , affichage d'un message
                         {
                          document.querySelector('#mylistuserblocked').innerHTML = "<div class=\"alert alert-primary\" role=\"alert\">Vous n\'avez bloqué aucun utilisateur.</div>";
                            
                         }

                         showNotification('Abonnement supprimé.', 'success');

                         break;

                         case "removeblocknotok" : // impossible de supprimer un blocage (serveur,bdd,...) 

                         // notification
                         
                         showNotification('Impossible de débloquer cet utilisateur.', 'danger');
                
                          break;
                      
                    }
                }
              }

              // envoi des données

                request.open('POST', URL,true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader("X-CSRF-Token", csrfToken);

                request.send(userconcerned);
  
              }
            })

//#Notification#//

// supprimer une notification

function removenotification(idnotif)
{

  let requesturl = new XMLHttpRequest();

  requesturl.onreadystatechange = function() 
{
  if (requesturl.readyState == 4 && requesturl.status == 200) {

      switch(requesturl.response)
    {
      case "deletenotifok":  // suppresion de vidéo réussie : affichage d'un message

            document.querySelector('#notification'+idnotif+'').remove();

            // décremente le compteur

            document.querySelector('#nbnotif').textContent --;

              if(document.querySelector('#notificationslist').textContent.trim() === '') // si la div contenant mes vidéos est vide , affichage d'un message
            {
              document.querySelector('#notificationslist').innerHTML = "<div class=\"alert alert-primary nocommunitymessage\" role=\"alert\">Vous n\'avez aucune notifications pour le moment.</div>";
            
            }


          showNotification('Notification supprimée.', 'success');
    
        break;

      case "notownernotif": // la vidéo appartient à quelqu'un d'autre

        showNotification('Impossible de supprimer cette notification, elle ne vous appartient pas.', 'danger');

        break;

      case "deletenotifnotok": // suppression impossible : problème serveur, bdd,...

        showNotification('Un problème technique empêche la suppression de cettenotification.Veuillez réessayer plus tard.', 'danger');

        break;
    }
      
  }
}

// appel de l'URL

  requesturl.open('POST', '/youtux/n/delete', true); 

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("X-CSRF-Token", csrfToken);

  requesturl.send(idnotif);

}
