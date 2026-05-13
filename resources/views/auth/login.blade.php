<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UVCI — Connexion</title>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<style>
/* ════════════════════════════════════════════════════════════
   RESET & VARIABLES
════════════════════════════════════════════════════════════ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --g:  #00C07F;
  --gd: #009962;
  --gl: #E6FBF3;
  --o:  #FF6B35;
  --n:  #0D1B2A;
  --n2: #132233;
  --n3: #1E3347;
  --mu: #6B7A8D;
  --bo: #E2E8F0;
}
html,body{height:100%;overflow:hidden;
  font-family:'Segoe UI',system-ui,-apple-system,Arial,sans-serif;}
body{background:var(--n);}

/* ════════════════════════════════════════════════════════════
   FOND COMMUN AUX 2 PAGES
════════════════════════════════════════════════════════════ */
@keyframes blob{
  0%,100%{transform:translate(0,0) scale(1) rotate(0deg)}
  33%    {transform:translate(30px,-20px) scale(1.08) rotate(3deg)}
  66%    {transform:translate(-20px,25px) scale(.95) rotate(-2deg)}
}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.35;transform:scale(.7)}}

.bg-blobs{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
.blob{position:absolute;border-radius:50%;filter:blur(100px);opacity:.09}
.b1{width:760px;height:760px;background:var(--g);top:-280px;left:-200px;
    animation:blob 18s ease-in-out infinite}
.b2{width:560px;height:560px;background:var(--o);bottom:-220px;right:-180px;
    animation:blob 14s ease-in-out infinite reverse;animation-delay:-6s}
.b3{width:400px;height:400px;background:#5B8DEF;top:30%;left:30%;
    animation:blob 11s ease-in-out infinite;animation-delay:-4s}

.bg-grid{position:fixed;inset:0;z-index:0;
  background:
    linear-gradient(rgba(255,255,255,.022) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.022) 1px,transparent 1px);
  background-size:60px 60px}

/* ════════════════════════════════════════════════════════════
   STAGE  (les 2 pages empilées)
════════════════════════════════════════════════════════════ */
.stage{position:fixed;inset:0;z-index:1}

/* ════════════════════════════════════════════════════════════
   PAGE 1 — ACCUEIL
════════════════════════════════════════════════════════════ */
#p1{
  position:absolute;inset:0;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  padding:48px 28px 32px;text-align:center;
  transition:opacity .65s cubic-bezier(.4,0,.2,1),
             transform .65s cubic-bezier(.4,0,.2,1);
}
#p1.out{opacity:0;transform:translateY(-18px) scale(.97);pointer-events:none}

/* Logo absolu en haut */
.logo{
  position:absolute;top:34px;left:50%;transform:translateX(-50%);
  display:flex;align-items:center;gap:12px;white-space:nowrap
}
.lm{width:40px;height:40px;background:var(--g);border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-weight:900;font-size:16px;color:var(--n);flex-shrink:0}
.ln{font-weight:700;font-size:14.5px;color:#fff;display:block}
.ls{font-size:10.5px;color:rgba(255,255,255,.36);display:block;margin-top:1px}

/* Badge pill */
.pill{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(0,192,127,.1);
  border:1px solid rgba(0,192,127,.25);
  color:var(--g);font-size:10.5px;font-weight:600;
  letter-spacing:2px;text-transform:uppercase;
  padding:7px 18px;border-radius:50px;margin-bottom:34px
}
.pdot{width:6px;height:6px;background:var(--g);border-radius:50%;
  animation:pulse 2.4s ease-in-out infinite;flex-shrink:0}

/* Titre principal */
.ht{
  font-weight:800;font-size:54px;line-height:1.07;
  color:#fff;letter-spacing:-2.5px;margin-bottom:22px;
  max-width:700px
}
.ht b{
  color:transparent;
  background:linear-gradient(135deg,var(--g) 0%,#00e5c9 100%);
  -webkit-background-clip:text;background-clip:text
}

/* Desc */
.hd{
  font-size:15.5px;color:rgba(255,255,255,.48);
  line-height:1.8;max-width:480px;margin:0 auto 50px
}

/* CTA */
.cta{
  display:inline-flex;align-items:center;gap:11px;
  background:var(--g);color:var(--n);
  font-weight:700;font-size:15px;padding:15px 38px;
  border-radius:14px;border:none;cursor:pointer;font-family:inherit;
  letter-spacing:.2px;
  box-shadow:0 0 40px rgba(0,192,127,.3);
  transition:background .2s,transform .25s,box-shadow .25s
}
.cta:hover{background:#00dc96;transform:translateY(-3px);
  box-shadow:0 14px 44px rgba(0,192,127,.5)}
.cta:active{transform:translateY(-1px)}
.cta svg{width:17px;height:17px;transition:transform .3s}
.cta:hover svg{transform:translateX(6px)}



/* ════════════════════════════════════════════════════════════
   PAGE 2 — FORMULAIRE
════════════════════════════════════════════════════════════ */
#p2{
  position:absolute;inset:0;
  display:flex;align-items:center;justify-content:center;
  padding:20px;overflow-y:auto;
  opacity:0;pointer-events:none;
  transition:opacity .6s cubic-bezier(.4,0,.2,1),
             transform .6s cubic-bezier(.4,0,.2,1);
  transform:translateY(24px)
}
#p2.in{opacity:1;pointer-events:all;transform:translateY(0)}

/* Card glassmorphism léger */
.card{
  width:100%;max-width:420px;
  background:rgba(255,255,255,.96);
  border-radius:24px;
  padding:44px 40px;
  position:relative;
  /* ombre profonde */
  box-shadow:
    0 0 0 1px rgba(255,255,255,.06),
    0 8px 20px rgba(0,0,0,.15),
    0 40px 80px rgba(0,0,0,.5);
}

/* Barre top gradient */
.card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--g) 0%,#00d8ff 45%,var(--o) 100%);
  border-radius:24px 24px 0 0
}

