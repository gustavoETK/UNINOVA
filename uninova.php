<?php
// ═══════════════════════════════════════════════════════════════
//  UNINOVA — SISTEMA INTEGRADO DE GESTÃO ACADÊMICA v3.0
//  O maior portal universitário já codificado por uma IA
// ═══════════════════════════════════════════════════════════════
session_start();

// ─── FAKE DATABASE ───────────────────────────────────────────
$USERS = [
    'aluno' => [
        'senha'   => '1234',
        'role'    => 'aluno',
        'nome'    => 'Lucas Ferreira',
        'ra'      => '2024001234',
        'curso'   => 'Ciência da Computação',
        'periodo' => '4º Semestre',
        'avatar'  => 'LF',
        'turno'   => 'Noturno',
        'cr'      => 8.4,
        'situacao'=> 'Regular',
    ],
    'prof' => [
        'senha'   => 'prof123',
        'role'    => 'professor',
        'nome'    => 'Dra. Ana Paula Rocha',
        'ra'      => 'PROF-0042',
        'curso'   => 'Engenharia de Software',
        'periodo' => 'Docente',
        'avatar'  => 'AP',
        'turno'   => 'Integral',
        'cr'      => null,
        'situacao'=> 'Ativo',
    ],
    'coord' => [
        'senha'   => 'coord456',
        'role'    => 'coordenador',
        'nome'    => 'Prof. Ricardo Almeida',
        'ra'      => 'COORD-009',
        'curso'   => 'Administração Acadêmica',
        'periodo' => 'Coordenador',
        'avatar'  => 'RA',
        'turno'   => 'Integral',
        'cr'      => null,
        'situacao'=> 'Ativo',
    ],
];

$DISCIPLINAS = [
    ['cod'=>'CC401','nome'=>'Algoritmos Avançados',      'prof'=>'Dr. Silva',       'm1'=>7.5, 'm2'=>null, 'ch'=>80, 'faltas'=>4,  'max_faltas'=>20],
    ['cod'=>'CC402','nome'=>'Banco de Dados II',          'prof'=>'Dra. Ana Paula',  'm1'=>8.2, 'm2'=>8.8,  'ch'=>60, 'faltas'=>2,  'max_faltas'=>15],
    ['cod'=>'CC403','nome'=>'Engenharia de Software',     'prof'=>'Prof. Torres',    'm1'=>6.0, 'm2'=>null, 'ch'=>80, 'faltas'=>8,  'max_faltas'=>20],
    ['cod'=>'CC404','nome'=>'Redes de Computadores',      'prof'=>'Dra. Lima',       'm1'=>9.1, 'm2'=>7.4,  'ch'=>60, 'faltas'=>1,  'max_faltas'=>15],
    ['cod'=>'CC405','nome'=>'Inteligência Artificial',    'prof'=>'Dr. Mendes',      'm1'=>5.5, 'm2'=>null, 'ch'=>80, 'faltas'=>12, 'max_faltas'=>20],
    ['cod'=>'HU101','nome'=>'Ética e Legislação',         'prof'=>'Prof. Castro',    'm1'=>7.0, 'm2'=>8.0,  'ch'=>40, 'faltas'=>0,  'max_faltas'=>10],
];

$NOTICIAS = [
    ['tipo'=>'urgente','titulo'=>'Calendário de Provas Finais 2025/1','data'=>'28/04','resumo'=>'Confira as datas das provas finais do semestre.'],
    ['tipo'=>'normal', 'titulo'=>'Semana Acadêmica — Inscrições Abertas','data'=>'25/04','resumo'=>'Palestras, workshops e hackathon. Inscreva-se já.'],
    ['tipo'=>'normal', 'titulo'=>'Bolsas de Iniciação Científica','data'=>'20/04','resumo'=>'12 vagas disponíveis para pesquisa. Prazo: 10/05.'],
    ['tipo'=>'info',   'titulo'=>'Manutenção nos Laboratórios','data'=>'18/04','resumo'=>'Labs L3 e L4 fora de serviço sexta-feira.'],
];

$EVENTOS = [
    ['dia'=>'02','mes'=>'MAI','titulo'=>'Prova — Algoritmos Avançados','hora'=>'19h00','cor'=>'rose'],
    ['dia'=>'05','mes'=>'MAI','titulo'=>'Entrega — Projeto BD II','hora'=>'23h59','cor'=>'amber'],
    ['dia'=>'08','mes'=>'MAI','titulo'=>'Seminário de IA','hora'=>'14h00','cor'=>'sky'],
    ['dia'=>'12','mes'=>'MAI','titulo'=>'Prova — Engenharia de Software','hora'=>'19h00','cor'=>'rose'],
    ['dia'=>'15','mes'=>'MAI','titulo'=>'Palestra: Mercado Tech 2025','hora'=>'10h00','cor'=>'teal'],
    ['dia'=>'20','mes'=>'MAI','titulo'=>'Semana Acadêmica','hora'=>'08h00','cor'=>'violet'],
];

$FINANCEIRO = [
    ['mes'=>'Abril/2025',  'valor'=>1890.00,'status'=>'pago',    'venc'=>'10/04/2025'],
    ['mes'=>'Março/2025',  'valor'=>1890.00,'status'=>'pago',    'venc'=>'10/03/2025'],
    ['mes'=>'Maio/2025',   'valor'=>1890.00,'status'=>'pendente','venc'=>'10/05/2025'],
    ['mes'=>'Fevereiro/25','valor'=>1890.00,'status'=>'pago',    'venc'=>'10/02/2025'],
];

$BIBLIOTECA = [
    ['titulo'=>'Clean Code','autor'=>'Robert C. Martin',   'devol'=>'15/05/2025','status'=>'emprestado'],
    ['titulo'=>'Algoritmos','autor'=>'Cormen et al.',      'devol'=>'22/05/2025','status'=>'emprestado'],
    ['titulo'=>'Domain-Driven Design','autor'=>'Eric Evans','devol'=>'—',         'status'=>'reservado'],
];

// ─── AÇÕES ────────────────────────────────────────────────────
$erro = '';
$secao = $_GET['s'] ?? 'dashboard';
$mediaMsg = '';

// Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = trim($_POST['usuario'] ?? '');
    $p = trim($_POST['senha']   ?? '');
    if (isset($USERS[$u]) && $USERS[$u]['senha'] === $p) {
        $_SESSION['user'] = $u;
        header('Location: ?s=dashboard'); exit;
    } else { $erro = 'Usuário ou senha incorretos.'; }
}

// Logout
if (isset($_GET['logout'])) { session_destroy(); header('Location: ?'); exit; }

// Calcular Média (M1/M2)
if (isset($_POST['action']) && $_POST['action'] === 'calcmedia' && isset($_SESSION['user'])) {
    $m1 = floatval(str_replace(',','.',$_POST['m1'] ?? 0));
    $m2 = floatval(str_replace(',','.',$_POST['m2'] ?? 0));
    $m1 = max(0, min(10, $m1));
    $m2 = max(0, min(10, $m2));
    // M2 vale dobrado: média = (M1 + M2*2) / 3
    $mf = round(($m1 + $m2 * 2) / 3, 2);
    $sit = $mf >= 7 ? 'Aprovado' : ($mf >= 5 ? 'Recuperação' : 'Reprovado');
    $_SESSION['calc'] = compact('m1','m2','mf','sit');
    $secao = 'notas';
}

$logado = isset($_SESSION['user']) && isset($USERS[$_SESSION['user']]);
$user   = $logado ? $USERS[$_SESSION['user']] : null;
$calc   = $_SESSION['calc'] ?? null;

// ─── HELPERS ──────────────────────────────────────────────────
function mediaFinal($d){
    if($d['m1']===null) return null;
    if($d['m2']===null) return null;
    return round(($d['m1'] + $d['m2']*2)/3, 2);
}
function situacao($mf){
    if($mf===null) return ['—','muted'];
    if($mf>=7)  return ['Aprovado','teal'];
    if($mf>=5)  return ['Recuperação','amber'];
    return ['Reprovado','rose'];
}
function pctFaltas($f,$m){ return $m>0?round($f/$m*100):0; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNINOVA — Portal Acadêmico</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════
   TOKENS & RESET
════════════════════════════════════════ */
:root{
  --bg:        #07070d;
  --bg2:       #0d0d18;
  --bg3:       #121220;
  --card:      #111122;
  --line:      #1e1e35;
  --line2:     #2a2a45;
  --ink:       #eeeef8;
  --ink2:      #a0a0c0;
  --muted:     #50507a;
  --teal:      #00ddb0;
  --teal-d:    rgba(0,221,176,0.12);
  --teal-g:    rgba(0,221,176,0.28);
  --violet:    #8b6cf7;
  --violet-d:  rgba(139,108,247,0.12);
  --amber:     #f5a623;
  --amber-d:   rgba(245,166,35,0.12);
  --rose:      #f5617a;
  --rose-d:    rgba(245,97,122,0.12);
  --sky:       #38c8f5;
  --sky-d:     rgba(56,200,245,0.12);
  --sidebar:   240px;
  --ease:      cubic-bezier(.16,1,.3,1);
  --r:         14px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  background:var(--bg);color:var(--ink);
  font-family:'Outfit',sans-serif;font-size:15px;
  min-height:100vh;overflow-x:hidden;cursor:none;
}
a{text-decoration:none;color:inherit}
button,input,select,textarea{font-family:inherit;border:none;outline:none}
ul{list-style:none}

/* ════════════════════════════════════════
   CURSOR
════════════════════════════════════════ */
#cur{position:fixed;width:8px;height:8px;border-radius:50%;
  background:var(--teal);pointer-events:none;z-index:99999;
  transform:translate(-50%,-50%);transition:width .2s,height .2s;
  mix-blend-mode:screen}
#cur-ring{position:fixed;width:32px;height:32px;border-radius:50%;
  border:1.5px solid rgba(0,221,176,.35);pointer-events:none;z-index:99998;
  transform:translate(-50%,-50%);transition:width .3s var(--ease),height .3s var(--ease),border-color .3s,left .08s linear,top .08s linear}

/* ════════════════════════════════════════
   SCROLLBAR
════════════════════════════════════════ */
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:var(--line2);border-radius:3px}

