<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UVCI - @yield('title', 'Gestion des heures')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex flex-col">
    
    <!-- TOP BAR NAVIGATION (dégradé UVCI) -->
    <nav class="fixed top-0 left-0 right-0 z-50 shadow-lg" style="background: linear-gradient(135deg, #5B2E8E 0%, #2E7D32 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-[#5B2E8E] font-heading font-bold text-xl">U</span>
                    </div>
                    <div>
                        <span class="text-white font-heading font-bold text-lg tracking-tight">UVCI</span>
                        <p class="text-white/70 text-xs -mt-0.5">Gestion des heures</p>
                    </div>
                </div>

                <!-- Navigation par rôle -->
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        @php $role = Auth::user()->role; @endphp
                        
                        @if($role === 'administrateur')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.utilisateurs') }}" class="nav-link {{ request()->routeIs('admin.utilisateurs*') ? 'nav-link-active' : '' }}">Utilisateurs</a>
                            <a href="{{ route('admin.annees') }}" class="nav-link {{ request()->routeIs('admin.annees*') ? 'nav-link-active' : '' }}">Années académiques</a>
                            <a href="{{ route('admin.parametres') }}" class="nav-link {{ request()->routeIs('admin.parametres*') ? 'nav-link-active' : '' }}">Paramètres</a>
                            <a href="{{ route('admin.taux-horaires') }}" class="nav-link {{ request()->routeIs('admin.taux-horaires*') ? 'nav-link-active' : '' }}">Taux horaires</a>
                        
                        @elseif($role === 'secretaire')
                            <a href="{{ route('secretaire.dashboard') }}" class="nav-link {{ request()->routeIs('secretaire.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('secretaire.enseignants') }}" class="nav-link {{ request()->routeIs('secretaire.enseignants*') ? 'nav-link-active' : '' }}">Enseignants</a>
                            <a href="{{ route('secretaire.cours') }}" class="nav-link {{ request()->routeIs('secretaire.cours*') ? 'nav-link-active' : '' }}">Cours</a>
                            <a href="{{ route('secretaire.activites') }}" class="nav-link {{ request()->routeIs('secretaire.activites*') ? 'nav-link-active' : '' }}">Activités</a>
                            <a href="{{ route('secretaire.paiements') }}" class="nav-link {{ request()->routeIs('secretaire.paiements*') ? 'nav-link-active' : '' }}">Paiements</a>
                        
                        @elseif($role === 'enseignant')
                            <a href="{{ route('enseignant.dashboard') }}" class="nav-link {{ request()->routeIs('enseignant.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('enseignant.activites') }}" class="nav-link {{ request()->routeIs('enseignant.activites') ? 'nav-link-active' : '' }}">Mes activités</a>
                            <a href="{{ route('enseignant.recapitulatif') }}" class="nav-link {{ request()->routeIs('enseignant.recapitulatif') ? 'nav-link-active' : '' }}">Récapitulatif</a>
                        @endif
                    @endauth
                </div>

                <!-- User menu -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-white hover:bg-white/10 rounded-xl px-3 py-1.5 transition">
                                <div class="avatar avatar-purple">
                                    {{ strtoupper(substr(Auth::user()->login, 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-semibold">{{ Auth::user()->login }}</p>
                                    <p class="text-xs text-white/70 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display: none;">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-[#5B2E8E] hover:bg-gray-100 font-semibold px-5 py-2 rounded-xl transition">
                            Connexion
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT (padding top pour compenser la fixed navbar) -->
    <main class="flex-1 pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-400">
            © {{ date('Y') }} Université Virtuelle de Côte d'Ivoire - Gestion des heures d'enseignement
        </div>
    </footer>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>