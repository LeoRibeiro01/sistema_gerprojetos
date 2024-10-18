<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Sistema de Gerenciamento de Projetos</title>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #001f3f; /* Azul marinho */
            padding: 0.3% 2rem; /* Aumenta o padding da navbar */
        }
        .navbar-brand img {
            height: 60px; /* Ajuste o tamanho conforme necessário */
        }
        .navbar-nav .nav-link {
            color: white;
            padding: 0.75rem 1.5rem; /* Aumenta o padding dos links */
        }
        .navbar-nav .nav-link:hover {
            color: #d3d3d3;
        }
        .hero-section {
            background: rgba(0, 0, 0, 0.6) url('https://static.vecteezy.com/ti/fotos-gratis/p2/2978358-close-up-de-pessoas-trabalhando-no-escritorio-gratis-foto.jpg') no-repeat center center;
            background-size: cover;
            padding: 150px 0; /* Aumenta o padding vertical */
            text-align: center;
            color: #fff;
        }
        .hero-section h1 {
            font-size: 3.5rem; /* Aumenta o tamanho da fonte */
            margin-bottom: 20px;
            text-shadow: 6px 6px 12px rgba(0, 0, 0, 0.9); /* Contorno mais pronunciado */
        }
        .hero-section p {
            font-size: 1.75rem; /* Aumenta o tamanho da fonte */
            margin-bottom: 40px;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.9); /* Contorno mais pronunciado */
        }
        .btn-primary {
            background-color: #0056b3; /* Cor do botão */
            border: none;
        }
        .btn-primary:hover {
            background-color: #004494; /* Cor do botão ao passar o mouse */
        }
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="https://infotech-solucoes.com/novo/public/img/logo_infotech.png" alt="Infotech Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projeto.index') }}">Projetos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tarefas.index') }}">Tarefas</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Cadastro</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Sistema de Gerenciamento de Projetos</h1>
            <p>Gerencie seus projetos e tarefas com eficiência.</p>
            <a href="{{ route('projeto.index') }}" class="btn btn-primary btn-lg">Ver Projetos</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
