<?php
// ═══════════════════════════════════════════════════════════════
//  UNINOVA — DATABASE SIMULADO v4.0
//  Dados centralizados do sistema acadêmico
// ═══════════════════════════════════════════════════════════════

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
        'email'   => 'lucas.ferreira@aluno.uninova.edu.br',
        'telefone'=> '(11) 98765-4321',
        'cpf'     => '123.456.789-00',
        'entrada' => '2023',
        'foto'    => null,
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
        'email'   => 'ana.rocha@uninova.edu.br',
        'telefone'=> '(11) 3456-7891',
        'cpf'     => null,
        'entrada' => '2018',
        'foto'    => null,
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
        'email'   => 'r.almeida@uninova.edu.br',
        'telefone'=> '(11) 3456-7892',
        'cpf'     => null,
        'entrada' => '2015',
        'foto'    => null,
    ],
];

$DISCIPLINAS = [
    ['cod'=>'CC401','nome'=>'Algoritmos Avançados',      'prof'=>'Dr. Silva',       'm1'=>7.5, 'm2'=>null, 'ch'=>80, 'faltas'=>4,  'max_faltas'=>20, 'sala'=>'Lab 02', 'cor'=>'violet'],
    ['cod'=>'CC402','nome'=>'Banco de Dados II',          'prof'=>'Dra. Ana Paula',  'm1'=>8.2, 'm2'=>8.8,  'ch'=>60, 'faltas'=>2,  'max_faltas'=>15, 'sala'=>'Lab 05', 'cor'=>'teal'],
    ['cod'=>'CC403','nome'=>'Engenharia de Software',     'prof'=>'Prof. Torres',    'm1'=>6.0, 'm2'=>null, 'ch'=>80, 'faltas'=>8,  'max_faltas'=>20, 'sala'=>'Sala 12','cor'=>'amber'],
    ['cod'=>'CC404','nome'=>'Redes de Computadores',      'prof'=>'Dra. Lima',       'm1'=>9.1, 'm2'=>7.4,  'ch'=>60, 'faltas'=>1,  'max_faltas'=>15, 'sala'=>'Lab 03', 'cor'=>'sky'],
    ['cod'=>'CC405','nome'=>'Inteligência Artificial',    'prof'=>'Dr. Mendes',      'm1'=>5.5, 'm2'=>null, 'ch'=>80, 'faltas'=>12, 'max_faltas'=>20, 'sala'=>'Lab 01', 'cor'=>'rose'],
    ['cod'=>'HU101','nome'=>'Ética e Legislação',         'prof'=>'Prof. Castro',    'm1'=>7.0, 'm2'=>8.0,  'ch'=>40, 'faltas'=>0,  'max_faltas'=>10, 'sala'=>'Sala 08','cor'=>'teal'],
];

$NOTICIAS = [
    ['id'=>1,'tipo'=>'urgente','titulo'=>'Calendário de Provas Finais 2025/1','data'=>'28/04/2025','resumo'=>'Confira as datas das provas finais do semestre. Todas as provas serão realizadas nos laboratórios habituais.','lido'=>false],
    ['id'=>2,'tipo'=>'normal', 'titulo'=>'Semana Acadêmica — Inscrições Abertas','data'=>'25/04/2025','resumo'=>'Palestras, workshops e hackathon com empresas parceiras. Inscreva-se já no Portal do Aluno.','lido'=>true],
    ['id'=>3,'tipo'=>'normal', 'titulo'=>'Bolsas de Iniciação Científica 2025/2','data'=>'20/04/2025','resumo'=>'12 vagas disponíveis para pesquisa nos grupos de IA e Redes. Prazo de inscrição: 10/05.','lido'=>false],
    ['id'=>4,'tipo'=>'info',   'titulo'=>'Manutenção nos Laboratórios L3 e L4','data'=>'18/04/2025','resumo'=>'Os laboratórios L3 e L4 ficarão fora de serviço na próxima sexta-feira para manutenção preventiva.','lido'=>true],
    ['id'=>5,'tipo'=>'normal', 'titulo'=>'Novo Convênio com Empresas de Tecnologia','data'=>'10/04/2025','resumo'=>'A UNINOVA firmou convênio com 5 novas empresas para estágios e empregos. Confira no Portal de Carreiras.','lido'=>false],
];