/* Bouton retour */
.back{
  display:inline-flex;align-items:center;gap:7px;
  background:rgba(13,27,42,.06);border:none;
  color:var(--mu);font-size:12.5px;
  cursor:pointer;padding:7px 12px 7px 10px;
  border-radius:8px;margin-bottom:24px;font-family:inherit;
  transition:background .15s,color .15s
}
.back:hover{background:rgba(13,27,42,.1);color:var(--n)}
.back svg{width:13px;height:13px}

/* Titres form */
.fey{font-size:10.5px;font-weight:700;color:var(--g);
  letter-spacing:2px;text-transform:uppercase;margin-bottom:7px}
.ftt{font-weight:800;font-size:26px;color:var(--n);
  margin-bottom:5px;letter-spacing:-.8px}
.fst{font-size:13px;color:var(--mu);margin-bottom:26px;line-height:1.55}

/* Erreur */
.err{
  background:#FFF0F0;border:1px solid #FFCDD2;color:#C62828;
  border-radius:10px;padding:11px 13px;margin-bottom:16px;
  font-size:12.5px;display:flex;align-items:center;gap:8px
}

/* ── Sélecteur de rôle ─────────────────────────────────────*/
.rlbl{display:block;font-size:12.5px;font-weight:700;
  color:var(--n);margin-bottom:9px}
.rgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;margin-bottom:22px}
.rgrid label{cursor:pointer;display:block}
.rgrid input[type=radio]{display:none}

.rc{
  display:flex;flex-direction:column;align-items:center;
  padding:12px 4px 10px;
  border:1.5px solid var(--bo);border-radius:14px;
  background:#F8FAFC;
  transition:border-color .18s,background .18s,transform .18s;
  position:relative;user-select:none
}
.rc:hover{transform:translateY(-1px)}
.rgrid input:checked+.rc{
  border-color:var(--g);
  background:linear-gradient(160deg,#f0fdf8 0%,var(--gl) 100%);
  box-shadow:0 4px 14px rgba(0,192,127,.15)
}
.rgrid label:hover .rc{border-color:rgba(0,192,127,.4)}

/* Coche */
.rchk{
  display:none;position:absolute;top:6px;right:6px;
  width:14px;height:14px;background:var(--g);border-radius:50%;
  align-items:center;justify-content:center
}
.rgrid input:checked+.rc .rchk{display:flex}

.rico{font-size:21px;margin-bottom:5px;filter:drop-shadow(0 2px 3px rgba(0,0,0,.1))}
.rnam{font-size:10.5px;font-weight:600;color:var(--mu)}
.rgrid input:checked+.rc .rnam{color:var(--gd)}

/* ── Champs ─────────────────────────────────────────────── */
.field{margin-bottom:14px}
.field label{display:block;font-size:12.5px;font-weight:700;
  color:var(--n);margin-bottom:5px}
.iw{position:relative}
.ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);
  color:#b8c1cc;pointer-events:none;width:15px;height:15px}