/* ════════════════════════════════════════
   PARTICLE CANVAS
════════════════════════════════════════ */
#pcvs{position:fixed;inset:0;pointer-events:none;z-index:0;opacity:.6}

/* ════════════════════════════════════════
   ── LOGIN PAGE ──
════════════════════════════════════════ */
.login-page{
  position:relative;z-index:2;
  min-height:100vh;display:flex;align-items:center;justify-content:center;
  padding:2rem;
}
.login-bg{
  position:fixed;inset:0;z-index:0;
  background:
    radial-gradient(ellipse 80% 60% at 10% 0%, rgba(139,108,247,.15) 0%,transparent 60%),
    radial-gradient(ellipse 60% 60% at 90% 100%, rgba(0,221,176,.1) 0%,transparent 60%);
}
.login-grid{
  position:fixed;inset:0;z-index:0;
  background-image:
    linear-gradient(rgba(139,108,247,.06) 1px,transparent 1px),
    linear-gradient(90deg,rgba(139,108,247,.06) 1px,transparent 1px);
  background-size:50px 50px;
}
.login-wrap{
  position:relative;z-index:2;
  width:100%;max-width:960px;
  display:grid;grid-template-columns:1fr 1fr;gap:0;
  border-radius:24px;overflow:hidden;
  border:1px solid var(--line2);
  box-shadow:0 40px 120px rgba(0,0,0,.6);
}
.login-left{
  background:linear-gradient(145deg,#0f0f22 0%,#0a0a18 100%);
  padding:3.5rem;display:flex;flex-direction:column;justify-content:space-between;
  position:relative;overflow:hidden;
}
.login-left::before{
  content:'';position:absolute;top:-80px;right:-80px;
  width:300px;height:300px;border-radius:50%;
  background:radial-gradient(circle,rgba(139,108,247,.18) 0%,transparent 70%);
}
.login-left::after{
  content:'';position:absolute;bottom:-60px;left:-60px;
  width:200px;height:200px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,221,176,.12) 0%,transparent 70%);
}
.brand{position:relative;z-index:1}
.brand-logo{
  font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;
  letter-spacing:.06em;
  background:linear-gradient(135deg,#fff 0%,var(--violet) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.brand-sub{font-size:.72rem;color:var(--muted);letter-spacing:.18em;text-transform:uppercase;margin-top:.3rem;font-family:'JetBrains Mono',monospace}
.brand-tagline{
  font-size:1.8rem;font-family:'Syne',sans-serif;font-weight:700;
  color:var(--ink);line-height:1.2;margin-top:3rem;position:relative;z-index:1;
}
.brand-tagline span{color:var(--teal)}
.login-stats{
  display:flex;gap:2rem;position:relative;z-index:1;
}
.login-stat-val{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;color:var(--ink)}
.login-stat-lbl{font-size:.68rem;color:var(--muted);letter-spacing:.1em;text-transform:uppercase}

.login-right{
  background:var(--bg2);padding:3.5rem;
  display:flex;flex-direction:column;justify-content:center;
}
.login-title{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:700;margin-bottom:.4rem}
.login-desc{color:var(--ink2);font-size:.85rem;margin-bottom:2.5rem}

.demo-accounts{
  background:rgba(139,108,247,.07);border:1px solid rgba(139,108,247,.18);
  border-radius:10px;padding:1rem;margin-bottom:2rem;
  font-family:'JetBrains Mono',monospace;font-size:.7rem;line-height:1.9;
  color:var(--ink2);
}
.demo-accounts strong{color:var(--violet)}

.form-group{margin-bottom:1.25rem}
.form-label{display:block;font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;
  color:var(--muted);margin-bottom:.5rem;font-family:'JetBrains Mono',monospace}
.form-input{
  width:100%;background:var(--bg3);border:1px solid var(--line2);
  border-radius:10px;padding:.85rem 1.1rem;color:var(--ink);font-size:.95rem;
  transition:border-color .2s,box-shadow .2s;
}
.form-input:focus{border-color:var(--violet);box-shadow:0 0 0 3px rgba(139,108,247,.15)}
.form-input::placeholder{color:var(--muted)}

.btn-login{
  width:100%;background:linear-gradient(135deg,var(--violet),#6a4fc7);
  color:#fff;border-radius:10px;padding:1rem;
  font-family:'Syne',sans-serif;font-weight:700;font-size:.95rem;
  letter-spacing:.08em;cursor:none;
  transition:transform .2s var(--ease),box-shadow .2s;
  box-shadow:0 4px 20px rgba(139,108,247,.35);
  position:relative;overflow:hidden;
}
.btn-login::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);
  transform:translateX(-100%);transition:transform .5s var(--ease);
}
.btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(139,108,247,.5)}
.btn-login:hover::after{transform:translateX(100%)}
.login-error{
  background:var(--rose-d);border:1px solid rgba(245,97,122,.25);
  border-radius:8px;padding:.7rem 1rem;font-size:.8rem;color:var(--rose);margin-bottom:1rem;
  font-family:'JetBrains Mono',monospace;
}

/* ════════════════════════════════════════
   ── DASHBOARD LAYOUT ──
════════════════════════════════════════ */
.app{display:flex;min-height:100vh;position:relative;z-index:2}

/* SIDEBAR */
.sidebar{
  width:var(--sidebar);flex-shrink:0;
  background:var(--bg2);border-right:1px solid var(--line);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;height:100vh;
  z-index:100;overflow-y:auto;
}
.sb-brand{
  padding:1.75rem 1.5rem 1.25rem;
  border-bottom:1px solid var(--line);
}
.sb-logo{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;letter-spacing:.06em;
  background:linear-gradient(135deg,#fff,var(--violet));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.sb-tagline{font-size:.6rem;color:var(--muted);letter-spacing:.15em;text-transform:uppercase;
  font-family:'JetBrains Mono',monospace;margin-top:.2rem;}

.sb-user{
  padding:1.25rem 1.5rem;border-bottom:1px solid var(--line);
  display:flex;align-items:center;gap:.85rem;
}
.sb-avatar{
  width:38px;height:38px;border-radius:10px;
  background:linear-gradient(135deg,var(--violet),var(--teal));
  display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-weight:700;font-size:.8rem;color:#fff;
  flex-shrink:0;
}
.sb-name{font-size:.82rem;font-weight:600;line-height:1.3}
.sb-role{font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;font-family:'JetBrains Mono',monospace}

.sb-nav{padding:1rem 0;flex:1}
.sb-group-label{
  font-size:.58rem;letter-spacing:.2em;text-transform:uppercase;
  color:var(--muted);padding:.5rem 1.5rem;font-family:'JetBrains Mono',monospace;
}
.sb-item{
  display:flex;align-items:center;gap:.75rem;
  padding:.65rem 1.5rem;margin:.1rem .75rem;
  border-radius:9px;color:var(--ink2);font-size:.85rem;font-weight:500;
  transition:background .2s,color .2s;cursor:none;
  text-decoration:none;
}
.sb-item:hover{background:rgba(255,255,255,.04);color:var(--ink)}
.sb-item.active{background:rgba(139,108,247,.12);color:var(--violet)}
.sb-item.active .sb-icon{color:var(--violet)}
.sb-icon{font-size:1rem;width:18px;text-align:center;flex-shrink:0}
.sb-badge{
  margin-left:auto;background:var(--rose);color:#fff;
  font-size:.58rem;font-weight:700;border-radius:99px;padding:.15rem .45rem;
  font-family:'JetBrains Mono',monospace;
}
.sb-footer{
  padding:1.25rem 1.5rem;border-top:1px solid var(--line);
}
.sb-logout{
  display:flex;align-items:center;gap:.6rem;
  color:var(--muted);font-size:.8rem;cursor:none;
  transition:color .2s;text-decoration:none;
}
.sb-logout:hover{color:var(--rose)}

/* MAIN */
.main{margin-left:var(--sidebar);flex:1;min-height:100vh}
.topnav{
  background:rgba(13,13,24,.85);backdrop-filter:blur(20px);
  border-bottom:1px solid var(--line);
  padding:.9rem 2rem;display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:50;
}
.topnav-title{font-family:'Syne',sans-serif;font-weight:700;font-size:1.05rem}
.topnav-right{display:flex;align-items:center;gap:1rem}
.topnav-chip{
  font-family:'JetBrains Mono',monospace;font-size:.65rem;
  background:var(--teal-d);border:1px solid rgba(0,221,176,.2);
  color:var(--teal);padding:.25rem .7rem;border-radius:99px;
  letter-spacing:.06em;
}
.topnav-notif{
  width:34px;height:34px;border-radius:9px;background:var(--bg3);
  border:1px solid var(--line2);display:flex;align-items:center;justify-content:center;
  cursor:none;transition:background .2s;font-size:.95rem;position:relative;
}
.topnav-notif:hover{background:var(--line)}
.notif-dot{
  position:absolute;top:5px;right:5px;width:6px;height:6px;
  background:var(--rose);border-radius:50%;border:1.5px solid var(--bg2);
}

.page-content{padding:2rem;max-width:1200px}

/* ════════════════════════════════════════
   CARDS / UTILS
════════════════════════════════════════ */
.card{
  background:var(--card);border:1px solid var(--line);
  border-radius:var(--r);padding:1.5rem;position:relative;overflow:hidden;
}
.card-sm{padding:1.25rem}
.card-title{font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;margin-bottom:1.25rem;
  display:flex;align-items:center;gap:.6rem;}
.card-title .ct-icon{font-size:1.1rem}
.section-hd{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:1.5rem;
}
.section-hd h2{font-family:'Syne',sans-serif;font-weight:700;font-size:1.3rem}
.badge{
  display:inline-flex;align-items:center;gap:.3rem;
  font-size:.65rem;font-family:'JetBrains Mono',monospace;
  letter-spacing:.1em;text-transform:uppercase;
  padding:.25rem .65rem;border-radius:6px;font-weight:500;
}
.badge-teal  {background:var(--teal-d);color:var(--teal);border:1px solid rgba(0,221,176,.2)}
.badge-rose  {background:var(--rose-d);color:var(--rose);border:1px solid rgba(245,97,122,.2)}
.badge-amber {background:var(--amber-d);color:var(--amber);border:1px solid rgba(245,166,35,.2)}
.badge-violet{background:var(--violet-d);color:var(--violet);border:1px solid rgba(139,108,247,.2)}
.badge-sky   {background:var(--sky-d);color:var(--sky);border:1px solid rgba(56,200,245,.2)}
.badge-muted {background:rgba(255,255,255,.04);color:var(--muted);border:1px solid var(--line2)}
.tag{font-size:.62rem;color:var(--muted);font-family:'JetBrains Mono',monospace;letter-spacing:.12em;text-transform:uppercase}
.divider{height:1px;background:var(--line);margin:1.25rem 0}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem}
.gap-sm{gap:.75rem}
.mt-1{margin-top:.75rem}.mt-2{margin-top:1.25rem}.mt-3{margin-top:2rem}
.flex{display:flex}.flex-col{flex-direction:column}
.items-center{align-items:center}.items-end{align-items:flex-end}
.justify-between{justify-content:space-between}.justify-end{justify-content:flex-end}
.gap{gap:.75rem}.gap-lg{gap:1.5rem}
.text-muted{color:var(--ink2)}.text-sm{font-size:.82rem}.text-xs{font-size:.72rem}
.mono{font-family:'JetBrains Mono',monospace}
.fw-7{font-weight:700}.fw-8{font-weight:800}
.text-teal{color:var(--teal)}.text-rose{color:var(--rose)}.text-amber{color:var(--amber)}
.text-violet{color:var(--violet)}.text-sky{color:var(--sky)}

