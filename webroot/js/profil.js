
// Navigation sur la page de profil d'un utilisateur

// variable 

let divuserprofil = document.querySelector("#profil_"+currentuser+""); // div servant à accueuillir les données chargées

let URL; // url de chargement des différents onglets

let spinner = document.querySelector('.spinner-border'); // spinner de chargement des données au changement d'onglets

// naviguer entre les différents onglets

  function loadProfilItem(itemprofil)
{

      switch(itemprofil)
    {
        case "videos": // page des videos d'une personne

        URL = '/youtux/'+currentuser+'';

        break;

        case "communaute": // page des message d'une communaute

        URL = '/youtux/'+currentuser+'/communaute';

        break;

        case "chaines": // page des chaînes suivies d'une personne

        URL = '/youtux/'+currentuser+'/chaines';

        break;

        case "apropos": // page des utilisateurs bloqués

        URL = '/youtux/'+currentuser+'/apropos';

        break;

        default: return;
  }

    spinner.classList.remove("visually-hidden"); // affichage du spinner de chargement

    let requesturl = new XMLHttpRequest();

      requesturl.onreadystatechange = function() 
    {
      if (requesturl.readyState == 4 && requesturl.status == 200) {

        divuserprofil.innerHTML = ""; // on vide la div d'affichage des informations

        spinner.classList.add("visually-hidden"); // suppression du spinner de chargement

        divuserprofil.innerHTML= this.responseText; // on charge les données dans la div

        // suppression de la class 'active' sur l'onglet précédant actif

        document.querySelector('.nav-link.active').classList.add("text-white");

        document.querySelector('.nav-link.active').classList.remove("active");

        // ajout de la classe 'active' sur l'item cliqué
  
        document.querySelector('#'+itemprofil+'').classList.add("active");

        document.querySelector('#'+itemprofil+'').classList.remove("text-white");
      }
  }

  // appel de l'URL

  requesturl.open('GET', URL, true); 

  // envoi d'un header spécifique AJAX pour les controller CakePHP

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.send();
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


// #Abonnement#

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('actionfollow')){

    var userconcerned = e.target.getAttribute('data_userconcerned') // utilisateur concerné par le bouton
      
    let URL;

      if(e.target.getAttribute('data_actionfollow') == 'add')
    {
      URL = '/youtux/f/new';
    }
      else if(e.target.getAttribute('data_actionfollow') == 'delete')
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

                        case "removefollowok" : // suppression d'un abonnement réussie 

                        // notification

                        showNotification('Abonnement supprimé.', 'success');

                        // mise à jour actionfollow

                        e.target.setAttribute('data_actionfollow', 'add');

                        // mise à jour class button

                        e.target.className = e.target.className.replace("btn-secondary", "btn-danger");

                        // mise à jour text button

                        e.target.textContent = 'S\'ABONNE';
               
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

/** Gestion des messages communautaire */

// Nouveau message communautaire

document.addEventListener('submit',function(e){

  if(e.target && e.target.id === 'formaddcommunitypost'){

  e.preventDefault();

  let data = new FormData(); // on récupère les données du formulaire

  document.querySelector('input[type="submit"]').disabled = true // désactivation du bouton

  document.querySelector('input[type="submit"]').value = 'Publication en cours...' // mise à jour du texte du bouton

  // ajout du message communautaire à l'objet Formdata

  data.append('communitymessage', document.querySelector('#content').value);

  // si l'URL est celle de modification d'un message communautaire

    if(document.querySelector('#formaddcommunitypost').getAttribute('action') == '/youtux/c/update')
  {

    // on l'ajoute à l'objet Formdata l'identifiant du message communautaire 

    data.append('idcommunitymessagetoupdate', document.querySelector('[name="communitymessagetoupdate"]').value)
  }

  let request = new XMLHttpRequest();

  request.responseType = 'json';

  request.onreadystatechange = function() 
{
    if (request.readyState == 4 && request.status == 200) // si tout est bon
  {

    document.querySelector('input[type="submit"]').disabled = false // on réactive le bouton

    document.querySelector('input[type="submit"]').value = 'Publier'// on remet le texte initial du bouton

    const data = request.response; // récupération de la réponse

        switch(data.response)
      {
        case "newcommunitymessageok" :  // message communautaire ajouté avec succès 
        
        document.querySelector('#formaddcommunitypost').reset(); // reset du formulaire

        //insertion du nouveau message communautaire au tout début de la div

        document.querySelector('#communitymessagelist').insertAdjacentHTML('afterbegin', '<div id="communitypost'+data.communitypost['id_community_post']+'" class="m-3">'+
        '<div class="dropdown float-end">'+
          '<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">...'+
          '</button>'+
        '<ul class="dropdown-menu">'+
          '<li><a class="deletecommunitypost dropdown-item" href="#" onclick="return false;" data_idcommunitypost="'+data.communitypost['id_community_post']+'">Supprimer</a></li>'+
          '<li><a class="updatecommunitypost dropdown-item" href="#" onclick="return false;" data_idcommunitypost="'+data.communitypost['id_community_post']+'"> Modifier</a></li>'+
        '</ul>'+
        '</div>'+
        '<img src="/youtux/users/'+data.communitypost['username_community_post']+'/'+data.communitypost['username_community_post']+'.jpg" alt="image utilisateur" class="d-inline rounded-circle me-1" width="48" height="48">'+
        '<span class="ms-2 fs-6">'+
          ''+data.communitypost['username_community_post']+''+
        '<span class="text-secondary">  à l\'instant </span>'+
        '</span>'+
        '<p id="communitycontent'+data.communitypost['id_community_post']+'" class="mt-2">'+
       ''+data.communitypost['message_community_post']+'</p>'+
       '<a href="/youtux/c/'+data.communitypost['id_community_post']+'" class="text-decoration-none">'+
       '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">'+
       '<path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>'+
       '<path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>'+
      '</svg>'+
      '&nbsp;'+data.communitypost['nb_comm']+'</a>'+
       '<hr>'+
      '</div>');

        // disparition de la div nocommunitymessage si elle existe (cas du premier message communautaire d'une vidéo)

        if(document.querySelector('.nocommunitymessage'))
        {
          document.querySelector('.nocommunitymessage').remove();
        }

          showNotification('Message Communautaire posté!', 'success') // notification de réussite

         break;

      case "newcommunitymessagenotok" :  // impossible de publier un message communautaire

          showNotification('Impossible de publier cette vidéo', 'danger') // notification d'échec

          break;

      case "emptycommunitymessage" :   // impossible de publier un message communautaire vide

          showNotification('Un message communautaire ne peut pas être vide.', 'danger') // notification d'échec

          break;

      case "notownercommunitymessage" : // je tente de modifier un message communautaire qui ne m'appartient pas

          // affichage d'une notification
          
          showNotification('Ce message communautaire ne vous appartient pas, il ne peut être modifié que par son auteur.', 'danger');

          break

      case "updatecommunitymessageok" : // mise à jour avec succès d'un message communautaire

          // affichage d'une notification
          
          showNotification('Message communautaire modifié !.', 'success');

          // on récupère la span 'text-secondary' pour y afficher la mention modifié si elle n'existe pas

            if(!document.querySelector('#communitypost'+data.idcommunitypost+' span[class="text-secondary"]').textContent.includes(" · modifié"))  // si la mention existe déjà (un commentaire modifié est re modifié), on n'ajoute pas la mention
          {
            document.querySelector('#communitypost'+data.idcommunitypost+' span[class="text-secondary"]').insertAdjacentText("beforeend", " · modifié");
          }

          // appelle de la fonction resetformafterupdate()

          resetformafterupdate();

          // affichage du message communautaire modifié

          document.querySelector('#communitycontent'+data.idcommunitypost+'').innerHTML = data.updatedcommunitypost;

          break;

      case "updatecommunitymessagenotok" :  // impossible de mettre à jour le message communautaire (serveur,bdd...)
          
          showNotification('Un problème technique est survenue lors de la modification de ce message communautaire. Veuillez réessayer plus tard.', 'danger');

          // problème insertion BDD 

          break;

      }
  }
}

// envoi des données

request.open('POST', document.querySelector('#formaddcommunitypost').getAttribute('action'),true); 

request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

request.setRequestHeader("X-CSRF-Token", csrfToken);

request.send(data);
  }

})

// suppression d'un message communautaire

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('deletecommunitypost')){

     var idcommunitypost =  e.target.getAttribute('data_idcommunitypost') // identifiant du message communautaire

                let request = new XMLHttpRequest();

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {
                      switch(request.response)
                    {

                      case "deletecommunitymessageok" :  // suppression d'un message communautaire réussi 
                      
                            const divcommunitypost = document.querySelector('#communitypost'+e.target.getAttribute('data_idcommunitypost')); // on récupère la div contenant le message communautaire

                            divcommunitypost.parentNode.removeChild(divcommunitypost); // suppression de la div contenant le message communautaire

                            if(document.querySelector('#communitymessagelist').textContent.trim() === '') // si la div contenant les messages communautaires est vide , affichage d'un message
                          {
                            document.querySelector('#communitymessagelist').innerHTML = "<div class=\"alert alert-primary nocommunitymessage\" role=\"alert\">Aucun message communautaire à afficher.</div>";
                            
                          }
                    
                      // notification

                        showNotification('Message communautaire supprimé.', 'success');
                      
                       break;
              
                       case "deletecommunitymessagenotok" : // impossible de supprimer un message communautaire (serveur,bdd...) 

                        // notification
                       
                        showNotification('Impossible de supprimer ce message communautaire.', 'danger');
              
                        break;

                        case "notownercommunitymessage" : // impossible de supprimer un message communautaire car on en est pas l'auteur

                       // notification
                       
                        showNotification('Ce message communautaire ne vous appartient pas, vous ne pouvez pas le supprimer.', 'danger');
              
                        break;
                      
                    }
                }
              }

              // envoi des données

                request.open('POST', '/youtux/c/delete',true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader("X-CSRF-Token", csrfToken);
                
                request.send(idcommunitypost);
  
              }
            })

