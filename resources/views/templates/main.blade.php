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
        .contact-info {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Fundo semi-transparente */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Sombra leve */
        }
        .contact-info p {
            margin: 0;
            font-size: 1.1rem;
            color: #001f3f;
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
                        <!-- Botão para PERFIL -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                                  <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                                </svg> Perfil
                            </a>
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

    <!-- Contact Info -->
    <div class="contact-info">
        <p>Contato: (41) 3422-2717</p>
        <p>Email: paulo@infotech-solucoes.com</p>
        <p>Endereço: R. Arthur Bernardes, 453 - Alvorada, Paranaguá - PR, 83206-110</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
