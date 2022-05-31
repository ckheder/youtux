// communityposts.js

// Traitement des différentes actions sur la page 'view' d'un message communautaire : crée, modifier, supprimer un commentaire communautaire

// et suppression du message communautaire

// variables

let divcommunitycomment = document.querySelector("#community_comments_zone"); // div servant à accueuillir les données chargées

let spinner = document.querySelector('.spinner-border'); // spinner de chargement des données au changement d'onglets

// au chargement de la page, on appelle la page des commentaires communautaires d'un message communautaire

  divcommunitycomment.addEventListener('DOMContentLoaded', loadCommunityComments(''+idcommunitypost+''));

// fonction de chargement des commentaires
//
// Paramètres :  idcommunitypost -> identifiant du message communautaire en cours de visite

  function loadCommunityComments(idcommunitypost)
{

    spinner.classList.remove("visually-hidden"); // affichage du spinner de chargement

    let requesturl = new XMLHttpRequest();

      requesturl.onreadystatechange = function() 
    {
      if (requesturl.readyState == 4 && requesturl.status == 200) {

        divcommunitycomment.innerHTML = ""; // on vide la div d'affichage des commentaires

        spinner.classList.add("visually-hidden"); // suppression du spinner de chargement

        divcommunitycomment.innerHTML= this.responseText; // on charge les données dans la div

      }
  }

  // appel de l'URL

  requesturl.open('GET', '/youtux/c/comments/'+idcommunitypost+'/', true); 

  // envoi d'un header spécifique AJAX pour les controller CakePHP

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.send();
}

// ajouter un commentaire communautaire

document.addEventListener('submit', (e) => {

  if (e.target.id === 'formcommunitycomment') {

      e.preventDefault();

  let data = new FormData(); // on crée un nouvel objet formdata

  // si le formulaire pointe vers la modification d'un commentaire communautaire,

    if(document.querySelector('#formcommunitycomment').getAttribute('action') == '/youtux/c/comments/updatecommunitycomment')
  {

    // on l'ajoute à l'objet Formdata l'identifiant du message communautaire 

    data.append('idcommentcommunitytoupdate', document.querySelector('[name="communitycommenttoupdate"]').value)
  }
    else
  {
    data.append('idcommunitypost', idcommunitypost); // ajout de l'id du message communautaire en cours de visite
  }

    // ajout du commentaire communautaire à l'objet Formdata

    data.append('communitycomment', document.querySelector('#content').value);

    let request = new XMLHttpRequest();

    // on veut une réponse et un envoi en JSON

    request.responseType = 'json';

    request.onreadystatechange = function() 
  {
      if (request.readyState == 4 && request.status == 200) // si tout est bon
    {

        const data = request.response; // récupération de la réponse

        switch(data.response)
      {
        case "newcommunitycommentok" :  // ajout d'un nouveau commentaire communautaire
        
                            document.querySelector('#content').value = ''; // reset du formulaire

      //insertion du nouveau commentaire communautaire au tout début de la div

      document.querySelector('.startcommunitycomment').insertAdjacentHTML('afterend', '<div id="communitycomments'+data.communitycomment['id_community_comment']+'">'+
                                                                          '<div class="dropdown float-end">'+
                                                                            '<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">'+
                                                                            '</button>'+
                                                                            '<ul class="dropdown-menu">'+
                                                                              '<li><a class="deletecommunitycomment dropdown-item" href="#" onclick="return false;" data_idcommunitycomment="'+data.communitycomment['id_community_comment']+'">Supprimer</a></li>'+
                                                                              '<li><a class="updatecommunitycomment dropdown-item" href="#" onclick="return false;" data_idcommunitycomment="'+data.communitycomment['id_community_comment']+'"> Modifier</a></li>'+
                                                                            '</ul>'+
                                                                          '</div>'+
                                                                          '<img src="/youtux/users/'+data.communitycomment['username_community_comment']+'/'+data.communitycomment['username_community_comment']+'.jpg" alt="image utilisateur" class="d-inline rounded-circle me-1" width="48" height="48">'+
                                                                          '<span class="ms-2 fs-6">'+
                                                                            '<a href="/youtux/'+data.communitycomment['username_community_comment']+'" class="text-decoration-none">'+data.communitycomment['username_community_comment']+'</a>'+
                                                                            '<span class="text-secondary">  à l\'instant </span>'+
                                                                          '</span>'+
                                                                          '<p id="communitycommentcontent'+data.communitycomment['id_community_comment']+'" class="mt-2">'+
                                                                           ''+data.communitycomment['community_comment']+'</p><hr>'+
                                                                          '</div>');

          // incrémenation du nombre de commentaire communautaire

          document.querySelector('#nb_community_comment').textContent ++;

          // disparition de la div nocommunitycomment si elle existe (cas du premier commentaire communautaire d'un message communautaire)

            if(document.querySelector('.nocommunitycomment'))
          {
            document.querySelector('.nocommunitycomment').remove();
          }

          // affichage d'une notification de succès

          showNotification('Commentaire posté', 'success');

         break;

         case "userblocked" : //utilisateur bloqué

         showNotification(''+authcommunitypost+' vous à bloqué, vous ne pouvez pas commenter ce post communautaire', 'danger');

         break;

         case "newcommunitycommentnotok" :  // impossible d'ajouter un commentaire communautaire : problème serveur,BDD,..

          // affichage d'une notification d'échec

         document.querySelector('#notificationcomm').innerHTML = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

         showNotification('Un problème technique est survenue lors de la publication de ce commentaire. Veuillez réessayer plus tard.', 'danger');

          break;

          case "emptycommunitycomment" :  // commentaire vide

          // affichage d'une notification
          
          showNotification('Un commentaire ne peut être vide.', 'info');

          break;

          // UPADTE COMMUNITY COMMENT

          case "notownercommunitycomment" : // je tente de modifier un message communautaire qui ne m'appartient pas

          // affichage d'une notification
          
          showNotification('Ce commentaire communautaire ne vous appartient pas, il ne peut être modifié que par son auteur.', 'danger');

          break

          case "updatecommunitycommentok" : // mise à jour avec succès d'un message communautaire

          // affichage d'une notification
          
          showNotification('Commentaire communautaire modifié !.', 'success');

          // on récupère la span 'text-secondary' pour y afficher la mention modifié si elle n'existe pas

            if(!document.querySelector('#communitycomments'+data.idcommunitycomment+' span[class="text-secondary"]').textContent.includes(" · modifié"))  // si la mention existe déjà (un commentaire modifié est re modifié), on n'ajoute pas la mention
          {
            document.querySelector('#communitycomments'+data.idcommunitycomment+' span[class="text-secondary"]').insertAdjacentText("beforeend", " · modifié");
          }

          // appelle de la fonction resetformafterupdate()

          resetformaftercommunitycommentupdate();

          // affichage du message communautaire modifié

          document.querySelector('#communitycommentcontent'+data.idcommunitycomment+'').innerHTML = data.updatedcommunitycomment;

          break;

          case "updatecommunitycommentnotok" :  // impossible de mettre à jour le message communautaire (serveur,bdd...)
          
          showNotification('Un problème technique est survenue lors de la modification de ce commentaire communautaire. Veuillez réessayer plus tard.', 'danger');

          // problème insertion BDD 

          break;

      }
  }
}

// envoi des données

  request.open('POST',document.querySelector('#formcommunitycomment').getAttribute('action'),true); 

  request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  request.setRequestHeader("X-CSRF-Token", csrfToken);

  request.setRequestHeader("Authtest", ""+authcommunitypost+""); // auteur du post communautaire pour test de blocage

  request.send(data);

}
})

