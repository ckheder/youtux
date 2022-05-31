// Videopage.js
// Traitement ddes différentes actions sur la page 'view' d'une vidéo

// variables

let divcomment = document.querySelector("#comments_zone"); // div servant à accueuillir les données chargées

let spinner = document.querySelector('.spinner-border'); // spinner de chargement des données au changement d'onglets

// au chargement de la page, on appelle la page des vidéos d'un utilisateur

  if(allowcomm != 1) // si les commentaires sont activé on charge la liste des commentaires pour une vidéo donnée
{
  divcomment.addEventListener('DOMContentLoaded', loadVideoComments(''+idvideo+''));
}

// fonction de chargement des commentaires
//
// Paramètres :  videoid -> identifiant de la vidéo

  function loadVideoComments(videoid)
{

    spinner.classList.remove("visually-hidden"); // affichage du spinner de chargement

    let requesturl = new XMLHttpRequest();

      requesturl.onreadystatechange = function() 
    {
      if (requesturl.readyState == 4 && requesturl.status == 200) {

        divcomment.innerHTML = ""; // on vide la div d'affichage des commentaires

        spinner.classList.add("visually-hidden"); // suppression du spinner de chargement

        divcomment.innerHTML= this.responseText; // on charge les données dans la div

      }
  }

  // appel de l'URL

  requesturl.open('GET', '/youtux/comments/'+videoid+'/', true); 

  // envoi d'un header spécifique AJAX pour les controller CakePHP

  requesturl.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  requesturl.setRequestHeader("Authtest", ""+authvideo+""); // auteur de la vidéo pour test de blocage

  requesturl.send();
}

// ajouter un commentaire

document.addEventListener('submit', (e) => {

  if (e.target.id === 'formcomment') {

  e.preventDefault();

  let data = new FormData(); // on crée un nouvel objet formdata

  // on récupère l'URL du formulaire et on en extrait la fin

  let urlform = e.target.action; 

  const lastItem = urlform.substring(urlform.lastIndexOf('/') + 1)

  // ajout du commentaire à l(objet Formdata)

  data.append('comment', document.querySelector('#content').value);

  // si l'URL est 'newcomment'

    if(lastItem == 'newcomment')
  {
    data.append('idvideo', idvideo); // ajout de l'id de la video

    data.append('authvideo', authvideo); // auteur de la video
  }

  // si l'URL est 'updatecomm'

    else if(lastItem == 'updatecomm')
  {

    // on récupère l'id du comm à modifier

    let idcomm = document.querySelector('[name="commtoupdate"]').value;

    // on l'ajoute à l'objet Formdata

    data.append('idcomm', idcomm)
  }

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
        case "newcommok" :  // ajout d'un nouveau commentaire
        
                            document.querySelector('#content').value = ''; // reset du formulaire

      //insertion du nouveau commentaire au tout début de la div

      document.querySelector('.testcomm').insertAdjacentHTML('afterend', '<div id="comm'+data.comment['id_comm']+'">'+
                                                                          '<div class="dropdown float-end">'+
                                                                            '<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">'+
                                                                            '</button>'+
                                                                            '<ul class="dropdown-menu">'+
                                                                              '<li><a class="deletecomm dropdown-item" href="#" onclick="return false;" data_idcomm="'+data.comment['id_comm']+'">Supprimer</a></li>'+
                                                                              '<li><a class="updatecomment dropdown-item" href="#" onclick="return false;" data_idcomm="'+data.comment['id_comm']+'"> Modifier</a></li>'+
                                                                            '</ul>'+
                                                                          '</div>'+
                                                                          '<img src="/youtux/users/'+data.comment['user_comm']+'/'+data.comment['user_comm']+'.jpg" alt="image utilisateur" class="d-inline rounded-circle me-1" width="48" height="48">'+
                                                                          '<span class="ms-2 fs-6">'+
                                                                            '<a href="/youtux/'+data.comment['user_comm']+'" class="text-decoration-none">'+data.comment['user_comm']+'</a>'+
                                                                            '<span class="text-secondary">  à l\'instant </span>'+
                                                                          '</span>'+
                                                                          '<p id="commcontent'+data.comment['id_comm']+'" class="mt-2">'+
                                                                           ''+data.comment['commentaire']+'</p><hr>'+
                                                                          '</div>');

  // incrémenation du nombre de commentaire

      document.querySelector('#nb_comm').textContent ++;

  // disparition de la div nocomm si elle existe (cas du premier commentaire d'une vidéo)

            if(document.querySelector('.nocomm'))
          {
            document.querySelector('.nocomm').remove();
          }

  // affichage d'une notification de succès

          showNotification('Commentaire posté', 'success');

         break;

         case "newvcommnotok" :  // impossible d'ajouter un commentaire : problème serveur,BDD,..

  // affichage d'une notification d'échec

         document.querySelector('#notificationcomm').innerHTML = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

         showNotification('Un problème technique est survenue lors de la publication de ce commentaire. Veuillez réessayer plus tard.', 'danger');

          break;

          case "userblocked" : //utilisateur bloqué

          showNotification(''+authvideo+' vous à bloqué, vous ne pouvez pas commenter cette vidéo', 'danger');

          break;

          case "emptycomm" :  // commentaire vide

  // affichage d'une notification
          
          showNotification('Un commentaire ne peut être vide.', 'info');

          break;

 // UPADTE COMMENT

          case "notownercomm" : // je tente de modifier un commentaire qui ne m'appartient pas

// affichage d'une notification
          
          showNotification('Ce commentaire ne vous appartient pas, il ne peut être modifié que par son auteur.', 'danger');

          break;

          case "updatecommok" : // mise à jour avec succès d'un commentaire

// affichage d'une notification
          
          showNotification('Commentaire modifié.', 'success');

// on récupère la span 'text-secondary' pour y afficher la mention modifié 

          var datecomm = document.querySelector('#comm'+data.idcomm+' span[class="text-secondary"]');

          if(!datecomm.textContent.includes(" · modifié"))  // si la mention existe déjà (un commentaire modifié est re modifié), on n'ajoute pas la mention
        {
          datecomm.insertAdjacentText("beforeend", " · modifié");
        }

// reset du formulaires

          document.querySelector('#content').value = '';

// modification de l'url de destination

          document.querySelector('#formcomment').action = '/youtux/v/newcomment';

// suppression du champ caché

          document.querySelector('#formcomment').removeChild(document.querySelector('input[name="commtoupdate"]'));

// mise à jour du texte d'aide vers sa version initiale

          document.querySelector('#commentHelp').textContent = 'Appuyez sur Entrée pour envoyer votre commentaire.';

// disparition du lien d'annulation de modification d'un commentaire

          document.querySelector('button[name="linkcancelupdatecomment"]').remove();

// affichage du comm modifié

          document.querySelector('#commcontent'+data.idcomm+'').innerHTML = data.comment;

          break;

          case "updatecommnotok" :  // impossible de mettre à jour le commentaire (serveur,bdd...)
          
          showNotification('Un problème technique est survenue lors de la modification de ce commentaire. Veuillez réessayer plus tard.', 'danger');

          // problème insertion BDD 

          break;

      }
  }
}

