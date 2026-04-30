<?php
// ═══════════════════════════════════════════════════════════════
//  UNINOVA — API INTERNA v4.0
//  Endpoint para ações AJAX (sem reload de página)
// ═══════════════════════════════════════════════════════════════
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../includes/data.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = ['ok' => false, 'msg' => 'Ação desconhecida'];

switch ($action) {

    case 'login':
        $u = trim($_POST['usuario'] ?? '');
        $p = trim($_POST['senha']   ?? '');
        if (isset($USERS[$u]) && $USERS[$u]['senha'] === $p) {
            $_SESSION['user'] = $u;
            $response = ['ok' => true, 'redirect' => '?s=dashboard', 'role' => $USERS[$u]['role']];
        } else {
            $response = ['ok' => false, 'msg' => 'Usuário ou senha incorretos.'];
        }
        break;

    case 'logout':
        session_destroy();
        $response = ['ok' => true, 'redirect' => '?'];
        break;

    case 'calcmedia':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $m1 = floatval(str_replace(',', '.', $_POST['m1'] ?? 0));
        $m2 = floatval(str_replace(',', '.', $_POST['m2'] ?? 0));
        $m1 = max(0, min(10, $m1));
        $m2 = max(0, min(10, $m2));
        $mf = round(($m1 + $m2 * 2) / 3, 2);
        $sit = $mf >= 7 ? 'Aprovado' : ($mf >= 5 ? 'Recuperação' : 'Reprovado');
        $_SESSION['calc'] = compact('m1','m2','mf','sit');
        $response = ['ok'=>true, 'mf'=>$mf, 'sit'=>$sit, 'm1'=>$m1, 'm2'=>$m2];
        break;

    case 'marcar_lido':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $id = intval($_POST['id'] ?? 0);
        $_SESSION['noticias_lidas'][$id] = true;
        $response = ['ok' => true];
        break;

    case 'solicitar':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $nome = htmlspecialchars(strip_tags($_POST['nome'] ?? ''));
        $protocolo = 'REQ-' . date('Y') . '-' . str_pad(rand(1,9999),4,'0',STR_PAD_LEFT);
        if (!isset($_SESSION['solicitacoes'])) $_SESSION['solicitacoes'] = [];
        $_SESSION['solicitacoes'][] = [
            'nome' => $nome,
            'data' => date('d/m/Y'),
            'status' => 'andamento',
            'protocolo' => $protocolo,
        ];
        $response = ['ok'=>true, 'protocolo'=>$protocolo, 'msg'=>"Solicitação registrada! Protocolo: $protocolo"];
        break;

    case 'abrir_chamado':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $assunto = htmlspecialchars(strip_tags($_POST['assunto'] ?? ''));
        $mensagem = htmlspecialchars(strip_tags($_POST['mensagem'] ?? ''));
        if (!$assunto || $assunto === 'Selecione uma categoria') {
            $response = ['ok'=>false,'msg'=>'Selecione uma categoria.']; break;
        }
        if (strlen($mensagem) < 10) {
            $response = ['ok'=>false,'msg'=>'Mensagem muito curta (mín. 10 caracteres).']; break;
        }
        $numero = 'UNI-' . rand(1000,9999);
        $response = ['ok'=>true, 'chamado'=>$numero, 'msg'=>"Chamado $numero aberto! Resposta em até 48h."];
        break;

    case 'gerar_boleto':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $barcode = implode('.',array_map(fn($i)=>str_pad(rand(0,99999999),8,'0',STR_PAD_LEFT),[1,2,3])).'.'
                  .rand(1,9).'.'.rand(10000,99999).'.'
                  .str_pad(rand(0,9999999999999999),16,'0',STR_PAD_LEFT).'.'.rand(1,9).'.'.date('Ymd').'00001890';
        $response = ['ok'=>true, 'barcode'=>$barcode, 'msg'=>'Boleto gerado com sucesso!'];
        break;

    case 'alterar_senha':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $atual = $_POST['atual'] ?? '';
        $nova  = $_POST['nova']  ?? '';
        $conf  = $_POST['conf']  ?? '';
        $user  = $USERS[$_SESSION['user']];
        if ($user['senha'] !== $atual) { $response = ['ok'=>false,'msg'=>'Senha atual incorreta.']; break; }
        if (strlen($nova) < 6) { $response = ['ok'=>false,'msg'=>'Nova senha muito curta.']; break; }
        if ($nova !== $conf) { $response = ['ok'=>false,'msg'=>'As senhas não coincidem.']; break; }
        // Em um sistema real, atualizaria no banco de dados
        $response = ['ok'=>true, 'msg'=>'Senha alterada com sucesso!'];
        break;

    case 'renovar_livro':
        if (!isset($_SESSION['user'])) { $response = ['ok'=>false,'msg'=>'Não autenticado']; break; }
        $id = intval($_POST['id'] ?? 0);
        $response = ['ok'=>true, 'msg'=>'Empréstimo renovado por mais 14 dias!'];
        break;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