// modifier un message communautaire

// préparation formulaire et textarea

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('updatecommunitypost')){

    // récupère le contenu du message communautaire

    var communitymessagevalue = document.querySelector('#communitycontent'+e.target.getAttribute('data_idcommunitypost')+'');

    // on clone le contenu du message communautaire

    var clonecommunitymessagecontent = communitymessagevalue.cloneNode(true);

    // on remplace tous les emoji par leur alt

      // pour chaque élément 'img' (emoji) on le remplace par son alt

    clonecommunitymessagecontent.querySelectorAll('img').forEach(
      el =>
      {
        el.replaceWith(document.createTextNode(el.alt))
      }
    );

    // on insère le contenu du cmessage communautaire dans l'input

    document.querySelector('#content').value = clonecommunitymessagecontent.innerHTML;

    // si j'appuie sur modifier une deuxième fois ou sur un autre message communautaire je met à jour le champ caché avec le message communautaire id à mettre à jour

      if(document.querySelector('#formaddcommunitypost').querySelector('input[name="communitymessagetoupdate"]'))
    {
 
     document.querySelector('#formaddcommunitypost').querySelector('input[name="communitymessagetoupdate"]').value = e.target.getAttribute('data_idcommunitypost');
 
    }
 
       // sinon on crée un nouvel input caché avec cette valeur
 
     else
    {
      let inputcomm = document.createElement("input");
 
      inputcomm.type = "hidden";
 
      inputcomm.name = "communitymessagetoupdate";
 
      inputcomm.value = e.target.getAttribute('data_idcommunitypost');
 
      document.querySelector('#content').parentNode.insertBefore(inputcomm, document.querySelector('#content').nextSibling);
    }

    // on scroll vers l'input

    document.querySelector('#content').scrollIntoView();

    // focus sur l'input

    document.querySelector('#content').focus();

    // modification de l'url de destination

    document.querySelector('#formaddcommunitypost').action = '/youtux/c/update';

    // on modifie le bouton d'envoi d'aide

    document.querySelector('input[type="submit"]').value = 'Enregistrer modifications';

    // suppression du précédent bouton d'annulation si il existe

      if(document.querySelector('input[type="reset"]'))
    {
        document.querySelector('input[type="reset"]').remove();
    }

    // création d'un bouton d'annulation si il n'existe pas

      if(!document.querySelector('a[name="linkcancelupdatecommunitymessage"]'))
    {
      let linkcancelupdatecommunitymessage = document.createElement("button");

      linkcancelupdatecommunitymessage.name = "linkcancelupdatecommunitymessage";

      linkcancelupdatecommunitymessage.href = "#";

      linkcancelupdatecommunitymessage.textContent = "Annuler";

      linkcancelupdatecommunitymessage.className = 'btn btn-secondary';

      //document.querySelector('.submit:nth-child(2)').appendChild(linkcancelupdatecommunitymessage);

      document.querySelector('.submit').appendChild(linkcancelupdatecommunitymessage);

    }

  }
});