/* Stat Card */
.stat-card{
  background:var(--card);border:1px solid var(--line);border-radius:var(--r);
  padding:1.4rem;position:relative;overflow:hidden;
  transition:border-color .25s,transform .25s var(--ease);
}
.stat-card:hover{transform:translateY(-3px)}
.stat-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:2px;
  border-radius:var(--r) var(--r) 0 0;
}
.stat-card.c-teal::before{background:var(--teal)}.stat-card.c-teal:hover{border-color:rgba(0,221,176,.3)}
.stat-card.c-violet::before{background:var(--violet)}.stat-card.c-violet:hover{border-color:rgba(139,108,247,.3)}
.stat-card.c-amber::before{background:var(--amber)}.stat-card.c-amber:hover{border-color:rgba(245,166,35,.3)}
.stat-card.c-rose::before{background:var(--rose)}.stat-card.c-rose:hover{border-color:rgba(245,97,122,.3)}
.stat-card.c-sky::before{background:var(--sky)}.stat-card.c-sky:hover{border-color:rgba(56,200,245,.3)}
.stat-val{font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;line-height:1}
.stat-lbl{font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.12em;font-family:'JetBrains Mono',monospace;margin-top:.4rem}
.stat-trend{font-size:.72rem;display:flex;align-items:center;gap:.3rem;margin-top:.75rem}
.stat-icon{
  width:40px;height:40px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;font-size:1.2rem;
  position:absolute;top:1.4rem;right:1.4rem;
}

/* Progress bar */
.pbar{height:5px;background:var(--line);border-radius:3px;overflow:hidden;margin-top:.5rem}
.pbar-fill{height:100%;border-radius:3px;transition:width .8s var(--ease)}
.pbar-teal{background:var(--teal)}.pbar-violet{background:var(--violet)}
.pbar-amber{background:var(--amber)}.pbar-rose{background:var(--rose)}

/* Table */
.tbl{width:100%;border-collapse:collapse}
.tbl th{font-size:.62rem;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);
  font-family:'JetBrains Mono',monospace;padding:.7rem 1rem;text-align:left;
  border-bottom:1px solid var(--line);}
.tbl td{padding:.85rem 1rem;border-bottom:1px solid var(--line);font-size:.85rem;vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:rgba(255,255,255,.015)}

/* ════════════════════════════════════════
   SECTIONS
════════════════════════════════════════ */

/* ── DASHBOARD ── */
.welcome-banner{
  background:linear-gradient(135deg,rgba(139,108,247,.12),rgba(0,221,176,.07));
  border:1px solid rgba(139,108,247,.2);border-radius:var(--r);
  padding:2rem;margin-bottom:2rem;position:relative;overflow:hidden;
}
.welcome-banner::after{
  content:'UNINOVA';font-family:'Syne',sans-serif;font-weight:800;
  font-size:8rem;color:rgba(255,255,255,.02);position:absolute;
  right:-1rem;bottom:-1.5rem;line-height:1;pointer-events:none;letter-spacing:.08em;
}
.welcome-name{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800}
.welcome-name span{color:var(--teal)}

/* ── NOTAS ── */
.nota-row{display:flex;align-items:center;gap:1.5rem;padding:1.1rem 0;border-bottom:1px solid var(--line)}
.nota-row:last-child{border-bottom:none}
.nota-cod{font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--muted);width:58px;flex-shrink:0}
.nota-nome{flex:1;font-size:.88rem;font-weight:500}
.nota-val{
  font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;
  width:52px;text-align:center;flex-shrink:0;
}
.nota-bar-wrap{flex:1;max-width:120px}

/* Media Calc form */
.calc-form{
  background:var(--bg3);border:1px solid var(--line2);
  border-radius:12px;padding:1.5rem;
}
.calc-inputs{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem}
.calc-label{font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);
  margin-bottom:.4rem;font-family:'JetBrains Mono',monospace;display:block}
.calc-input{
  width:100%;background:var(--card);border:1px solid var(--line2);
  border-radius:9px;padding:.8rem;text-align:center;color:var(--ink);
  font-family:'JetBrains Mono',monospace;font-size:1.3rem;font-weight:500;
  transition:border-color .2s,box-shadow .2s,transform .2s var(--ease);
  -moz-appearance:textfield;
}
.calc-input::-webkit-outer-spin-button,.calc-input::-webkit-inner-spin-button{-webkit-appearance:none}
.calc-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-d);transform:translateY(-2px)}
.calc-formula{
  font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--muted);
  background:rgba(255,255,255,.03);border-radius:7px;padding:.6rem .9rem;
  margin-bottom:1.25rem;text-align:center;
}
.calc-formula strong{color:var(--teal)}
.btn-calc{
  width:100%;background:linear-gradient(135deg,var(--teal),#00b891);
  color:#030b09;border-radius:9px;padding:.9rem;
  font-family:'Syne',sans-serif;font-weight:700;font-size:.9rem;
  letter-spacing:.08em;cursor:none;
  transition:transform .2s var(--ease),box-shadow .2s;
  box-shadow:0 4px 20px rgba(0,221,176,.25);
}
.btn-calc:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,221,176,.4)}

.result-card{
  border-radius:12px;overflow:hidden;border:1px solid var(--line);margin-top:1.25rem;
  animation:rise .5s var(--ease) both;
}
.result-top{
  padding:1.5rem;display:flex;align-items:center;justify-content:space-between;
}
.result-top.aprovado   {background:rgba(0,221,176,.07);border-bottom:1px solid rgba(0,221,176,.15)}
.result-top.recuperacao{background:rgba(245,166,35,.07);border-bottom:1px solid rgba(245,166,35,.15)}
.result-top.reprovado  {background:rgba(245,97,122,.07);border-bottom:1px solid rgba(245,97,122,.15)}
.result-mf{font-family:'Syne',sans-serif;font-size:4rem;font-weight:800;line-height:1}
.aprovado .result-mf   {color:var(--teal);text-shadow:0 0 40px var(--teal-g)}
.recuperacao .result-mf{color:var(--amber);text-shadow:0 0 40px rgba(245,166,35,.3)}
.reprovado .result-mf  {color:var(--rose);text-shadow:0 0 40px rgba(245,97,122,.3)}
.result-detail{text-align:right}
.result-desc{font-size:.75rem;color:var(--ink2);margin-top:.4rem;font-family:'JetBrains Mono',monospace}
.result-breakdown{
  padding:1rem 1.5rem;background:rgba(0,0,0,.2);
  display:flex;gap:1.5rem;font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--ink2);
}
.rb-item span{color:var(--ink);font-weight:600}
.rb-formula{margin-left:auto;color:var(--muted);font-size:.68rem;align-self:center}

/* ── HORARIOS ── */
.schedule-grid{
  display:grid;grid-template-columns:60px repeat(6,1fr);gap:2px;
  font-size:.75rem;
}
.sched-hdr{
  background:var(--bg3);padding:.55rem;text-align:center;border-radius:6px;
  font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);letter-spacing:.08em;
}
.sched-time{
  display:flex;align-items:center;justify-content:center;
  font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);
}
.sched-cell{
  border-radius:6px;padding:.5rem;min-height:60px;
  display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;
  font-size:.65rem;line-height:1.4;
}
.sched-empty{background:var(--bg3);opacity:.4}
.sched-teal  {background:rgba(0,221,176,.1);border:1px solid rgba(0,221,176,.2);color:var(--teal)}
.sched-violet{background:rgba(139,108,247,.1);border:1px solid rgba(139,108,247,.2);color:var(--violet)}
.sched-amber {background:rgba(245,166,35,.1);border:1px solid rgba(245,166,35,.2);color:var(--amber)}
.sched-sky   {background:rgba(56,200,245,.1);border:1px solid rgba(56,200,245,.2);color:var(--sky)}
.sched-rose  {background:rgba(245,97,122,.1);border:1px solid rgba(245,97,122,.2);color:var(--rose)}
.sched-name{font-weight:600;font-size:.68rem}
.sched-sala{font-size:.58rem;opacity:.7;margin-top:.2rem}

/* ── FINANCEIRO ── */
.fin-row{
  display:flex;align-items:center;gap:1rem;padding:1rem;
  border-radius:10px;margin-bottom:.5rem;background:var(--bg3);
  border:1px solid var(--line);transition:border-color .2s;
}
.fin-row:hover{border-color:var(--line2)}
.fin-mes{font-weight:600;font-size:.88rem;flex:1}
.fin-valor{font-family:'JetBrains Mono',monospace;font-size:.95rem;font-weight:600}
.fin-venc{font-size:.72rem;color:var(--muted);font-family:'JetBrains Mono',monospace}
.fin-total-box{
  background:linear-gradient(135deg,rgba(139,108,247,.08),rgba(0,221,176,.05));
  border:1px solid rgba(139,108,247,.2);border-radius:10px;padding:1.25rem;
}