//supprimer un commentaire communautaire

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('deletecommunitycomment')){

    var data = {
      "idcommunitycomment": e.target.getAttribute('data_idcommunitycomment'), // identifiant du commentaire communautaire
      "idcommunitypost": idcommunitypost // identifiant du message communautaire
    }
                
                let request = new XMLHttpRequest();

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {

                      switch(request.response)
                    {

                      case "deletecommunitycommentok" :  // suppression d'un commentaire communautaire réussi 

                      // on récupère la div contenant le commentaire communautaire
                      
                      const divcommunitycomment = document.querySelector('#communitycomments'+e.target.getAttribute('data_idcommunitycomment')); 

                      // suppression de la div contenant le commentaire communautaire

                      divcommunitycomment.parentNode.removeChild(divcommunitycomment);
                    
                      // mise à jour nombre de commentaire communautaire : décrémentation
                    
                      document.querySelector('#nb_community_comment').textContent --;

                      // si il n'y as plus de commentaire communautaire, on affiche une div indiquant qu'il n'y a pas de commentaire communautaire

                      // pour ce message communautaire

                        if(document.querySelector('#nb_community_comment').textContent == 0)
                      {
                        document.querySelector('.startcommunitycomment').insertAdjacentHTML('afterend',"<div class=\"alert alert-primary nocommunitycomment\" role=\"alert\">Aucun commentaire pour ce message communautaire.</div>");
                      }

                      // notification

                      showNotification('Commentaire supprimé.', 'success');
                      
                       break;
              
                       case "deletecommunitycommentnotok" : // impossible de supprimer un commentaire communautaire (serveur,bdd, pas l'auteur du commentaire,...) 

                       // notification
                       
                       showNotification('Impossible de supprimer ce commentaire communautaire.', 'danger');
              
                        break;
 
                    }
                }
              }

              // envoi des données

                request.open('POST', '/youtux/c/comments/deletecommunitycomment',true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader("X-CSRF-Token", csrfToken);
                
                request.send(JSON.stringify(data));
  
              }
            })

