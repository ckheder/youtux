//addvideo.js

// Traitement de l'envoi d'une vidéo

  // variables

let form_addvideo = document.querySelector('#formaddvideo') // récupération du formulaire

let button_submit_video = form_addvideo.querySelector('button[type=submit]') // récupération du bouton d'envoi

let buttonTextSubmitVideo = button_submit_video.textContent // récupération du texte du bouton

let inputvideofile = document.querySelector('#formFileVideo'); // input d'envoi de vidéo depuis l'ordinateur

// test si fichier img ou trop gros pour l'input d'envoi depuis l'ordinateur

//inputvideofile.addEventListener('change', (event) => {

   // var imgPath = event.target; //fichier

   // var name = imgPath.files[0].name; // nom du fichier

    //var size = imgPath.files[0].size; // taille fichier

    //var extn = imgPath.files[0].type; // extension fichier

     //if (extn == "video/mp4" || extn == "video/webm") // fichier jpg/png/gif
   // {
      // if(size > 3047171) // taille inférieur ou égale à 3mo
      //{

        //notification fichier trop gros

       // alertbox.show('<div class="w3-panel w3-red">'+
                       //'<p>Ce fichier est trop gros.</p>'+
                       //'</div>.');

                // inputvideofile.value = "";

      //}

    //}

    // else
    //{

      //notification extension fichier

      //alertbox.show('<div class="w3-panel w3-red">'+
                   //  '<p>Seuls les fichiers Jpeg/Png/Gif sont autorisés.</p>'+
                    // '</div>.');

                 //inputfile.value = ""; // on vide l'input
   // }
//});

// envoi d'une vidéo

form_addvideo.addEventListener('submit',  function (e) { // on capte l'envoi du formulaire

      e.preventDefault();

      // si aucun fichier vidéo n'est envoyé

        if(document.querySelector("#formFileVideo").files.length == 0) 
      {
        alert('Vous devez choisir une vidéo avant de cliquer sur Poster.', 'danger')
        return;
      }

      let data = new FormData(this); // on récupère les données du formulaire

      // on récupère le fichier file
  
      let file = inputvideofile.files[0];

      let allowed_mime_types = [ 'video/mp4', 'video/webm' ];

      let allowed_size_mb = 6; // limite de 6 MB

      let progressbar = document.querySelector('.progress-bar');

      // si la vidéo n'est pas au bon format
  
      if(allowed_mime_types.indexOf(file.type) == -1) 
    {
      alert('Vidéo au format incorrect ! Seules les vidéos au format mp4 et webm sont acceptées.', 'danger')
      return;
    }

      // si le fichier est trop gros
  
      if(file.size > allowed_size_mb*1024*1024) 
    {
      alert('Cette vidéo est trop grosse! Veuillez ne pas dépasser les 6mb.', 'danger')
      return;
    }
   
      // on ajoute le fichier file au formulaire

    data.append('file', file);

    button_submit_video.disabled = true // désactivation du bouton

    button_submit_video.textContent = 'Publication en cours...' // mise à jour du texte du bouton

    let request = new XMLHttpRequest();

      request.onreadystatechange = function() 
    {
        if (request.readyState == 4 && request.status == 200) // si tout est bon
      {

            switch(request.response)
          {
            case "newvideook" :   form_addvideo.reset(); // reset du formulaire

                                  document.querySelector('.progress').style.display = "none"; // disparition de la pbarre de progression upload
  
                                  button_submit_video.disabled = false // on réactive le bouton
  
                                  button_submit_video.textContent = buttonTextSubmitVideo// on remet le texte initial du bouton
  
                                  showNotification('Vidéo postée!', 'success') // notification de réussite

                                  // vidéo postée avec succès 

             break;

             case "uploadfail" :  document.querySelector('.progress').style.display = "none"; // disparition de la pbarre de progression upload
  
                                  button_submit_video.disabled = false // on réactive le bouton

                                  button_submit_video.textContent = buttonTextSubmitVideo// on remet le texte initial du bouton

                                  showNotification('Impossible de publier cette vidéo', 'danger') // notification d'échec

                                  // problème de traitement du fichier

              break;

              case "newvideonotok" :    document.querySelector('.progress').style.display = "none"; // disparition de la pbarre de progression upload
  
                                        button_submit_video.disabled = false // on réactive le bouton

                                        button_submit_video.textContent = buttonTextSubmitVideo// on remet le texte initial du bouton

                                        showNotification('Un problème technique est survenue lors de la publication de cette vidéo. Veuillez réessayer plus tard.', 'danger') // notification d'échec

                                  // problème insertion BDD 

              break;

          }
      }
  }

  // traitement de la progress bar pour suivre l'avancée de l'upload

    request.upload.addEventListener('progress', function(e)
  {
    document.querySelector('.progress').style.display = "block";

    var progress_width = Math.ceil(e.loaded/e.total * 100);

    progressbar.style.width = progress_width + '%';

    progressbar.innerHTML = progress_width + "%";

  });

  // envoi des données

    request.open('POST', form_addvideo.getAttribute('action'),true); 

    //request.setRequestHeader("X-CSRF-Token", csrfToken);

    request.send(data);

})