/* ── BIBLIOTECA ── */
.book-row{
  display:flex;align-items:center;gap:1rem;padding:.9rem;
  background:var(--bg3);border-radius:10px;border:1px solid var(--line);
  margin-bottom:.5rem;
}
.book-icon{
  width:44px;height:56px;border-radius:6px;
  background:linear-gradient(145deg,var(--violet-d),rgba(0,0,0,.3));
  border:1px solid rgba(139,108,247,.2);
  display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;
}
.book-info{flex:1}
.book-title{font-weight:600;font-size:.88rem}
.book-author{font-size:.75rem;color:var(--muted);margin-top:.1rem}
.book-devol{font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--ink2)}

/* ── NOTICIAS ── */
.news-item{
  padding:1rem;background:var(--bg3);border-radius:10px;border:1px solid var(--line);
  margin-bottom:.5rem;transition:border-color .2s,background .2s;cursor:none;
}
.news-item:hover{border-color:var(--line2);background:rgba(255,255,255,.02)}
.news-titulo{font-weight:600;font-size:.9rem;margin-bottom:.35rem}
.news-resumo{font-size:.8rem;color:var(--ink2);line-height:1.6}
.news-meta{display:flex;align-items:center;gap:.75rem;margin-top:.6rem}

/* ── PERFIL ── */
.profile-hero{
  background:linear-gradient(135deg,rgba(139,108,247,.1),rgba(0,221,176,.06));
  border:1px solid rgba(139,108,247,.15);border-radius:var(--r);
  padding:2.5rem;display:flex;gap:2rem;align-items:center;margin-bottom:1.5rem;
}
.profile-avatar{
  width:80px;height:80px;border-radius:18px;flex-shrink:0;
  background:linear-gradient(135deg,var(--violet),var(--teal));
  display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;color:#fff;
  box-shadow:0 8px 30px rgba(139,108,247,.4);
}
.profile-info-name{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800}
.profile-info-ra{font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--muted);margin-top:.2rem}
.profile-chips{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.75rem}

/* ── SECRETARIA ── */
.req-item{
  display:flex;align-items:center;gap:1rem;padding:.9rem;
  background:var(--bg3);border-radius:10px;border:1px solid var(--line);margin-bottom:.5rem;
}
.req-ico{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}

/* ── SUPORTE ── */
.faq-item{
  border-bottom:1px solid var(--line);padding:.9rem 0;cursor:none;
}
.faq-item:last-child{border-bottom:none}
.faq-q{font-weight:600;font-size:.88rem;display:flex;align-items:center;justify-content:space-between}
.faq-a{font-size:.82rem;color:var(--ink2);line-height:1.7;margin-top:.5rem;display:none}
.faq-item.open .faq-a{display:block}
.faq-item.open .faq-chevron{transform:rotate(180deg)}
.faq-chevron{transition:transform .25s var(--ease);font-size:.8rem;color:var(--muted)}

