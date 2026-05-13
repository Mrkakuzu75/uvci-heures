<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UVCI - Gestion des heures enseignants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden">
            <!-- Entête -->
            <div class="bg-blue-700 px-6 py-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">UVCI</span>
                    </div>
                </div>
                <h1 class="text-white text-2xl font-bold">Université Virtuelle<br>de Côte d'Ivoire</h1>
                <p class="text-blue-100 mt-2 text-sm">SYSTÈME DE GESTION</p>
            </div>
            
            <!-- Corps -->
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 text-center">Gestion des heures enseignants</h2>
                <p class="text-gray-500 text-center text-sm mt-1">Plateforme centralisée pour le suivi automatisé</p>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">ACCÈS SÉCURISÉ</p>
                    <h3 class="text-lg font-semibold text-gray-800 mt-1">Bienvenue 🎉</h3>
                    <p class="text-gray-500 text-sm">Connectez-vous à votre espace de gestion pédagogique.</p>
                </div>
                
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login.post') }}" class="mt-6">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Adresse email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="votre@uvci.edu.ci" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Mot de passe</label>
                        <input type="password" name="password" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="**********" required>
                    </div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                        Se connecter
                    </button>
                </form>
                
                <div class="mt-6 text-center text-xs text-gray-400">
                    UVCI - Université Virtuelle de Côte d'Ivoire
                </div>
            </div>
        </div>
    </div>
</body>
</html>