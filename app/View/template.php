<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPREMO CRM DEV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{cssPath}}">
    
</head>
<body>
    <header>
        <nav class="navbar">
        <a class="brand" href="{{siteUrl}}">
            <img src="{{logoPath}}">
        </a>
        </nav>
    </header>

    <div class="conteudo">
        {{mensagem}}
        {{conteudo}}
    </div>

    <div class="containerAlert">
    </div>

    <div class="loading-overlay" id="loader">
        <div class="loader"></div>
    </div>
    

    <footer class="page-footer fixed-bottom">
        <div class="footer-copyright text-center py-3">© {{ano}} Copyright:
            <a href="https://supremocrm.com.br/" target="_blank"> SUPREMO CRM</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{jsPath}}"></script>
</body>
</html>