.fi{
  width:100%;padding:12px 40px;
  border:1.5px solid var(--bo);border-radius:12px;
  font-size:13.5px;color:var(--n);background:#F8FAFC;
  outline:none;font-family:inherit;
  transition:border-color .2s,background .2s,box-shadow .2s
}
.fi:focus{
  border-color:var(--g);background:#fff;
  box-shadow:0 0 0 3.5px rgba(0,192,127,.13)
}
.fi::placeholder{color:#c4ccd6}
.eyebtn{
  position:absolute;right:11px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;padding:5px;
  line-height:0;color:#b8c1cc;transition:color .15s
}
.eyebtn:hover{color:var(--mu)}
.eyebtn:focus{outline:none}

/* ── Options ─────────────────────────────────────────────── */
.opts{margin-bottom:20px}
.rem{display:flex;align-items:center;gap:7px;
  font-size:12.5px;color:var(--mu);cursor:pointer;user-select:none}
.rem input{accent-color:var(--g);width:14px;height:14px}

/* ── Submit ──────────────────────────────────────────────── */
.sub{
  width:100%;padding:14px;background:var(--n);color:#fff;
  border:none;border-radius:13px;font-size:14.5px;font-weight:700;
  cursor:pointer;letter-spacing:.2px;font-family:inherit;
  transition:background .2s,transform .2s,box-shadow .2s
}
.sub:hover{background:var(--n2);transform:translateY(-2px);
  box-shadow:0 12px 32px rgba(13,27,42,.35)}
.sub:active{transform:translateY(0);box-shadow:none}

/* Pied */
.ffoot{text-align:center;margin-top:16px;font-size:11.5px;color:#a0aab4}
.ffoot strong{color:var(--gd)}

/* ════════════════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════════════════ */
@media(max-width:600px){
  .ht{font-size:34px;letter-spacing:-1.5px}
  .hd{font-size:14px}
  .stats{gap:22px;bottom:24px}
  .sv{font-size:22px}
  .card{padding:36px 26px;border-radius:18px}
  .pill{margin-bottom:22px}
}
</style>
</head>
<body>

{{-- Fond commun --}}
<div class="bg-blobs">
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="blob b3"></div>
</div>
<div class="bg-grid"></div>

<div class="stage">

  {{-- ══════════════ PAGE 1 — ACCUEIL ══════════════ --}}
  <div id="p1">

    {{-- Logo --}}
    <div class="logo">
      <div class="lm">UV</div>
      <div>
        <span class="ln">UVCI</span>
        <span class="ls">Université Virtuelle de Côte d'Ivoire</span>
      </div>
    </div>

    {{-- Contenu centré --}}
    <div class="pill">
      <span class="pdot"></span>
      Système de gestion
    </div>

    <h1 class="ht">
      Gestion des heures<br>
      d'enseignants de <b>L'UVCI</b>
    </h1>

    <p class="hd">
      Plateforme centralisée permettant la gestion automatisée des heures
      d'enseignement et des activités pédagogiques des enseignants.
    </p>

    <button class="cta" onclick="showLogin()">
      Se connecter
      <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
      </svg>
    </button>



  </div>

  {{-- ══════════════ PAGE 2 — FORMULAIRE ══════════════ --}}
  <div id="p2">
    <div class="card">

      {{-- Retour --}}
      <button class="back" onclick="showAccueil()">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour à l'accueil
      </button>

      <p class="fey">Accès sécurisé</p>
      <h2 class="ftt">Bienvenue 👋</h2>
      <p class="fst">Connectez-vous à votre espace de gestion pédagogique.</p>

      {{-- Erreurs --}}
      @if($errors->any())
      <div class="err">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Sélecteur de rôle --}}
        <span class="rlbl">Je suis…</span>
        @php $or = old('role','secretaire'); @endphp
        <div class="rgrid">
          @foreach([
            ['administrateur','⚙️','Administrateur'],
            ['secretaire',    '📋','Secrétaire'],
            ['enseignant',    '👨‍🏫','Enseignant'],
          ] as [$val,$ico,$lbl])
          <label>
            <input type="radio" name="role" value="{{ $val }}" {{ $or===$val?'checked':'' }}>
            <div class="rc">
              <div class="rchk">
                <svg width="8" height="8" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <span class="rico">{{ $ico }}</span>
              <span class="rnam">{{ $lbl }}</span>
            </div>
          </label>
          @endforeach
        </div>

        {{-- Email --}}
        <div class="field">
          <label>Adresse email</label>
          <div class="iw">
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" name="email" value="{{ old('email') }}"
              placeholder="votre@uvci.edu.ci" class="fi" required>
          </div>
        </div>

        {{-- Mot de passe --}}
        <div class="field">
          <label>Mot de passe</label>
          <div class="iw">
            <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="pwd" name="password"
              placeholder="••••••••" class="fi" required>
            <button type="button" class="eyebtn" onclick="togglePwd()">
              <svg id="eyeico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Options --}}
        <div class="opts">
          <label class="rem">
            <input type="checkbox" name="remember" checked>
            Se souvenir de moi
          </label>
        </div>

        <button type="submit" class="sub">Se connecter</button>
      </form>

      <p class="ffoot">Accès réservé au personnel autorisé de l'<strong>UVCI</strong></p>
    </div>
  </div>

</div>

<script>
function showLogin(){
  document.getElementById('p1').classList.add('out');
  document.getElementById('p2').classList.add('in');
  setTimeout(function(){
    var e=document.querySelector('input[name="email"]');
    if(e) e.focus();
  },620);
}
function showAccueil(){
  document.getElementById('p2').classList.remove('in');
  document.getElementById('p1').classList.remove('out');
}
function togglePwd(){
  var i=document.getElementById('pwd'), s=document.getElementById('eyeico');
  if(i.type==='password'){
    i.type='text';
    s.innerHTML='<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
  } else {
    i.type='password';
    s.innerHTML='<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
}
@if($errors->any())
document.addEventListener('DOMContentLoaded', showLogin);
@endif
</script>
</body>
</html>