// traitement bouton d'annulation de modification

document.addEventListener('click',function(e){

  if(e.target && e.target.name == 'linkcancelupdatecommunitymessage')
{
  resetformafterupdate();
}
})

// fonction qui va réinitialiser le formulaire après une mise à jour d'un message communautaire : 

// soit au click sur le bouton d'annulation 

// soit après une validatipon de modification


  function resetformafterupdate()
{

  document.querySelector('button[name="linkcancelupdatecommunitymessage"]').remove();

  // vidage du textarea

  document.querySelector('#content').value = '';

  // suppression du champ caché
  
  document.querySelector('#formaddcommunitypost').removeChild(document.querySelector('input[name="communitymessagetoupdate"]'));
  
  // mise à jour de l'action du formulaire
  
  document.querySelector('#formaddcommunitypost').action = '/youtux/c/new';

  // on modifie le bouton d'envoi d'aide

  document.querySelector('input[type="submit"]').value = 'Poster';

  
}

// recherche

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

//Création / Suppression d'un blocage

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

                      case "newblockok" : // blocage ajouté
                      
                      // notification

                      showNotification('Utilisateur bloqué.', 'success');

                     // mise à jour actionblock

                      e.target.setAttribute('data_actionblock', 'delete');

                      // mise à jour class button

                      e.target.className = e.target.className.replace("btn-danger", "btn-secondary");

                      // mise à jour text button

                      e.target.textContent = 'DEBLOQUER';
                      
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

                        // mise à jour actionblock

                        e.target.setAttribute('data_actionblock', 'add');

                        // mise à jour class button

                        e.target.className = e.target.className.replace("btn-secondary", "btn-danger");

                        // mise à jour text button

                        e.target.textContent = 'BLOQUER ';
               
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