$EVENTOS = [
    ['dia'=>'02','mes'=>'MAI','titulo'=>'Prova M1 — Algoritmos Avançados','hora'=>'19h00','cor'=>'rose','tipo'=>'prova'],
    ['dia'=>'05','mes'=>'MAI','titulo'=>'Entrega — Projeto BD II','hora'=>'23h59','cor'=>'amber','tipo'=>'entrega'],
    ['dia'=>'08','mes'=>'MAI','titulo'=>'Seminário de IA — Bloco B','hora'=>'14h00','cor'=>'sky','tipo'=>'evento'],
    ['dia'=>'12','mes'=>'MAI','titulo'=>'Prova M1 — Engenharia de Software','hora'=>'19h00','cor'=>'rose','tipo'=>'prova'],
    ['dia'=>'15','mes'=>'MAI','titulo'=>'Palestra: Mercado Tech 2025','hora'=>'10h00','cor'=>'teal','tipo'=>'evento'],
    ['dia'=>'20','mes'=>'MAI','titulo'=>'Semana Acadêmica UNINOVA','hora'=>'08h00','cor'=>'violet','tipo'=>'evento'],
];

$FINANCEIRO = [
    ['mes'=>'Abril/2025',  'valor'=>1890.00,'status'=>'pago',    'venc'=>'10/04/2025','pago_em'=>'08/04/2025'],
    ['mes'=>'Março/2025',  'valor'=>1890.00,'status'=>'pago',    'venc'=>'10/03/2025','pago_em'=>'09/03/2025'],
    ['mes'=>'Maio/2025',   'valor'=>1890.00,'status'=>'pendente','venc'=>'10/05/2025','pago_em'=>null],
    ['mes'=>'Fevereiro/25','valor'=>1890.00,'status'=>'pago',    'venc'=>'10/02/2025','pago_em'=>'07/02/2025'],
    ['mes'=>'Janeiro/25',  'valor'=>1890.00,'status'=>'pago',    'venc'=>'10/01/2025','pago_em'=>'10/01/2025'],
];

$BIBLIOTECA = [
    ['id'=>1,'titulo'=>'Clean Code','autor'=>'Robert C. Martin','devol'=>'15/05/2025','status'=>'emprestado','capa'=>'📗','isbn'=>'978-0132350884'],
    ['id'=>2,'titulo'=>'Algoritmos — Teoria e Prática','autor'=>'Cormen et al.','devol'=>'22/05/2025','status'=>'emprestado','capa'=>'📘','isbn'=>'978-8535236996'],
    ['id'=>3,'titulo'=>'Domain-Driven Design','autor'=>'Eric Evans','devol'=>'—','status'=>'reservado','capa'=>'📙','isbn'=>'978-0321125217'],
];

$ACERVO = [
    ['t'=>'Estruturas de Dados em Java','a'=>'Nivio Ziviani','disp'=>true,'cat'=>'Programação'],
    ['t'=>'Redes de Computadores','a'=>'Tanenbaum','disp'=>true,'cat'=>'Redes'],
    ['t'=>'Inteligência Artificial: Uma Abordagem Moderna','a'=>'Russell & Norvig','disp'=>false,'cat'=>'IA'],
    ['t'=>'Engenharia de Software','a'=>'Pressman & Maxim','disp'=>true,'cat'=>'Engenharia'],
    ['t'=>'Banco de Dados: Projeto e Implementação','a'=>'Heuser','disp'=>true,'cat'=>'BD'],
    ['t'=>'Padrões de Projeto','a'=>'GoF','disp'=>false,'cat'=>'Arquitetura'],
];

