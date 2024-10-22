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
            margin-top: 50px; /* Define a margem superior para afastar o conteúdo do meio da página */
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
            <!-- Adicionando ícone do Instagram -->
            <a class="nav-link" href="https://www.instagram.com/infotech.solucoes2/" target="_blank" style="margin-right: 20px;" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.94 0 5.668.01 4.605.048 3.543.086 2.663.322 1.947.902c-.716.579-1.134 1.387-1.398 2.35C.02 4.332 0 4.67 0 8s.02 3.668.548 4.748c.264.963.682 1.771 1.398 2.35.716.579 1.596.816 2.658.854C5.668 15.99 5.94 16 8 16s2.332-.01 3.395-.048c1.062-.038 1.942-.275 2.658-.854.716-.579 1.134-1.387 1.398-2.35C15.98 11.668 16 11.33 16 8s-.02-3.668-.548-4.748c-.264-.963-.682-1.771-1.398-2.35-.716-.58-1.596-.816-2.658-.854C10.332.01 10.06 0 8 0zM8 1.531c2.052 0 2.29.008 3.093.045.772.035 1.19.162 1.468.271.368.144.631.316.907.592.275.276.448.54.592.907.109.278.236.696.271 1.468.037.803.045 1.041.045 3.093s-.008 2.29-.045 3.093c-.035.772-.162 1.19-.271 1.468a2.278 2.278 0 0 1-.592.907c-.276.275-.54.448-.907.592-.278.109-.696.236-1.468.271-.803.037-1.041.045-3.093.045s-2.29-.008-3.093-.045c-.772-.035-1.19-.162-1.468-.271a2.278 2.278 0 0 1-.907-.592c-.276-.275-.448-.54-.592-.907-.109-.278-.236-.696-.271-1.468-.037-.803-.045-1.041-.045-3.093s.008-2.29.045-3.093c.035-.772.162-1.19.271-1.468.144-.368.316-.631.592-.907.276-.276.54-.448.907-.592.278-.109.696-.236 1.468-.271.803-.037 1.041-.045 3.093-.045zm0 2.35a4.119 4.119 0 1 0 0 8.237 4.119 4.119 0 0 0 0-8.237zm0 6.807a2.688 2.688 0 1 1 0-5.375 2.688 2.688 0 0 1 0 5.375zm4.953-6.873a.96.96 0 1 1-1.922 0 .96.96 0 0 1 1.922 0z"/>
                </svg>
            </a>

            <!-- Adicionando ícone do Facebook -->
            <a class="nav-link" href="https://www.facebook.com/infotechinfo/?locale=pt_BR" target="_blank" style="margin-right: 20px;" style="margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-facebook" viewBox="0 0 16 16">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 4.02 2.98 7.32 6.84 7.94V10.44H5.07V8h1.77V6.36c0-1.74 1.07-2.69 2.62-2.69.75 0 1.39.06 1.58.08v1.84h-1.09c-.85 0-1.02.4-1.02.99V8h2.04l-.27 2.44H8.95v5.5C12.82 15.32 16 12.02 16 8c0-4.42-3.58-8-8-8z"/>
                </svg>
            </a>

            <!-- Adicionando ícone do Whatsapp -->
            <a class="nav-link" href="https://api.whatsapp.com/send/?phone=5541998277342&text&type=phone_number&app_absent=0" target="_blank" style="margin-right: 20px;" style="margin-left: 10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-whatsapp" viewBox="0 0 16 16">
            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
            </svg>
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
            <h1>Gerencie seus projetos com eficiência</h1>
            <p>Organize, planeje e acompanhe o progresso dos seus projetos em uma única plataforma.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Comece Agora</a>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="contact-info">
        <p>Contato: (41) 3422-2717</p>
        <p>Email: paulo@infotech-solucoes.com</p>
        <p>Endereço: R. Arthur Bernardes, 453 - Alvorada, Paranaguá - PR, 83206-110</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-NMw3Z1gxRnZGH/WxqABZd+TWtJjhnHX1LpIXcfD1I4V4W/EYtq0J6p/5WV+fjEOf" crossorigin="anonymous"></script>
</body>
</html>