// envoi des données

request.open('POST',document.querySelector('#formcomment').getAttribute('action'),true); 

request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

request.setRequestHeader("X-CSRF-Token", csrfToken);

request.send(data);

}
})

//désactivation des commentaires

  if(document.querySelector('#actioncomm')) // si le bouton existe -> propriétaire de la vidéo qui visite la page
{

document.querySelector('#actioncomm').addEventListener('click',function(e){

    var data = {
                  "action": e.target.getAttribute('data-actioncomm'), // 0 -> activation des commentaires, 1 -> désactivation des commentaires
                  "idmovie": e.target.getAttribute('data-idmovie') // identifiant de la vidéo
                }

                let request = new XMLHttpRequest();

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {
                      switch(request.response)
                    {

                      case "updatestatutcommok" : // mise à jour du statut des commentaires réussis
                      
                      
                        if(e.target.getAttribute('data-actioncomm') == 0) // les commentaires sont activés donc je les désactivent : il vaut 0 donc je le passe à 1
                      {
                      
                        document.querySelector('#actioncomm').dataset.actioncomm = 1; // mise à jour du paramètre du lien du menu déroulant : ce menu envoi 1

                        document.querySelector('#actioncomm').textContent="Désactiver les commentaires"; // mise à jour du texte du lien du menu déroulant

                        document.querySelector('input[id="allowcomm"]').value = 0; // mise à jour de la valeur de l'input caché

                        document.querySelector('#comment').disabled=false; // on réactive l'input des commentaires

                        loadVideoComments(''+idvideo+''); // on réaffiche la liste des commentaires

                        showNotification('Les commentaires sont activés pour cette vidéo.', 'success'); // notification

                      }

                        else
                      {

                        document.querySelector('#actioncomm').dataset.actioncomm = 0; // mise à jour du paramètre du lien du menu déroulant

                        document.querySelector('#actioncomm').textContent="Activer les commentaires"; // mise à jour du texte du lien du menu déroulant

                        document.querySelector('input[id="allowcomm"]').value = 1; // mise à jour de la valeur de l'input caché

                        document.querySelector('#comment').disabled=true; // on désactive l'input des commentaires

                        // on remplace les commentaires par un message d'information

                        divcomment.innerHTML = '<div class="alert alert-primary" role="alert"> Les commentaires sont désactivés pour cette vidéo. </div>';

                        showNotification('Les commentaires sont désactivés pour cette vidéo.', 'success'); // notification

                      }

                       break;
              
                       case "updatestatutcommnotok" : // problème technique (serveur,bdd,...)

                       // notification 
                       
                       showNotification('Un problème technique empêche la mise à jour du statut des commentaires.Veuillez réessayer plus tard.', 'danger');
              
                        break;
                    
                    }
                }
              }

              // envoi des données

                request.open('POST', '/youtux/v/actioncomm',true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader('Content-Type', 'application/json');
                
                request.setRequestHeader("X-CSRF-Token", csrfToken);
                
                request.send(JSON.stringify(data));
  
})
}