$SERVICOS_SECRETARIA = [
    ['ico'=>'📄','nome'=>'Declaração de Matrícula','prazo'=>'1 dia útil','cor'=>'teal','desc'=>'Documento oficial comprovando vínculo ativo com a instituição.'],
    ['ico'=>'📜','nome'=>'Histórico Escolar','prazo'=>'3 dias úteis','cor'=>'violet','desc'=>'Relatório completo das disciplinas cursadas e notas obtidas.'],
    ['ico'=>'🎓','nome'=>'Diploma / Certificado','prazo'=>'30 dias úteis','cor'=>'amber','desc'=>'Documento de conclusão de curso. Requer colação de grau.'],
    ['ico'=>'🔄','nome'=>'Transferência de Curso','prazo'=>'Análise','cor'=>'sky','desc'=>'Solicitação de mudança de curso ou campus.'],
    ['ico'=>'📑','nome'=>'Atestado de Frequência','prazo'=>'1 dia útil','cor'=>'teal','desc'=>'Atestado para comprovação de frequência às aulas.'],
    ['ico'=>'✏️','nome'=>'Revisão de Prova','prazo'=>'5 dias úteis','cor'=>'rose','desc'=>'Solicitação de revisão formal de avaliação.'],
];

$SOLICITACOES = [
    ['nome'=>'Declaração de Matrícula','data'=>'22/04/2025','status'=>'concluido','protocolo'=>'REQ-2025-0441'],
    ['nome'=>'Histórico Escolar','data'=>'15/03/2025','status'=>'concluido','protocolo'=>'REQ-2025-0312'],
    ['nome'=>'Revisão de Prova — CC403','data'=>'28/04/2025','status'=>'andamento','protocolo'=>'REQ-2025-0489'],
];

$HORARIOS = [
    '19:00'=> ['—','CC403','CC401','—','CC402','—'],
    '20:00'=> ['CC404','CC403','CC401','CC405','CC402','—'],
    '21:00'=> ['CC404','—','HU101','CC405','—','—'],
    '22:00'=> ['—','—','HU101','—','—','—'],
];

$FAQS = [
    ['q'=>'Como calcular minha média final?','a'=>'A média final é calculada pela fórmula: MF = (M1 + M2×2) ÷ 3. A segunda avaliação tem peso duplo.'],
    ['q'=>'Qual o limite de faltas por disciplina?','a'=>'O aluno pode faltar no máximo 25% das aulas. Para disciplinas de 80h, o limite é 20 faltas; para 60h, 15 faltas.'],
    ['q'=>'Como emitir uma declaração de matrícula?','a'=>'Acesse a seção Secretaria → Serviços Disponíveis e solicite a declaração. O prazo de entrega é de 1 dia útil.'],
    ['q'=>'Quando abrem as rematrículas?','a'=>'As rematrículas para o próximo semestre ocorrem entre os dias 01 e 15 do mês anterior ao início do semestre.'],
    ['q'=>'Como solicitar revisão de prova?','a'=>'Solicite através da Secretaria Virtual em até 5 dias úteis após a divulgação das notas.'],
    ['q'=>'Onde fica a biblioteca?','a'=>'Biblioteca Central — Bloco C, 1º andar. Funciona de segunda a sábado, das 07h às 23h.'],
    ['q'=>'Existe Wi-Fi para alunos?','a'=>'Sim. A rede "UNINOVA-Alunos" está disponível em todo o campus. As credenciais são as mesmas do Portal Acadêmico.'],
    ['q'=>'Como acessar o material das aulas online?','a'=>'Acesse a plataforma UNINOVA EAD pelo menu principal. Os professores disponibilizam materiais até 48h após a aula.'],
];

$SISTEMAS_STATUS = [
    ['nome'=>'Portal Acadêmico','status'=>'online','uptime'=>'99.9%'],
    ['nome'=>'Biblioteca Digital','status'=>'online','uptime'=>'98.7%'],
    ['nome'=>'E-mail Institucional','status'=>'online','uptime'=>'99.5%'],
    ['nome'=>'VPN Institucional','status'=>'manutencao','uptime'=>'95.2%'],
    ['nome'=>'Plataforma EAD','status'=>'online','uptime'=>'99.1%'],
];

$CALENDARIO_ACADEMICO = [
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

// Helpers
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
function notifCount($noticias){ return count(array_filter($noticias, fn($n)=>!$n['lido'])); }
