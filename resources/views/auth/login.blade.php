<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UVCI - Gestion des Heures d'Enseignement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
        }
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(90deg, #5B2E8E 0%, #2E7D32 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }

        .hero-bg {
            background: linear-gradient(135deg, #F3E8FF 0%, #E8F5E9 100%);
        }

        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .login-card {
            animation: slideUp 0.4s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- BARRE HORIZONTALE FIXE -->
    <nav class="navbar shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-inner">
                    <span class="text-[#5B2E8E] font-bold text-2xl">U</span>
                </div>
                <div>
                    <span class="text-white font-heading text-xl font-bold tracking-tight">UVCI</span>
                </div>
            </div>


            <button onclick="showLoginModal()" 
                    class="bg-white text-[#5B2E8E] hover:bg-gray-100 font-semibold px-6 py-2.5 rounded-xl flex items-center gap-2 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7" />
                </svg>
                Connexion
            </button>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-bg pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-6 text-center pt-12">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 leading-tight mb-6">
                Gestion des <span class="text-[#5B2E8E]">Heures d'Enseignement</span><br>
                <span class="text-[#2E7D32]">Université Virtuelle de Côte d'Ivoire</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
                Plateforme officielle de suivi, de validation et de paiement des activités pédagogiques des enseignants.
            </p>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="py-16 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="feature-card bg-white rounded-3xl p-8 border border-gray-100">
                <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#5B2E8E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-9h6m-6 6h6" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Suivi Automatisé</h3>
                <p class="text-gray-600">Calcul automatique des volumes horaires selon la grille UVCI.</p>
            </div>

            <div class="feature-card bg-white rounded-3xl p-8 border border-gray-100">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#2E7D32]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 01-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Validation & Paiement</h3>
                <p class="text-gray-600">Gestion complète des validations et états de paiement.</p>
            </div>

            <div class="feature-card bg-white rounded-3xl p-8 border border-gray-100">
                <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#5B2E8E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17l4-4m0 0l-4-4m4 4V3m-4 4V3" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Exports Professionnels</h3>
                <p class="text-gray-600">PDF et Excel pour fiches individuelles et rapports globaux.</p>
            </div>
        </div>
    </section>

    <!-- MODAL CONNEXION -->
    <div id="loginModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-[100] p-4">
        <div class="login-card bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 relative">
            <button onclick="hideLoginModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12" />
                </svg>
            </button>

            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-[#5B2E8E] to-[#2E7D32] rounded-2xl flex items-center justify-center">
                    <span class="text-white font-bold text-3xl">UV</span>
                </div>
            </div>

            <h2 class="text-3xl font-heading font-bold text-center text-gray-900 mb-1">Bienvenue</h2>
            <p class="text-center text-gray-500 mb-8">Connectez-vous à votre espace</p>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="secretaire">

                <!-- Rôles -->
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <button type="button" onclick="selectRole(this, 'administrateur')" class="role-btn border-2 border-transparent hover:border-[#5B2E8E] rounded-2xl py-3 text-sm font-medium transition">Admin</button>
                    <button type="button" onclick="selectRole(this, 'secretaire')" class="role-btn border-2 border-[#5B2E8E] bg-violet-50 rounded-2xl py-3 text-sm font-medium transition">Secrétaire</button>
                    <button type="button" onclick="selectRole(this, 'enseignant')" class="role-btn border-2 border-transparent hover:border-[#5B2E8E] rounded-2xl py-3 text-sm font-medium transition">Enseignant</button>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#5B2E8E] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" name="password" required
                               class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#5B2E8E] transition">
                    </div>
                </div>

                <button type="submit" 
                        class="mt-8 w-full bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white font-semibold py-4 rounded-2xl hover:brightness-105 transition">
                    Se connecter
                </button>
            </form>
        </div>
    </div>

    <script>
        function showLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.getElementById('loginModal').classList.add('flex');
        }

        function hideLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function selectRole(btn, role) {
            document.querySelectorAll('.role-btn').forEach(b => {
                b.classList.remove('border-[#5B2E8E]', 'bg-violet-50');
            });
            btn.classList.add('border-[#5B2E8E]', 'bg-violet-50');
            document.getElementById('selectedRole').value = role;
        }

        // Ouvrir automatiquement si erreur
        @if($errors->any())
        window.onload = () => showLoginModal();
        @endif

        // Fermer avec Échap
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") hideLoginModal();
        });
    </script>
</body>
</html>