// modifier un message communautaire

// préparation formulaire et textarea

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('updatecommunitycomment')){

    // récupère le contenu du commentaire communautaire

    var communitycommentvalue = document.querySelector('#communitycommentcontent'+e.target.getAttribute('data_idcommunitycomment')+'');

    // on clone le contenu du commentaire communautaire

    //var clonecommunitycommentcontent = communitycommentvalue.cloneNode(true);

    // on remplace tous les emoji par leur alt

      // on clone d'abord le contenu du commentaire

    var clonecommcontent = communitycommentvalue.cloneNode(true);

      // pour chaque élément 'img' (emoji) on le remplace par son alt

    clonecommcontent.querySelectorAll('img').forEach(
      el =>
      {
        el.replaceWith(document.createTextNode(el.alt))
      }
    );

    // on insère le contenu du commentaire communautaire dans l'input

    document.querySelector('#content').value = clonecommcontent.innerHTML;

    // si j'appuie sur modifier une deuxième fois ou sur un autre commentaire communautaire je met à jour le champ caché avec le commentaire communautaire id à mettre à jour

      if(document.querySelector('#formcommunitycomment').querySelector('input[name="communitycommenttoupdate"]'))
    {
 
     document.querySelector('#formcommunitycomment').querySelector('input[name="communitycommenttoupdate"]').value = e.target.getAttribute('data_idcommunitycomment');
 
    }
 
       // sinon on crée un nouvel input caché avec cette valeur
 
     else
    {
      let inputcommunitycomment = document.createElement("input");
 
      inputcommunitycomment.type = "hidden";
 
      inputcommunitycomment.name = "communitycommenttoupdate";
 
      inputcommunitycomment.value = e.target.getAttribute('data_idcommunitycomment');
 
      document.querySelector('#content').parentNode.insertBefore(inputcommunitycomment, document.querySelector('#content').nextSibling);
    }

    // on scroll vers l'input

    document.querySelector('#content').scrollIntoView();

    // focus sur l'input

    document.querySelector('#content').focus();

    // modification de l'url de destination

    document.querySelector('#formcommunitycomment').action = '/youtux/c/comments/updatecommunitycomment';

    // on modifie le bouton d'envoi d'aide

    document.querySelector('#commentHelp').value = ' Appuyez sur Entrée pour envoyer votre commentaire modifié.';

    // suppression du précédent bouton d'annulation si il existe

      if(document.querySelector('input[type="reset"]'))
    {
        document.querySelector('input[type="reset"]').remove();
    }

    // création d'un bouton d'annulation si il n'existe pas

      if(!document.querySelector('button[name="linkcancelupdatecommunitycomment"]'))
    {
      let linkcancelupdatecommunitycomment = document.createElement("button");

      linkcancelupdatecommunitycomment.name = "linkcancelupdatecommunitycomment";

      linkcancelupdatecommunitycomment.href = "#";

      linkcancelupdatecommunitycomment.textContent = "Annuler";

      linkcancelupdatecommunitycomment.className = 'btn btn-secondary';

      document.querySelector('#formcommunitycomment').parentNode.insertBefore(linkcancelupdatecommunitycomment, document.querySelector('#formcommunitycomment').nextSibling)

    }

  }
});

// traitement bouton d'annulation de modification

document.addEventListener('click',function(e){

  if(e.target && e.target.name == 'linkcancelupdatecommunitycomment')
{
  resetformaftercommunitycommentupdate();
}
})

// fonction qui va réinitialiser le formulaire après une mise à jour d'un commentaire communautaire : 

// soit au click sur le bouton d'annulation 

// soit après une validatipon de modification

  function resetformaftercommunitycommentupdate()
{

  document.querySelector('button[name="linkcancelupdatecommunitycomment"]').remove();

  // vidage du textarea

  document.querySelector('#content').value = '';

  // suppression du champ caché
  
  document.querySelector('#formcommunitycomment').removeChild(document.querySelector('input[name="communitycommenttoupdate"]'));
  
  // mise à jour de l'action du formulaire
  
  document.querySelector('#formcommunitycomment').action = '/youtux/c/comments/newcommunitycomment';

}

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
                      
                            document.querySelector('.container').innerHTML = '<div class="alert alert-success" role="alert">'+
                                                                                'Message communautaire supprimé.'+
                                                                              '</div>';
                    
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