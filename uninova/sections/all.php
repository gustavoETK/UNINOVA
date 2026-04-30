<?php
// ─── SEÇÕES INDIVIDUAIS ─────────────────────────────────────
// Arquivo: sections/all.php — carrega a seção correta via include

function renderSection(string $s, array $data): void {
    extract($data);
    switch ($s) {
        case 'notas':       include __DIR__ . '/notas.php';      break;
        case 'horarios':    include __DIR__ . '/horarios.php';   break;
        case 'frequencia':  include __DIR__ . '/frequencia.php'; break;
        case 'financeiro':  include __DIR__ . '/financeiro.php'; break;
        case 'biblioteca':  include __DIR__ . '/biblioteca.php'; break;
        case 'noticias':    include __DIR__ . '/noticias.php';   break;
        case 'secretaria':  include __DIR__ . '/secretaria.php'; break;
        case 'suporte':     include __DIR__ . '/suporte.php';    break;
        case 'perfil':      include __DIR__ . '/perfil.php';     break;
        case 'calendario':  include __DIR__ . '/calendario.php'; break;
        default:            include __DIR__ . '/dashboard.php';  break;
    }
}