/* ── ANIMATIONS ── */
@keyframes rise{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

.anim-1{animation:rise .6s var(--ease) .05s both}
.anim-2{animation:rise .6s var(--ease) .1s both}
.anim-3{animation:rise .6s var(--ease) .15s both}
.anim-4{animation:rise .6s var(--ease) .2s both}
.anim-5{animation:rise .6s var(--ease) .25s both}
.anim-6{animation:rise .6s var(--ease) .3s both}

/* ── RESPONSIVE ── */
@media(max-width:900px){
  .login-wrap{grid-template-columns:1fr}
  .login-left{display:none}
  .grid-4{grid-template-columns:1fr 1fr}
  .grid-3{grid-template-columns:1fr 1fr}
}
@media(max-width:640px){
  .sidebar{transform:translateX(-100%)}
  .main{margin-left:0}
  .grid-2,.grid-3,.grid-4{grid-template-columns:1fr}
}
</style>
</head>
<body>

<!-- Cursor -->
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- Canvas -->
<canvas id="pcvs"></canvas>

<?php if(!$logado): ?>
<!-- ══════════════════════════════════════
     LOGIN PAGE
══════════════════════════════════════ -->
<div class="login-bg"></div>
<div class="login-grid"></div>
<div class="login-page">
  <div class="login-wrap">
    <div class="login-left">
      <div class="brand">
        <div class="brand-logo">UNINOVA</div>
        <div class="brand-sub">Portal Acadêmico Integrado</div>
        <div class="brand-tagline">Construindo o <span>futuro</span> com excelência acadêmica.</div>
      </div>
      <div class="login-stats">
        <div><div class="login-stat-val">12.400</div><div class="login-stat-lbl">Alunos</div></div>
        <div><div class="login-stat-val">840</div><div class="login-stat-lbl">Docentes</div></div>
        <div><div class="login-stat-val">68</div><div class="login-stat-lbl">Cursos</div></div>
      </div>
    </div>
    <div class="login-right">
      <div class="login-title">Bem-vindo de volta</div>
      <div class="login-desc">Acesse o portal com suas credenciais institucionais.</div>

      <div class="demo-accounts">
        <strong>Contas demo:</strong><br>
        aluno / 1234 &nbsp;·&nbsp; prof / prof123 &nbsp;·&nbsp; coord / coord456
      </div>

      <?php if($erro): ?>
      <div class="login-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label class="form-label">Usuário / RA</label>
          <input class="form-input" type="text" name="usuario" placeholder="ex: aluno" autocomplete="username">
        </div>
        <div class="form-group">
          <label class="form-label">Senha</label>
          <input class="form-input" type="password" name="senha" placeholder="••••••••" autocomplete="current-password">
        </div>
        <button class="btn-login" type="submit">Entrar no Portal</button>
      </form>
    </div>
  </div>
</div>

<?php else: ?>
<!-- ══════════════════════════════════════
     PORTAL AUTENTICADO
══════════════════════════════════════ -->
<div class="app">

  <!-- ═══ SIDEBAR ═══ -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div class="sb-logo">UNINOVA</div>
      <div class="sb-tagline">Portal Acadêmico</div>
    </div>
    <div class="sb-user">
      <div class="sb-avatar"><?= htmlspecialchars($user['avatar']) ?></div>
      <div>
        <div class="sb-name"><?= htmlspecialchars(explode(' ',$user['nome'])[0]) ?></div>
        <div class="sb-role"><?= htmlspecialchars($user['role']) ?></div>
      </div>
    </div>
    <nav class="sb-nav">
      <div class="sb-group-label">Principal</div>
      <?php
      $nav = [
        ['s'=>'dashboard', 'icon'=>'🏠','label'=>'Dashboard'],
        ['s'=>'notas',     'icon'=>'📊','label'=>'Notas & Médias'],
        ['s'=>'horarios',  'icon'=>'🗓','label'=>'Horários'],
        ['s'=>'frequencia','icon'=>'📋','label'=>'Frequência'],
        ['s'=>'financeiro','icon'=>'💳','label'=>'Financeiro','badge'=>'1'],
        ['s'=>'biblioteca','icon'=>'📚','label'=>'Biblioteca'],
      ];
      $nav2 = [
        ['s'=>'noticias',  'icon'=>'📰','label'=>'Notícias','badge'=>'3'],
        ['s'=>'secretaria','icon'=>'🏛','label'=>'Secretaria'],
        ['s'=>'suporte',   'icon'=>'💬','label'=>'Suporte'],
        ['s'=>'perfil',    'icon'=>'👤','label'=>'Meu Perfil'],
        ['s'=>'calendario','icon'=>'📅','label'=>'Calendário'],
      ];
      foreach($nav as $n):
        $a = ($secao===$n['s'])?'active':'';
      ?>
      <a href="?s=<?=$n['s']?>" class="sb-item <?=$a?>">
        <span class="sb-icon"><?=$n['icon']?></span>
        <?=$n['label']?>
        <?php if(!empty($n['badge'])): ?><span class="sb-badge"><?=$n['badge']?></span><?php endif; ?>
      </a>
      <?php endforeach; ?>
      <div class="sb-group-label" style="margin-top:.5rem">Serviços</div>
      <?php foreach($nav2 as $n):
        $a = ($secao===$n['s'])?'active':''; ?>
      <a href="?s=<?=$n['s']?>" class="sb-item <?=$a?>">
        <span class="sb-icon"><?=$n['icon']?></span>
        <?=$n['label']?>
        <?php if(!empty($n['badge'])): ?><span class="sb-badge"><?=$n['badge']?></span><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="sb-footer">
      <a href="?logout=1" class="sb-logout">🚪 Sair do Portal</a>
    </div>
  </aside>

  <!-- ═══ MAIN ═══ -->
  <main class="main">
    <div class="topnav">
      <div class="topnav-title">
        <?php
        $titles=['dashboard'=>'Dashboard','notas'=>'Notas & Médias','horarios'=>'Horários',
          'frequencia'=>'Frequência','financeiro'=>'Financeiro','biblioteca'=>'Biblioteca',
          'noticias'=>'Notícias & Comunicados','secretaria'=>'Secretaria Virtual',
          'suporte'=>'Suporte & FAQ','perfil'=>'Meu Perfil','calendario'=>'Calendário Acadêmico'];
        echo $titles[$secao] ?? 'Portal';
        ?>
      </div>
      <div class="topnav-right">
        <span class="topnav-chip">📅 <?= date('d/m/Y') ?></span>
        <div class="topnav-notif">🔔<span class="notif-dot"></span></div>
        <span class="badge badge-violet"><?= htmlspecialchars($user['role']) ?></span>
      </div>
    </div>

    <div class="page-content">

    <!-- ══════════ DASHBOARD ══════════ -->
    <?php if($secao==='dashboard'): ?>

    <div class="welcome-banner anim-1">
      <div class="tag">Bem-vindo de volta</div>
      <div class="welcome-name mt-1">Olá, <span><?= htmlspecialchars(explode(' ',$user['nome'])[0]) ?></span> 👋</div>
      <div class="text-muted text-sm mt-1"><?= htmlspecialchars($user['curso']) ?> · <?= htmlspecialchars($user['periodo']) ?> · <?= date('l, d \d\e F \d\e Y') ?></div>
    </div>

    <?php
    // Calcula CRAs
    $totalMF=[]; foreach($DISCIPLINAS as $d){$mf=mediaFinal($d);if($mf!==null)$totalMF[]=$mf;}
    $cr = count($totalMF)?round(array_sum($totalMF)/count($totalMF),1):$user['cr'];
    $totalFaltas = array_sum(array_column($DISCIPLINAS,'faltas'));
    ?>

    <div class="grid-4 anim-2">
      <div class="stat-card c-violet">
        <div class="stat-icon" style="background:var(--violet-d)">🎓</div>
        <div class="stat-val text-violet"><?= number_format($cr,1,',','') ?></div>
        <div class="stat-lbl">Coef. de Rendimento</div>
        <div class="pbar mt-1"><div class="pbar-fill pbar-violet" style="width:<?=($cr/10*100)?>%"></div></div>
      </div>
      <div class="stat-card c-teal">
        <div class="stat-icon" style="background:var(--teal-d)">📚</div>
        <div class="stat-val text-teal"><?= count($DISCIPLINAS) ?></div>
        <div class="stat-lbl">Disciplinas Ativas</div>
        <div class="stat-trend text-sm text-muted">Semestre 2025/1</div>
      </div>
      <div class="stat-card c-amber">
        <div class="stat-icon" style="background:var(--amber-d)">⚠️</div>
        <div class="stat-val text-amber"><?= $totalFaltas ?></div>
        <div class="stat-lbl">Total de Faltas</div>
        <div class="pbar mt-1"><div class="pbar-fill pbar-amber" style="width:<?=min(100,$totalFaltas/40*100)?>%"></div></div>
      </div>
      <div class="stat-card c-rose">
        <div class="stat-icon" style="background:var(--rose-d)">💳</div>
        <div class="stat-val text-rose">1</div>
        <div class="stat-lbl">Pendência Financeira</div>
        <div class="stat-trend text-sm" style="color:var(--rose)">Vence 10/05</div>
      </div>
    </div>

    <div class="grid-2 mt-2 anim-3">
      <!-- Desempenho rápido -->
      <div class="card">
        <div class="card-title"><span class="ct-icon">📊</span> Desempenho por Disciplina</div>
        <?php foreach($DISCIPLINAS as $d):
          $mf=mediaFinal($d);[$sit,$cor]=situacao($mf);
          $pct=$mf!==null?($mf/10*100):($d['m1']!==null?($d['m1']/10*100):0);
        ?>
        <div style="margin-bottom:.9rem">
          <div class="flex items-center justify-between">
            <span class="text-sm fw-7"><?= htmlspecialchars($d['nome']) ?></span>
            <span class="badge badge-<?= $cor ?>"><?= $sit ?></span>
          </div>
          <div class="flex items-center gap mt-1">
            <div class="pbar" style="flex:1"><div class="pbar-fill pbar-<?=$cor?>" style="width:<?=$pct?>%"></div></div>
            <span class="mono text-xs text-muted"><?= $mf!==null?number_format($mf,1,',',''):($d['m1']!==null?'M1:'.number_format($d['m1'],1,',',''):'—') ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Próximos eventos -->
      <div class="card">
        <div class="card-title"><span class="ct-icon">📅</span> Próximos Eventos</div>
        <?php foreach(array_slice($EVENTOS,0,5) as $e): ?>
        <div class="flex items-center gap" style="padding:.7rem 0;border-bottom:1px solid var(--line)">
          <div style="width:44px;height:44px;border-radius:10px;background:rgba(<?=$e['cor']==='rose'?'245,97,122':($e['cor']==='amber'?'245,166,35':($e['cor']==='sky'?'56,200,245':'0,221,176'))?>,.1);border:1px solid rgba(<?=$e['cor']==='rose'?'245,97,122':($e['cor']==='amber'?'245,166,35':($e['cor']==='sky'?'56,200,245':'0,221,176'))?>,.2);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0">
            <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:.9rem;line-height:1;color:var(--<?=$e['cor']?>)"><?=$e['dia']?></span>
            <span style="font-size:.5rem;color:var(--muted);font-family:'JetBrains Mono',monospace"><?=$e['mes']?></span>
          </div>
          <div style="flex:1">
            <div class="text-sm fw-7"><?= htmlspecialchars($e['titulo']) ?></div>
            <div class="text-xs text-muted mono"><?= $e['hora'] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Mini noticias -->
    <div class="card mt-2 anim-4">
      <div class="card-title"><span class="ct-icon">📰</span> Últimas Notícias</div>
      <div class="grid-2">
        <?php foreach(array_slice($NOTICIAS,0,4) as $n): ?>
        <div class="news-item">
          <div class="flex items-center gap" style="margin-bottom:.5rem">
            <?php if($n['tipo']==='urgente'): ?><span class="badge badge-rose">🔴 Urgente</span>
            <?php elseif($n['tipo']==='info'): ?><span class="badge badge-sky">ℹ Info</span>
            <?php else: ?><span class="badge badge-muted">Geral</span><?php endif; ?>
            <span class="tag"><?= $n['data'] ?></span>
          </div>
          <div class="news-titulo"><?= htmlspecialchars($n['titulo']) ?></div>
          <div class="news-resumo"><?= htmlspecialchars($n['resumo']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ══════════ NOTAS ══════════ -->
    <?php elseif($secao==='notas'): ?>

    <div class="grid-2 anim-1">
      <!-- Tabela de notas -->
      <div class="card" style="grid-column:1/-1">
        <div class="card-title"><span class="ct-icon">📊</span> Boletim Semestral — 2025/1</div>
        <div style="overflow-x:auto">
        <table class="tbl">
          <thead>
            <tr>
              <th>Código</th><th>Disciplina</th><th>Professor</th>
              <th>M1</th><th>M2 (×2)</th><th>Média Final</th><th>CH</th><th>Faltas</th><th>Situação</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($DISCIPLINAS as $d):
            $mf=mediaFinal($d);[$sit,$cor]=situacao($mf);
          ?>
            <tr>
              <td><span class="mono text-xs text-muted"><?= $d['cod'] ?></span></td>
              <td><span class="fw-7"><?= htmlspecialchars($d['nome']) ?></span></td>
              <td class="text-muted text-sm"><?= htmlspecialchars($d['prof']) ?></td>
              <td>
                <?php if($d['m1']!==null): ?>
                <span class="mono" style="color:<?= $d['m1']>=5?'var(--teal)':'var(--rose)' ?>"><?= number_format($d['m1'],1,',','') ?></span>
                <?php else: ?><span class="text-muted">—</span><?php endif; ?>
              </td>
              <td>
                <?php if($d['m2']!==null): ?>
                <span class="mono" style="color:<?= $d['m2']>=5?'var(--teal)':'var(--rose)' ?>"><?= number_format($d['m2'],1,',','') ?></span>
                <?php else: ?><span class="badge badge-muted">Pendente</span><?php endif; ?>
              </td>
              <td>
                <?php if($mf!==null): ?>
                <span class="fw-7" style="font-family:'Syne',sans-serif;font-size:1.1rem;color:var(--<?=$cor?>)"><?= number_format($mf,2,',','') ?></span>
                <?php else: ?><span class="text-muted">—</span><?php endif; ?>
              </td>
              <td class="text-muted text-sm"><?= $d['ch'] ?>h</td>
              <td>
                <span style="color:<?= pctFaltas($d['faltas'],$d['max_faltas'])>50?'var(--rose)':'var(--ink2)' ?>" class="mono text-sm">
                  <?= $d['faltas'] ?>/<?= $d['max_faltas'] ?>
                </span>
              </td>
              <td><span class="badge badge-<?=$cor?>"><?=$sit?></span></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>

    <!-- Calculadora de Média -->
    <div class="grid-2 mt-2 anim-2">
      <div class="card">
        <div class="card-title"><span class="ct-icon">🧮</span> Calculadora de Média Final</div>
        <div class="calc-form">
          <div class="calc-formula">
            MF = (<strong>M1</strong> + <strong>M2 × 2</strong>) ÷ 3
            <br><span style="font-size:.62rem;opacity:.6">M2 tem peso duplo</span>
          </div>
          <form method="post" action="?s=notas">
            <input type="hidden" name="action" value="calcmedia">
            <div class="calc-inputs">
              <div>
                <label class="calc-label">M1 — Prova 1</label>
                <input class="calc-input" type="number" name="m1" id="m1"
                  min="0" max="10" step="0.01" placeholder="0.00"
                  value="<?= $calc ? htmlspecialchars($calc['m1']) : '' ?>">
              </div>
              <div>
                <label class="calc-label">M2 — Prova 2 (peso 2)</label>
                <input class="calc-input" type="number" name="m2" id="m2"
                  min="0" max="10" step="0.01" placeholder="0.00"
                  value="<?= $calc ? htmlspecialchars($calc['m2']) : '' ?>">
              </div>
            </div>
            <!-- Live preview -->
            <div id="live-preview" style="text-align:center;margin-bottom:1rem;min-height:40px;font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--muted)">
              <?php if($calc): ?>
              <span style="color:var(--<?= $calc['sit']==='Aprovado'?'teal':($calc['sit']==='Recuperação'?'amber':'rose') ?>)"><?= number_format($calc['mf'],2,',','') ?></span>
              <?php else: ?>—<?php endif; ?>
            </div>
            <button class="btn-calc" type="submit">Calcular Média Final</button>
          </form>
        </div>

        <?php if($calc): ?>
        <div class="result-card <?= strtolower(str_replace('ã','a',$calc['sit'])) === 'aprovado' ? 'aprovado' : (strtolower($calc['sit']) === 'recuperação' || $calc['sit'] === 'Recuperação' ? 'recuperacao' : 'reprovado') ?>">
          <?php $rc = $calc['sit']==='Aprovado'?'aprovado':($calc['sit']==='Recuperação'?'recuperacao':'reprovado'); ?>
          <div class="result-top <?=$rc?>">
            <div class="result-mf"><?= number_format($calc['mf'],2,',','') ?></div>
            <div class="result-detail">
              <span class="badge badge-<?= $rc==='aprovado'?'teal':($rc==='recuperacao'?'amber':'rose') ?>"><?= htmlspecialchars($calc['sit']) ?></span>
              <div class="result-desc" style="margin-top:.4rem">
                <?= $rc==='aprovado'?'Parabéns! Aprovado.':($rc==='recuperacao'?'Avaliação adicional necessária.':'Abaixo do mínimo exigido.') ?>
              </div>
            </div>
          </div>
          <div class="result-breakdown">
            <div class="rb-item">M1 <span><?= number_format($calc['m1'],2,',','') ?></span></div>
            <div class="rb-item">M2 <span><?= number_format($calc['m2'],2,',','') ?></span></div>
            <div class="rb-item">MF <span><?= number_format($calc['mf'],2,',','') ?></span></div>
            <div class="rb-formula">(<?= number_format($calc['m1'],1,',','') ?> + <?= number_format($calc['m2'],1,',','') ?>×2) ÷ 3</div>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Resumo de aprovações -->
      <div class="card">
        <div class="card-title"><span class="ct-icon">🎯</span> Resumo de Situação</div>
        <?php
        $aprov=$recup=$reprov=$pend=0;
        foreach($DISCIPLINAS as $d){
          $mf=mediaFinal($d);
          if($mf===null){$pend++;continue;}
          if($mf>=7)$aprov++;elseif($mf>=5)$recup++;else$reprov++;
        }
        $total=count($DISCIPLINAS);
        ?>
        <div class="grid-2 gap-sm">
          <div style="background:var(--teal-d);border:1px solid rgba(0,221,176,.2);border-radius:10px;padding:1rem;text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--teal)"><?=$aprov?></div>
            <div class="tag">Aprovado</div>
          </div>
          <div style="background:var(--amber-d);border:1px solid rgba(245,166,35,.2);border-radius:10px;padding:1rem;text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--amber)"><?=$recup?></div>
            <div class="tag">Recuperação</div>
          </div>
          <div style="background:var(--rose-d);border:1px solid rgba(245,97,122,.2);border-radius:10px;padding:1rem;text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--rose)"><?=$reprov?></div>
            <div class="tag">Reprovado</div>
          </div>
          <div style="background:rgba(255,255,255,.03);border:1px solid var(--line2);border-radius:10px;padding:1rem;text-align:center">
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--muted)"><?=$pend?></div>
            <div class="tag">Pendente</div>
          </div>
        </div>
        <div class="divider"></div>
        <?php
        $allMF=[];foreach($DISCIPLINAS as $d){$mf=mediaFinal($d);if($mf!==null)$allMF[]=$mf;}
        $globalCR=count($allMF)?round(array_sum($allMF)/count($allMF),2):0;
        ?>
        <div style="text-align:center;padding:1rem">
          <div class="tag" style="margin-bottom:.4rem">Coeficiente de Rendimento Atual</div>
          <div style="font-family:'Syne',sans-serif;font-size:3rem;font-weight:800;color:var(--violet);text-shadow:0 0 30px rgba(139,108,247,.4)"><?= number_format($globalCR,2,',','') ?></div>
          <div class="pbar mt-1"><div class="pbar-fill pbar-violet" style="width:<?=($globalCR/10*100)?>%"></div></div>
        </div>
        <div class="tag" style="text-align:center;margin-top:.5rem">
          Mínimo para aprovação: M1+M2×2÷3 ≥ 7.00
        </div>
      </div>
    </div>

    <!-- ══════════ HORÁRIOS ══════════ -->
    <?php elseif($secao==='horarios'): ?>
    <div class="card anim-1">
      <div class="card-title"><span class="ct-icon">🗓</span> Grade de Horários — 2025/1</div>
      <div style="overflow-x:auto">
        <div class="schedule-grid" style="min-width:600px">
          <!-- Header -->
          <div class="sched-hdr"></div>
          <?php foreach(['SEG','TER','QUA','QUI','SEX','SÁB'] as $dia): ?>
          <div class="sched-hdr"><?=$dia?></div>
          <?php endforeach; ?>
          <?php
          $horarios=[
            '19:00'=> ['—','CC403','CC401','—','CC402','—'],
            '20:00'=> ['CC404','CC403','CC401','CC405','CC402','—'],
            '21:00'=> ['CC404','—','HU101','CC405','—','—'],
            '22:00'=> ['—','—','HU101','—','—','—'],
          ];
          $cores=['CC401'=>'violet','CC402'=>'teal','CC403'=>'amber','CC404'=>'sky','CC405'=>'rose','HU101'=>'teal'];
          $nomes=['CC401'=>'Algoritmos Av.','CC402'=>'Banco de Dados','CC403'=>'Eng. Software','CC404'=>'Redes','CC405'=>'IA','HU101'=>'Ética'];
          $salas=['CC401'=>'Lab 02','CC402'=>'Lab 05','CC403'=>'Sala 12','CC404'=>'Lab 03','CC405'=>'Lab 01','HU101'=>'Sala 08'];
          foreach($horarios as $h=>$cells):
          ?>
          <div class="sched-time"><?=$h?></div>
          <?php foreach($cells as $c): if($c==='—'): ?>
            <div class="sched-cell sched-empty"></div>
          <?php else: ?>
            <div class="sched-cell sched-<?=$cores[$c]?>">
              <div class="sched-name"><?=$nomes[$c]?></div>
              <div class="sched-sala"><?=$salas[$c]?></div>
            </div>
          <?php endif; endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="flex gap mt-2" style="flex-wrap:wrap">
        <?php foreach($nomes as $cod=>$nome): ?>
        <span class="badge badge-<?=$cores[$cod]?>"><?=$cod?> — <?=$nome?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ══════════ FREQUÊNCIA ══════════ -->
    <?php elseif($secao==='frequencia'): ?>
    <div class="card anim-1">
      <div class="card-title"><span class="ct-icon">📋</span> Frequência por Disciplina</div>
      <table class="tbl">
        <thead><tr>
          <th>Disciplina</th><th>Faltas</th><th>Limite (25%)</th><th>Frequência</th><th>Status</th>
        </tr></thead>
        <tbody>
        <?php foreach($DISCIPLINAS as $d):
          $pct = pctFaltas($d['faltas'],$d['max_faltas']);
          $freq = 100-$pct;
          $ok = $pct <= 25;
        ?>
          <tr>
            <td><span class="fw-7"><?= htmlspecialchars($d['nome']) ?></span><br><span class="mono text-xs text-muted"><?=$d['cod']?></span></td>
            <td><span class="mono <?= $pct>50?'text-rose':($pct>25?'text-amber':'text-teal') ?>"><?=$d['faltas']?></span></td>
            <td class="mono text-muted text-sm"><?=$d['max_faltas']?> aulas</td>
            <td>
              <div class="flex items-center gap" style="min-width:120px">
                <div class="pbar" style="flex:1"><div class="pbar-fill <?= $ok?'pbar-teal':'pbar-rose'?>" style="width:<?=$freq?>%"></div></div>
                <span class="mono text-xs"><?=$freq?>%</span>
              </div>
            </td>
            <td><span class="badge badge-<?=$ok?'teal':'rose'?>"><?=$ok?'Regular':'⚠ Atenção'?></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card mt-2 anim-2" style="background:rgba(245,166,35,.05);border-color:rgba(245,166,35,.2)">
      <div class="flex items-center gap"><span style="font-size:1.2rem">⚠️</span>
      <div><div class="fw-7 text-amber">Atenção: Inteligência Artificial</div>
      <div class="text-sm text-muted mt-1">Você possui <?= $DISCIPLINAS[4]['faltas'] ?> faltas nesta disciplina. O limite máximo é <?= $DISCIPLINAS[4]['max_faltas'] ?> faltas (25% da carga horária). Risco de reprovação por falta.</div></div></div>
    </div>

    <!-- ══════════ FINANCEIRO ══════════ -->
    <?php elseif($secao==='financeiro'): ?>
    <div class="grid-2 anim-1">
      <div>
        <div class="card">
          <div class="card-title"><span class="ct-icon">💳</span> Mensalidades 2025</div>
          <?php foreach($FINANCEIRO as $f): ?>
          <div class="fin-row">
            <div style="font-size:1.1rem"><?= $f['status']==='pago'?'✅':'🔴' ?></div>
            <div style="flex:1">
              <div class="fin-mes"><?= $f['mes'] ?></div>
              <div class="fin-venc">Vencimento: <?= $f['venc'] ?></div>
            </div>
            <div class="text-right">
              <div class="fin-valor">R$ <?= number_format($f['valor'],2,',','.') ?></div>
              <span class="badge badge-<?= $f['status']==='pago'?'teal':'rose' ?>"><?= $f['status']==='pago'?'Pago':'Pendente' ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <div class="card fin-total-box">
          <div class="card-title">💰 Resumo Financeiro</div>
          <?php
          $pago = array_sum(array_column(array_filter($FINANCEIRO,fn($f)=>$f['status']==='pago'),'valor'));
          $pend = array_sum(array_column(array_filter($FINANCEIRO,fn($f)=>$f['status']==='pendente'),'valor'));
          ?>
          <div style="margin-bottom:1rem">
            <div class="tag">Total Pago</div>
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--teal)">R$ <?= number_format($pago,2,',','.') ?></div>
          </div>
          <div class="divider"></div>
          <div>
            <div class="tag">Em Aberto</div>
            <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--rose)">R$ <?= number_format($pend,2,',','.') ?></div>
          </div>
          <div class="pbar mt-2"><div class="pbar-fill pbar-teal" style="width:<?= round($pago/($pago+$pend)*100) ?>%"></div></div>
          <div class="text-xs text-muted mono mt-1"><?= round($pago/($pago+$pend)*100) ?>% quitado</div>
        </div>
        <div class="card mt-2" style="background:var(--rose-d);border-color:rgba(245,97,122,.25)">
          <div class="fw-7 text-rose">🔴 Boleto Pendente</div>
          <div class="text-sm text-muted mt-1">Maio/2025 · R$ 1.890,00</div>
          <div class="text-xs mono text-muted mt-1">Vence em 10/05/2025</div>
          <div style="margin-top:1rem;background:var(--rose);color:#fff;border-radius:8px;padding:.7rem;text-align:center;font-family:'Syne',sans-serif;font-weight:700;font-size:.85rem;letter-spacing:.06em;cursor:none">
            Gerar Boleto
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════ BIBLIOTECA ══════════ -->
    <?php elseif($secao==='biblioteca'): ?>
    <div class="grid-2 anim-1">
      <div class="card">
        <div class="card-title"><span class="ct-icon">📚</span> Empréstimos Ativos</div>
        <?php foreach($BIBLIOTECA as $b): ?>
        <div class="book-row">
          <div class="book-icon">📖</div>
          <div class="book-info">
            <div class="book-title"><?= htmlspecialchars($b['titulo']) ?></div>
            <div class="book-author"><?= htmlspecialchars($b['autor']) ?></div>
            <?php if($b['devol']!=='—'): ?>
            <div class="book-devol mono">Devolução: <?= $b['devol'] ?></div>
            <?php endif; ?>
          </div>
          <span class="badge badge-<?= $b['status']==='emprestado'?'amber':'sky' ?>"><?= $b['status'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="card">
        <div class="card-title"><span class="ct-icon">🔍</span> Buscar Acervo</div>
        <div style="background:var(--bg3);border:1px solid var(--line2);border-radius:9px;padding:.8rem 1rem;display:flex;align-items:center;gap:.7rem;margin-bottom:1rem">
          <span>🔍</span>
          <input type="text" placeholder="Título, autor, ISBN..." style="background:none;border:none;color:var(--ink);flex:1;font-size:.88rem">
        </div>
        <?php
        $acervo=[
          ['t'=>'Estruturas de Dados','a'=>'Nivio Ziviani','disp'=>true],
          ['t'=>'Redes de Computadores','a'=>'Tanenbaum','disp'=>true],
          ['t'=>'Inteligência Artificial','a'=>'Russell & Norvig','disp'=>false],
          ['t'=>'Engenharia de Requisitos','a'=>'Pressman','disp'=>true],
        ];
        foreach($acervo as $a): ?>
        <div class="book-row" style="background:var(--bg);border-color:var(--line)">
          <div class="book-icon" style="background:rgba(56,200,245,.05);border-color:rgba(56,200,245,.15)">📘</div>
          <div class="book-info">
            <div class="book-title"><?= htmlspecialchars($a['t']) ?></div>
            <div class="book-author"><?= htmlspecialchars($a['a']) ?></div>
          </div>
          <span class="badge badge-<?=$a['disp']?'teal':'rose'?>"><?=$a['disp']?'Disponível':'Indisponível'?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ══════════ NOTÍCIAS ══════════ -->
    <?php elseif($secao==='noticias'): ?>
    <div class="grid-2 anim-1">
      <div>
        <?php foreach($NOTICIAS as $n): ?>
        <div class="news-item" style="margin-bottom:.75rem;padding:1.25rem">
          <div class="flex items-center gap" style="margin-bottom:.75rem">
            <?php if($n['tipo']==='urgente'): ?><span class="badge badge-rose">🔴 Urgente</span>
            <?php elseif($n['tipo']==='info'): ?><span class="badge badge-sky">ℹ Info</span>
            <?php else: ?><span class="badge badge-muted">Geral</span><?php endif; ?>
            <span class="tag"><?= $n['data'] ?>/2025</span>
          </div>
          <div class="news-titulo" style="font-size:1rem"><?= htmlspecialchars($n['titulo']) ?></div>
          <div class="news-resumo" style="margin-top:.4rem"><?= htmlspecialchars($n['resumo']) ?></div>
          <div style="margin-top:.9rem;color:var(--violet);font-size:.78rem;cursor:none">Leia mais →</div>
        </div>
        <?php endforeach; ?>
      </div>
      <div>
        <div class="card">
          <div class="card-title">📅 Eventos do Mês</div>
          <?php foreach($EVENTOS as $e): ?>
          <div class="flex items-center gap" style="padding:.65rem 0;border-bottom:1px solid var(--line)">
            <span class="badge badge-<?=$e['cor']?>"><?=$e['dia']?> <?=$e['mes']?></span>
            <div style="flex:1">
              <div class="text-sm fw-7"><?= htmlspecialchars($e['titulo']) ?></div>
              <div class="text-xs text-muted mono"><?=$e['hora']?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════ SECRETARIA ══════════ -->
    <?php elseif($secao==='secretaria'): ?>
    <div class="grid-2 anim-1">
      <div class="card">
        <div class="card-title">🏛 Serviços Disponíveis</div>
        <?php
        $servicos=[
          ['ico'=>'📄','nome'=>'Declaração de Matrícula','prazo'=>'1 dia útil','cor'=>'teal'],
          ['ico'=>'📜','nome'=>'Histórico Escolar','prazo'=>'3 dias úteis','cor'=>'violet'],
          ['ico'=>'🎓','nome'=>'Diploma / Certificado','prazo'=>'30 dias úteis','cor'=>'amber'],
          ['ico'=>'🔄','nome'=>'Transferência de Curso','prazo'=>'Análise','cor'=>'sky'],
          ['ico'=>'📑','nome'=>'Atestado de Frequência','prazo'=>'1 dia útil','cor'=>'teal'],
          ['ico'=>'✏️','nome'=>'Revisão de Prova','prazo'=>'5 dias úteis','cor'=>'rose'],
        ];
        foreach($servicos as $s): ?>
        <div class="req-item">
          <div class="req-ico" style="background:var(--<?=$s['cor']?>-d, rgba(139,108,247,.12));border:1px solid rgba(0,0,0,.2)"><?=$s['ico']?></div>
          <div style="flex:1">
            <div class="fw-7 text-sm"><?= htmlspecialchars($s['nome']) ?></div>
            <div class="text-xs text-muted">Prazo: <?= $s['prazo'] ?></div>
          </div>
          <div style="background:rgba(139,108,247,.1);border:1px solid rgba(139,108,247,.2);border-radius:7px;padding:.35rem .8rem;font-size:.72rem;color:var(--violet);cursor:none;font-family:'JetBrains Mono',monospace">
            Solicitar
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div>
        <div class="card">
          <div class="card-title">📬 Minhas Solicitações</div>
          <?php
          $reqs=[
            ['nome'=>'Declaração de Matrícula','data'=>'22/04/2025','status'=>'concluido'],
            ['nome'=>'Histórico Escolar','data'=>'15/03/2025','status'=>'concluido'],
            ['nome'=>'Revisão de Prova — CC403','data'=>'28/04/2025','status'=>'andamento'],
          ];
          foreach($reqs as $r): ?>
          <div class="req-item" style="background:var(--card);border-color:var(--line)">
            <div style="flex:1">
              <div class="fw-7 text-sm"><?= htmlspecialchars($r['nome']) ?></div>
              <div class="text-xs text-muted mono"><?= $r['data'] ?></div>
            </div>
            <span class="badge badge-<?= $r['status']==='concluido'?'teal':'amber' ?>">
              <?= $r['status']==='concluido'?'✔ Concluído':'⏳ Em andamento' ?>
            </span>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="card mt-2" style="background:rgba(0,221,176,.04);border-color:rgba(0,221,176,.15)">
          <div class="fw-7 text-teal">📞 Atendimento Presencial</div>
          <div class="text-sm text-muted mt-1">Segunda a Sexta · 08h–22h<br>Bloco A, Térreo — Sala 002</div>
          <div class="divider"></div>
          <div class="text-sm">📧 secretaria@uninova.edu.br</div>
          <div class="text-sm mt-1">📱 (11) 3456-7890</div>
        </div>
      </div>
    </div>

    <!-- ══════════ SUPORTE ══════════ -->
    <?php elseif($secao==='suporte'): ?>
    <div class="grid-2 anim-1">
      <div class="card">
        <div class="card-title">💬 Perguntas Frequentes</div>
        <?php
        $faqs=[
          ['q'=>'Como calcular minha média final?','a'=>'A média final é calculada pela fórmula: MF = (M1 + M2×2) ÷ 3. A segunda avaliação tem peso duplo.'],
          ['q'=>'Qual o limite de faltas por disciplina?','a'=>'O aluno pode faltar no máximo 25% das aulas. Para disciplinas de 80h, o limite é 20 faltas; para 60h, 15 faltas.'],
          ['q'=>'Como emitir uma declaração de matrícula?','a'=>'Acesse a seção Secretaria > Serviços Disponíveis e solicite a declaração. O prazo de entrega é de 1 dia útil.'],
          ['q'=>'Quando abrem as rematrículas?','a'=>'As rematrículas para o próximo semestre ocorrem entre os dias 01 e 15 do mês anterior ao início do semestre.'],
          ['q'=>'Como solicitar revisão de prova?','a'=>'Solicite através da Secretaria Virtual em até 5 dias úteis após a divulgação das notas.'],
          ['q'=>'Onde fica a biblioteca?','a'=>'Biblioteca Central — Bloco C, 1º andar. Funciona de segunda a sábado, das 07h às 23h.'],
        ];
        foreach($faqs as $i=>$f): ?>
        <div class="faq-item" onclick="this.classList.toggle('open')">
          <div class="faq-q">
            <?= htmlspecialchars($f['q']) ?>
            <span class="faq-chevron">▾</span>
          </div>
          <div class="faq-a"><?= htmlspecialchars($f['a']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div>
        <div class="card">
          <div class="card-title">📩 Abrir Chamado</div>
          <div class="form-group">
            <label class="form-label">Assunto</label>
            <select class="form-input" style="appearance:none">
              <option>Selecione uma categoria</option>
              <option>Problemas com notas</option>
              <option>Acesso ao sistema</option>
              <option>Financeiro</option>
              <option>Outros</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Mensagem</label>
            <textarea class="form-input" rows="5" placeholder="Descreva seu problema..." style="resize:vertical"></textarea>
          </div>
          <div style="background:var(--violet);color:#fff;border-radius:9px;padding:.85rem;text-align:center;font-family:'Syne',sans-serif;font-weight:700;font-size:.88rem;letter-spacing:.06em;cursor:none">
            Enviar Chamado
          </div>
        </div>
        <div class="card mt-2">
          <div class="card-title">📊 Status dos Sistemas</div>
          <?php
          $sistemas=[['Portal Acadêmico','online'],['Biblioteca Digital','online'],['E-mail Institucional','online'],['VPN Institucional','manutencao']];
          foreach($sistemas as $s): ?>
          <div class="flex items-center justify-between" style="padding:.5rem 0;border-bottom:1px solid var(--line)">
            <span class="text-sm"><?=$s[0]?></span>
            <span class="badge badge-<?=$s[1]==='online'?'teal':'amber'?>"><?=$s[1]==='online'?'● Online':'⚙ Manutenção'?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ══════════ PERFIL ══════════ -->
    <?php elseif($secao==='perfil'): ?>
    <div class="anim-1">
      <div class="profile-hero">
        <div class="profile-avatar"><?= htmlspecialchars($user['avatar']) ?></div>
        <div>
          <div class="profile-info-name"><?= htmlspecialchars($user['nome']) ?></div>
          <div class="profile-info-ra">RA: <?= htmlspecialchars($user['ra']) ?></div>
          <div class="profile-chips mt-1">
            <span class="badge badge-violet"><?= htmlspecialchars($user['curso']) ?></span>
            <span class="badge badge-teal"><?= htmlspecialchars($user['periodo']) ?></span>
            <span class="badge badge-muted">Turno <?= htmlspecialchars($user['turno']) ?></span>
            <span class="badge badge-<?= $user['situacao']==='Regular'?'teal':'amber' ?>"><?= $user['situacao'] ?></span>
          </div>
        </div>
      </div>
      <div class="grid-3 anim-2">
        <div class="card">
          <div class="card-title">👤 Dados Pessoais</div>
          <?php
          $dados=[['Nome Completo',$user['nome']],['RA / Matrícula',$user['ra']],['Curso',$user['curso']],['Período',$user['periodo']],['Turno',$user['turno']]];
          foreach($dados as [$k,$v]): ?>
          <div class="flex justify-between" style="padding:.55rem 0;border-bottom:1px solid var(--line)">
            <span class="text-xs text-muted"><?=$k?></span>
            <span class="text-sm mono"><?= htmlspecialchars($v) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="card">
          <div class="card-title">🔐 Segurança</div>
          <div class="form-group">
            <label class="form-label">Senha Atual</label>
            <input class="form-input" type="password" placeholder="••••••••">
          </div>
          <div class="form-group">
            <label class="form-label">Nova Senha</label>
            <input class="form-input" type="password" placeholder="••••••••">
          </div>
          <div class="form-group">
            <label class="form-label">Confirmar</label>
            <input class="form-input" type="password" placeholder="••••••••">
          </div>
          <div style="background:var(--violet-d);border:1px solid rgba(139,108,247,.25);border-radius:8px;padding:.8rem;text-align:center;font-size:.82rem;color:var(--violet);font-weight:600;cursor:none">
            Alterar Senha
          </div>
        </div>
        <div class="card">
          <div class="card-title">📊 Estatísticas</div>
          <?php if($user['cr']): ?>
          <div style="text-align:center;padding:1rem 0">
            <div class="tag">Coeficiente de Rendimento</div>
            <div style="font-family:'Syne',sans-serif;font-size:3.5rem;font-weight:800;color:var(--violet);line-height:1;margin:.5rem 0"><?= $user['cr'] ?></div>
            <div class="pbar"><div class="pbar-fill pbar-violet" style="width:<?=($user['cr']/10*100)?>%"></div></div>
          </div>
          <div class="divider"></div>
          <?php endif; ?>
          <div class="flex justify-between" style="padding:.5rem 0;border-bottom:1px solid var(--line)"><span class="text-xs text-muted">Disciplinas</span><span class="text-sm mono"><?=count($DISCIPLINAS)?></span></div>
          <div class="flex justify-between" style="padding:.5rem 0"><span class="text-xs text-muted">Situação</span><span class="badge badge-teal"><?=$user['situacao']?></span></div>
        </div>
      </div>
    </div>

    <!-- ══════════ CALENDÁRIO ══════════ -->
    <?php elseif($secao==='calendario'): ?>
    <div class="grid-2 anim-1">
      <div class="card">
        <div class="card-title">📅 Calendário Acadêmico 2025/1</div>
        <?php
        $cal=[
          ['data'=>'03/02 – 10/02','evento'=>'Período de Rematrícula','tipo'=>'violet'],
          ['data'=>'17/02','evento'=>'Início das Aulas 2025/1','tipo'=>'teal'],
          ['data'=>'07/04 – 11/04','evento'=>'Semana Santa (Recesso)','tipo'=>'muted'],
          ['data'=>'28/04 – 02/05','evento'=>'Provas M1','tipo'=>'rose'],
          ['data'=>'12/05 – 16/05','evento'=>'Semana Acadêmica','tipo'=>'sky'],
          ['data'=>'16/06 – 27/06','evento'=>'Provas M2 / Finais','tipo'=>'rose'],
          ['data'=>'30/06 – 04/07','evento'=>'Exames de Recuperação','tipo'=>'amber'],
          ['data'=>'07/07','evento'=>'Encerramento Semestre','tipo'=>'teal'],
          ['data'=>'14/07 – 01/08','evento'=>'Recesso de Férias','tipo'=>'muted'],
        ];
        foreach($cal as $c): ?>
        <div class="flex items-center gap" style="padding:.75rem 0;border-bottom:1px solid var(--line)">
          <span class="badge badge-<?=$c['tipo']?>" style="flex-shrink:0;min-width:110px;justify-content:center"><?= $c['data'] ?></span>
          <span class="text-sm"><?= htmlspecialchars($c['evento']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="card">
        <div class="card-title">📌 Próximas Avaliações</div>
        <?php foreach($EVENTOS as $e): ?>
        <div class="flex items-center gap" style="padding:.8rem;background:var(--bg3);border-radius:10px;border:1px solid var(--line);margin-bottom:.5rem">
          <div style="width:50px;height:50px;border-radius:12px;background:rgba(0,0,0,.3);border:1px solid var(--line2);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0">
            <span style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--<?=$e['cor']?>);line-height:1"><?=$e['dia']?></span>
            <span style="font-size:.5rem;color:var(--muted);font-family:'JetBrains Mono',monospace"><?=$e['mes']?></span>
          </div>
          <div style="flex:1">
            <div class="fw-7 text-sm"><?= htmlspecialchars($e['titulo']) ?></div>
            <div class="text-xs text-muted mono"><?=$e['hora']?></div>
          </div>
          <span class="badge badge-<?=$e['cor']?>">Em <?= (int)$e['dia'] - 28 > 0 ? ((int)$e['dia']-28).'d':'hoje' ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php endif; ?>

    </div><!-- /page-content -->
  </main>

</div><!-- /app -->

<?php endif; ?>

<!-- ═══════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════ -->
<script>
/* ── Custom cursor ── */
const cur=document.getElementById('cur');
const ring=document.getElementById('cur-ring');
if(cur&&ring){
  let mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cur.style.left=mx+'px';cur.style.top=my+'px'});
  (function loop(){rx+=(mx-rx)*.1;ry+=(my-ry)*.1;ring.style.left=rx+'px';ring.style.top=ry+'px';requestAnimationFrame(loop)})();
  document.querySelectorAll('a,button,input,select,textarea,[style*="cursor:none"]').forEach(el=>{
    el.addEventListener('mouseenter',()=>{cur.style.width='14px';cur.style.height='14px';ring.style.width='46px';ring.style.height='46px';ring.style.borderColor='rgba(0,221,176,.7)'});
    el.addEventListener('mouseleave',()=>{cur.style.width='8px';cur.style.height='8px';ring.style.width='32px';ring.style.height='32px';ring.style.borderColor='rgba(0,221,176,.35)'});
  });
}

