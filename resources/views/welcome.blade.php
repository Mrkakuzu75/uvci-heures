<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UVCI - Gestion des Heures</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-bg { background: linear-gradient(135deg, #F3E8FF 0%, #E8F5E9 100%); }
        .navbar-hero { background: linear-gradient(135deg, #5B2E8E 0%, #2E7D32 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="navbar-hero shadow-xl fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <span class="text-[#5B2E8E] font-heading font-bold text-2xl">U</span>
                </div>
                <span class="text-white font-heading text-xl font-bold">UVCI</span>
            </div>
            <button onclick="showLoginModal()" class="bg-white text-[#5B2E8E] hover:bg-gray-100 font-semibold px-6 py-2.5 rounded-xl transition">
                Connexion
            </button>
        </div>
    </nav>

    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-gray-900 leading-tight mb-6">
                Gestion des <span class="text-[#5B2E8E]">Heures d'Enseignement</span><br>
                <span class="text-[#2E7D32]">UVCI</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">Plateforme officielle de suivi, de validation et de paiement</p>
            <button onclick="showLoginModal()" class="bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white font-semibold px-8 py-4 rounded-2xl shadow-lg hover:brightness-105 transition">
                Commencer
            </button>
        </div>
    </section>

    <!-- Modal Connexion -->
    <div id="loginModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-[100] p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 relative">
            <button onclick="hideLoginModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">✕</button>
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-[#5B2E8E] to-[#2E7D32] rounded-2xl flex items-center justify-center">
                    <span class="text-white font-heading font-bold text-3xl">U</span>
                </div>
            </div>
            <h2 class="text-3xl font-heading font-bold text-center mb-1">Bienvenue</h2>
            <p class="text-center text-gray-500 mb-8">Connectez-vous à votre espace</p>

            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm mb-6">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="secretaire">
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <button type="button" onclick="selectRole(this, 'administrateur')" class="role-btn border-2 border-gray-200 hover:border-[#5B2E8E] rounded-xl py-3 text-sm">Admin</button>
                    <button type="button" onclick="selectRole(this, 'secretaire')" class="role-btn border-2 border-[#5B2E8E] bg-[#5B2E8E]/10 rounded-xl py-3 text-sm">Secrétaire</button>
                    <button type="button" onclick="selectRole(this, 'enseignant')" class="role-btn border-2 border-gray-200 hover:border-[#5B2E8E] rounded-xl py-3 text-sm">Enseignant</button>
                </div>
                <div class="space-y-4">
                    <input type="email" name="email" placeholder="Email" class="form-input" value="{{ old('email') }}" required>
                    <input type="password" name="password" placeholder="Mot de passe" class="form-input" required>
                </div>
                <button type="submit" class="mt-8 w-full bg-gradient-to-r from-[#5B2E8E] to-[#2E7D32] text-white font-semibold py-4 rounded-xl hover:brightness-105">Se connecter</button>
            </form>
        </div>
    </div>

    <script>
        function showLoginModal() { document.getElementById('loginModal').classList.remove('hidden'); document.getElementById('loginModal').classList.add('flex'); }
        function hideLoginModal() { document.getElementById('loginModal').classList.add('hidden'); document.getElementById('loginModal').classList.remove('flex'); }
        function selectRole(btn, role) {
            document.querySelectorAll('.role-btn').forEach(b => { b.classList.remove('border-[#5B2E8E]', 'bg-[#5B2E8E]/10'); b.classList.add('border-gray-200'); });
            btn.classList.add('border-[#5B2E8E]', 'bg-[#5B2E8E]/10');
            document.getElementById('selectedRole').value = role;
        }
        @if($errors->any()) window.onload = () => showLoginModal(); @endif
    </script>
</body>
</html>