//supprimer un commentaire

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('deletecomm')){

    var data = {
      "idcomm": e.target.getAttribute('data_idcomm'), // identifiant du commentaire
      "idmovie": idvideo // identifiant de la vidéo
    }
                
                let request = new XMLHttpRequest();

                request.onreadystatechange = function() 
              {
                  if (request.readyState == 4 && request.status == 200) // si tout est bon
                {

                      switch(request.response)
                    {

                      case "deletecommok" :  // suppression d'un commentaire réussi 
                      
                      const divcomm = document.querySelector('#comm'+e.target.getAttribute('data_idcomm')); // on récupère la div contenant le commentaire

                      divcomm.parentNode.removeChild(divcomm); // suppression de la div contenant le commentaire
                    
                      // mise à jour nombre de commentaire : décrémentation
                    
                      document.querySelector('#nb_comm').textContent --;

                      if(document.querySelector('#nb_comm').textContent == 0)
                      {
                        document.querySelector('.testcomm').insertAdjacentHTML('afterend',"<div class=\"alert alert-primary nocomm\" role=\"alert\">Aucun message communautaire à afficher.</div>");
                      }

                      // notification

                      showNotification('Commentaire supprimé.', 'success');
                      
                       break;
              
                       case "deletecommnotok" : // impossible de supprimer un commentaire (serveur,bdd, pas l'auteur du commentaire,...) 

                       // notification
                       
                       showNotification('Impossible de supprimer ce commentaire.', 'danger');
              
                        break;
                      
                    }
                }
              }

              // envoi des données

                request.open('POST', '/youtux/v/deletecomm',true); 

                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                request.setRequestHeader("X-CSRF-Token", csrfToken);
                
                request.send(JSON.stringify(data));
  
              }
            })

// MODIFIER UN COMMENTAIRE

// préparation formulaire et textarea

document.addEventListener('click',function(e){

  if(e.target && e.target.classList.contains('updatecomment')){

    // récupère le contenu du commentaire

    var commvalue = document.querySelector('#commcontent'+e.target.getAttribute('data_idcomm')+'');

    // on clone le contenu du commentaire

    var clonecommcontent = commvalue.cloneNode(true);

    // on remplace tous les emoji par leur alt

      // pour chaque élément 'img' (emoji) on le remplace par son alt

    clonecommcontent.querySelectorAll('img').forEach(
      el =>
    {
        el.replaceWith(document.createTextNode(el.alt))
      }
    );

    // on insère le contenu du commentaire dans l'input

    document.querySelector('#content').value = clonecommcontent.innerHTML;

    // si j'appuie sur modifier une deuxième fois ou sur un autre comm je met à jour le champ caché avec le comm id à mettre à jour

      if(document.querySelector('#formcomment').querySelector('input[name="commtoupdate"]'))
    {
 
     document.querySelector('#formcomment').querySelector('input[name="commtoupdate"]').value = e.target.getAttribute('data_idcomm');
 
    }
 
       // sinon on crée un nouvel input caché avec cette valeur
 
     else
    {
      let inputcomm = document.createElement("input");
 
      inputcomm.type = "hidden";
 
      inputcomm.name = "commtoupdate";
 
      inputcomm.value = e.target.getAttribute('data_idcomm');
 
      document.querySelector('#formcomment').appendChild(inputcomm);
    }

    // on scroll vers l'input

    document.querySelector('#content').scrollIntoView();

    // focus sur l'input

    document.querySelector('#content').focus();

    // modification de l'url de destination

    document.querySelector('#formcomment').action = '/youtux/v/updatecomm';

    // on modifie le texte d'aide

    document.querySelector('#commentHelp').textContent = 'Appuyez sur Entrée pour valider les modifications de votre commentaire.';

    // création d'un bouton d'annulation si il n'existe pas

      if(!document.querySelector('a[name="linkcancelupdatecomment"]'))
    {
      let linkcancelupdatecomment = document.createElement("button");

      linkcancelupdatecomment.name = "linkcancelupdatecomment";

      linkcancelupdatecomment.href = "#";

      linkcancelupdatecomment.innerHTML = "Annuler";

      linkcancelupdatecomment.className = 'btn btn-danger';

      document.querySelector('#formcomment').parentNode.insertBefore(linkcancelupdatecomment, document.querySelector('#formcomment').nextSibling)
    }
  }
});

// traitement bouton d'annulation de modification

document.addEventListener('click',function(e){

    if(e.target && e.target.name == 'linkcancelupdatecomment')
  {

    // vidage du textarea

    document.querySelector('#content').value = '';

    // suppression du champ caché

    document.querySelector('#formcomment').removeChild(document.querySelector('input[name="commtoupdate"]'));

    // mise à jour de l'action du formulaire

    document.querySelector('#formcomment').action = '/youtux/v/newcomment';

    // mise à jour du texte d'aide vers le texte initial

    document.querySelector('#commentHelp').textContent = 'Appuyez sur Entrée pour envoyer votre commentaire.';

    // disparition du lien

    e.target.remove();
  }

});



     