/* ── Particles ── */
const cv=document.getElementById('pcvs');
if(cv){
  const cx=cv.getContext('2d');let W,H,pts=[];
  const resize=()=>{W=cv.width=window.innerWidth;H=cv.height=window.innerHeight};
  resize();window.addEventListener('resize',resize);
  for(let i=0;i<50;i++)pts.push({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.2,vy:(Math.random()-.5)*.15,r:Math.random()*1.2+.3,a:Math.random()*.4+.1});
  (function draw(){
    cx.clearRect(0,0,W,H);
    pts.forEach(p=>{
      p.x+=p.vx;p.y+=p.vy;
      if(p.x<0)p.x=W;if(p.x>W)p.x=0;if(p.y<0)p.y=H;if(p.y>H)p.y=0;
      cx.beginPath();cx.arc(p.x,p.y,p.r,0,Math.PI*2);
      cx.fillStyle=`rgba(139,108,247,${p.a*.6})`;cx.fill();
    });
    for(let i=0;i<pts.length;i++)for(let j=i+1;j<pts.length;j++){
      const dx=pts[i].x-pts[j].x,dy=pts[i].y-pts[j].y,d=Math.hypot(dx,dy);
      if(d<120){cx.beginPath();cx.moveTo(pts[i].x,pts[i].y);cx.lineTo(pts[j].x,pts[j].y);cx.strokeStyle=`rgba(139,108,247,${.05*(1-d/120)})`;cx.lineWidth=.5;cx.stroke()}
    }
    requestAnimationFrame(draw);
  })();
}

/* ── Live media calc ── */
const im1=document.getElementById('m1'),im2=document.getElementById('m2'),lp=document.getElementById('live-preview');
if(im1&&im2&&lp){
  function upd(){
    const v1=parseFloat(im1.value),v2=parseFloat(im2.value);
    if(isNaN(v1)||isNaN(v2)){lp.innerHTML='<span style="color:var(--muted)">—</span>';return}
    const mf=Math.round((v1+v2*2)/3*100)/100;
    const cor=mf>=7?'teal':mf>=5?'amber':'rose';
    const sit=mf>=7?'Aprovado':mf>=5?'Recuperação':'Reprovado';
    lp.innerHTML=`<span style="color:var(--${cor})">${mf.toFixed(2).replace('.',',')} — ${sit}</span>`;
  }
  im1.addEventListener('input',upd);im2.addEventListener('input',upd);
}
</script>
</body>
</html>
