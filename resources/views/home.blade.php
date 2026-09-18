<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATLAS — Gestão de Projetos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased flex flex-col">
    @include('layouts.navigation')

    <div class="relative overflow-hidden">
        <div class="atlas-orb atlas-orb-one"></div>
        <div class="atlas-orb atlas-orb-two"></div>

        <main class="relative flex-1 px-6 pb-14 pt-10 sm:px-8 lg:pb-20 lg:pt-16">
            <div class="mx-auto max-w-7xl">
                <section class="grid items-center gap-12 lg:grid-cols-[1.08fr_0.92fr]">
                    <div class="text-center lg:text-left">
                        <img src="{{ asset('images/atlas-brand-transparent.png') }}" alt="ATLAS Project Management System" class="mx-auto h-auto w-64 object-contain lg:mx-0">

                        <h1 class="mt-6 text-4xl font-black tracking-[-0.06em] text-slate-900 sm:text-5xl lg:text-7xl">
                            A solução de gerenciamento que a sua equipe precisa.
                        </h1>

                        <p class="mx-auto mt-5 max-w-xl text-lg leading-8 text-slate-600 lg:mx-0">
                            Planejamento, execução, acompanhamento e entrega em um só lugar para times de tecnologia, operação e clientes.
                        </p>

                        <div class="mt-8 flex flex-wrap justify-center gap-4 lg:justify-start">
                            @guest
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_20px_40px_rgba(15,23,42,0.18)] transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700">
                                    Começar agora
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white/80 px-6 py-3 text-sm font-semibold text-slate-700 shadow-[0_12px_28px_rgba(15,23,42,0.08)] backdrop-blur-sm transition duration-300 hover:-translate-y-0.5 hover:border-sky-300 hover:text-sky-700">
                                    Entrar
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_20px_40px_rgba(15,23,42,0.18)] transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700">
                                    Ir para o dashboard
                                </a>
                            @endguest
                        </div>

                    </div>

                    <div class="relative flex justify-center lg:justify-end">
                        <div class="atlas-panel atlas-float relative w-full max-w-xl overflow-hidden rounded-[32px] border border-slate-200 bg-white p-3 shadow-[0_35px_90px_rgba(15,23,42,0.18)]">
                            <div class="absolute -left-10 top-10 h-24 w-24 rounded-full bg-sky-300/80 blur-2xl"></div>
                            <div class="absolute -right-8 bottom-8 h-24 w-24 rounded-full bg-blue-500/80 blur-2xl"></div>

                            <img
                                src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80"
                                alt="Briefing de equipe"
                                class="h-[420px] w-full rounded-[24px] object-cover"
                            >

                            <div class="atlas-card atlas-card-top absolute left-7 top-7 rounded-2xl border border-white/30 bg-slate-900/85 p-3 text-white shadow-xl backdrop-blur-md">
                                <div class="text-[10px] uppercase tracking-[0.2em] text-sky-200">Sprints</div>
                                <div class="mt-2 text-2xl font-bold">12</div>
                                <div class="mt-1 text-xs text-slate-300">em andamento</div>
                            </div>

                            <div class="atlas-card atlas-card-bottom absolute bottom-7 right-7 rounded-2xl border border-sky-200/50 bg-white/85 p-3 shadow-xl backdrop-blur-md">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700">✓</div>
                                    <div>
                                        <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500">Status</div>
                                        <div class="text-sm font-bold text-slate-900">Entrega no prazo</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-16 grid gap-5 md:grid-cols-3">
                    <div class="atlas-feature-card rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-[0_18px_40px_rgba(15,23,42,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-xl text-sky-700">📊</div>
                        <h3 class="mt-4 text-xl font-bold text-slate-900">Visão executiva</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Indicadores claros, acompanhamento em tempo real e decisões mais rápidas para toda a operação.</p>
                    </div>

                    <div class="atlas-feature-card rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-[0_18px_40px_rgba(15,23,42,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700">⚙️</div>
                        <h3 class="mt-4 text-xl font-bold text-slate-900">Fluxo integrado</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Projetos, tarefas, sprints e entregas conectados num único ambiente produtivo e centralizado.</p>
                    </div>

                    <div class="atlas-feature-card rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-[0_18px_40px_rgba(15,23,42,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-xl text-emerald-700">🚀</div>
                        <h3 class="mt-4 text-xl font-bold text-slate-900">Entrega com confiança</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Acompanhe riscos, metas e status com uma operação mais enxuta e precisa para sua equipe.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <footer class="mt-auto border-t border-slate-200 bg-slate-900 text-slate-200">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-center gap-3 px-6 py-4 text-center text-sm sm:flex-row sm:gap-6">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/atlas-logo-white.png') }}" alt="ATLAS" class="h-8 w-8 object-contain">
                <span class="font-semibold text-white">ATLAS</span>
            </div>
            <span>Contato: (41) 98477-0170</span>
            <span>leonardo.ribeiro250307@gmail.com</span>
        </div>
    </footer>
</body>
</html>
