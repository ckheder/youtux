<!-- Barre de navigation des utilisateurs non connectés -->

<header>

  <!-- Fixed navbar -->

  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">

      <a class="navbar-brand" href="/youtux"><img src="/youtux/img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top ms-5">
        Youtux</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">

      <!-- input de recherche -->

      <div class="input-group" style="width:40%;margin: 0 auto">
          <input class="form-control" id="inputsearch" type="text" placeholder="Rechercher" aria-label="Search" >

            <button type="submit" class="input-group-text bg-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
              </svg>
            </button>
      </div> 

      <!-- bouton de connexion -->

<ul class="navbar-nav">

  <li class="nav-item active">

    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" role="button"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        </svg> Connexion</a>

  </li>

</ul>
 
      </div>

    </div>

  </nav>

</header>