/* ═══════════════════════════════════════════════════════════════
   UNINOVA — INTERACTIVE LAYER v4.0
   Cursor, particles, toasts, modals, search, animations, etc.
═══════════════════════════════════════════════════════════════ */

'use strict';

// ── CURSOR ──────────────────────────────────────────────────────
const Cursor = (() => {
  const cur  = document.getElementById('cur');
  const ring = document.getElementById('cur-ring');
  if (!cur || !ring) return {};

  let mx = 0, my = 0, rx = 0, ry = 0, animId;

  document.addEventListener('mousemove', e => {
    mx = e.clientX; my = e.clientY;
    cur.style.left = mx + 'px';
    cur.style.top  = my + 'px';
  });

  (function loop() {
    rx += (mx - rx) * .1;
    ry += (my - ry) * .1;
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    animId = requestAnimationFrame(loop);
  })();

  function bindHovers() {
    document.querySelectorAll('a, button, input, select, textarea, .nav-item, .card, .faq-item, .book-row, .news-item, .req-item, .stat-card, .sched-cell, [data-cursor]').forEach(el => {
      el.addEventListener('mouseenter', () => {
        cur.style.width  = '14px'; cur.style.height = '14px';
        ring.style.width = '46px'; ring.style.height = '46px';
        ring.style.borderColor = 'rgba(0,221,176,.65)';
      });
      el.addEventListener('mouseleave', () => {
        cur.style.width  = '8px';  cur.style.height = '8px';
        ring.style.width = '32px'; ring.style.height = '32px';
        ring.style.borderColor = 'rgba(0,221,176,.3)';
      });
    });
  }
  bindHovers();
  return { bindHovers };
})();

// ── PARTICLES ───────────────────────────────────────────────────
const Particles = (() => {
  const cv = document.getElementById('pcvs');
  if (!cv) return {};
  const cx = cv.getContext('2d');
  let W, H, pts = [], mouse = { x: -9999, y: -9999 };

  const resize = () => {
    W = cv.width  = window.innerWidth;
    H = cv.height = window.innerHeight;
  };
  resize();
  window.addEventListener('resize', resize);

  document.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY });

  for (let i = 0; i < 60; i++) pts.push({
    x: Math.random() * (typeof W !== 'undefined' ? W : 1400),
    y: Math.random() * (typeof H !== 'undefined' ? H : 900),
    vx: (Math.random() - .5) * .25,
    vy: (Math.random() - .5) * .18,
    r:  Math.random() * 1.3 + .3,
    a:  Math.random() * .35 + .08,
  });

  (function draw() {
    cx.clearRect(0, 0, W, H);
    pts.forEach(p => {
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0) p.x = W; if (p.x > W) p.x = 0;
      if (p.y < 0) p.y = H; if (p.y > H) p.y = 0;
      // Subtle mouse repulsion
      const dx = p.x - mouse.x, dy = p.y - mouse.y;
      const d = Math.hypot(dx, dy);
      if (d < 80) { p.x += dx / d * .5; p.y += dy / d * .5; }

      cx.beginPath();
      cx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      cx.fillStyle = `rgba(139,108,247,${p.a * .7})`;
      cx.fill();
    });
    for (let i = 0; i < pts.length; i++) {
      for (let j = i + 1; j < pts.length; j++) {
        const dx = pts[i].x - pts[j].x, dy = pts[i].y - pts[j].y;
        const d = Math.hypot(dx, dy);
        if (d < 130) {
          cx.beginPath();
          cx.moveTo(pts[i].x, pts[i].y);
          cx.lineTo(pts[j].x, pts[j].y);
          cx.strokeStyle = `rgba(139,108,247,${.06 * (1 - d / 130)})`;
          cx.lineWidth = .5;
          cx.stroke();
        }
      }
    }
    requestAnimationFrame(draw);
  })();
})();

// ── CLOCK ────────────────────────────────────────────────────────
const Clock = (() => {
  const el = document.getElementById('topbar-clock');
  if (!el) return {};
  const fmt2 = n => String(n).padStart(2, '0');
  const tick = () => {
    const now = new Date();
    el.textContent = `${fmt2(now.getHours())}:${fmt2(now.getMinutes())}:${fmt2(now.getSeconds())}`;
  };
  tick();
  setInterval(tick, 1000);
})();

// ── TOAST SYSTEM ─────────────────────────────────────────────────
const Toast = (() => {
  const container = document.getElementById('toast-container');
  if (!container) return { show: () => {} };

  function show(msg, type = 'info', icon = null, dur = 3500) {
    const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.innerHTML = `
      <span class="toast-icon">${icon || icons[type] || 'ℹ️'}</span>
      <span class="toast-text">${msg}</span>
      <button class="toast-close" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(t);
    if (dur > 0) setTimeout(() => {
      t.classList.add('out');
      setTimeout(() => t.remove(), 350);
    }, dur);
    return t;
  }
  return { show };
})();

// ── MODAL SYSTEM ─────────────────────────────────────────────────
const Modal = (() => {
  function open(id) {
    const m = document.getElementById(id);
    if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
  }
  function close(id) {
    const m = document.getElementById(id);
    if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
  }
  // Close on overlay click
  document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) close(m.id); });
  });
  // Close on Escape
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => close(m.id));
  });
  return { open, close };
})();

// ── FAQ ACCORDION ────────────────────────────────────────────────
document.querySelectorAll('.faq-item').forEach(item => {
  item.addEventListener('click', () => {
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
  });
});

// ── LIVE GRADE CALCULATOR ────────────────────────────────────────
const CalcLive = (() => {
  const im1 = document.getElementById('m1');
  const im2 = document.getElementById('m2');
  const lp  = document.getElementById('live-preview');
  if (!im1 || !im2 || !lp) return {};

  function calc() {
    const v1 = parseFloat(im1.value.replace(',', '.'));
    const v2 = parseFloat(im2.value.replace(',', '.'));
    if (isNaN(v1) && isNaN(v2)) {
      lp.innerHTML = '<span style="color:var(--muted)">—</span>';
      return;
    }
    const a = isNaN(v1) ? 0 : Math.min(10, Math.max(0, v1));
    const b = isNaN(v2) ? 0 : Math.min(10, Math.max(0, v2));
    if (isNaN(v1) || isNaN(v2)) {
      lp.innerHTML = '<span style="color:var(--muted)">Insira M1 e M2</span>';
      return;
    }
    const mf  = Math.round((a + b * 2) / 3 * 100) / 100;
    const cor = mf >= 7 ? 'teal' : mf >= 5 ? 'amber' : 'rose';
    const sit = mf >= 7 ? 'Aprovado' : mf >= 5 ? 'Recuperação' : 'Reprovado';
    lp.innerHTML = `<span style="color:var(--${cor})">${mf.toFixed(2).replace('.', ',')} — ${sit}</span>`;
  }
  im1.addEventListener('input', calc);
  im2.addEventListener('input', calc);
  calc();
})();

// ── GLOBAL SEARCH ────────────────────────────────────────────────
const Search = (() => {
  const input = document.getElementById('global-search');
  const wrap  = document.getElementById('search-wrap');
  if (!input) return {};

  const index = [
    { title: 'Dashboard',           sub: 'Visão geral do semestre',         icon: '🏠', href: '?s=dashboard' },
    { title: 'Notas e Boletim',     sub: 'Visualizar e calcular médias',    icon: '📊', href: '?s=notas' },
    { title: 'Grade de Horários',   sub: 'Disciplinas e salas',             icon: '🗓', href: '?s=horarios' },
    { title: 'Frequência',          sub: 'Controle de faltas',              icon: '📋', href: '?s=frequencia' },
    { title: 'Financeiro',          sub: 'Mensalidades e boletos',          icon: '💳', href: '?s=financeiro' },
    { title: 'Biblioteca',          sub: 'Empréstimos e acervo',            icon: '📚', href: '?s=biblioteca' },
    { title: 'Notícias',            sub: 'Comunicados e eventos',           icon: '📰', href: '?s=noticias' },
    { title: 'Secretaria',          sub: 'Solicitações acadêmicas',         icon: '🏛', href: '?s=secretaria' },
    { title: 'Suporte',             sub: 'FAQ e chamados',                  icon: '💬', href: '?s=suporte' },
    { title: 'Meu Perfil',          sub: 'Dados pessoais e senha',          icon: '👤', href: '?s=perfil' },
    { title: 'Calendário Acadêmico',sub: 'Datas e prazos 2025/1',           icon: '📅', href: '?s=calendario' },
  ];

  let dropdown = null;

  function render(results) {
    if (dropdown) dropdown.remove();
    if (!results.length) return;
    dropdown = document.createElement('div');
    dropdown.className = 'search-dropdown';
    results.slice(0, 6).forEach(r => {
      const item = document.createElement('a');
      item.className = 'search-result-item';
      item.href = r.href;
      item.innerHTML = `
        <span class="sr-ico">${r.icon}</span>
        <div><div class="sr-title">${r.title}</div><div class="sr-sub">${r.sub}</div></div>
      `;
      dropdown.appendChild(item);
    });
    wrap.style.position = 'relative';
    wrap.appendChild(dropdown);
    Cursor.bindHovers && Cursor.bindHovers();
  }

  input.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    if (!q) { if (dropdown) dropdown.remove(); dropdown = null; return; }
    const res = index.filter(i => i.title.toLowerCase().includes(q) || i.sub.toLowerCase().includes(q));
    render(res);
  });

  input.addEventListener('keydown', e => {
    if (e.key === 'Escape') { input.value = ''; if (dropdown) dropdown.remove(); dropdown = null; }
  });

  document.addEventListener('click', e => {
    if (!wrap.contains(e.target)) { if (dropdown) dropdown.remove(); dropdown = null; }
  });
})();

// ── COPY TO CLIPBOARD ────────────────────────────────────────────
function copyText(text, label) {
  navigator.clipboard.writeText(text).then(() => {
    Toast.show(`${label} copiado!`, 'success');
  }).catch(() => Toast.show('Erro ao copiar', 'error'));
}

// ── SOLICITAR SERVIÇO (fake) ─────────────────────────────────────
function solicitarServico(nome) {
  Toast.show(`Solicitação de "${nome}" enviada!`, 'success', '📤', 4000);
}

// ── GERAR BOLETO (fake) ───────────────────────────────────────────
function gerarBoleto(mes, valor) {
  Toast.show(`Boleto de ${mes} (R$ ${valor}) gerado!`, 'info', '💳', 4000);
  setTimeout(() => Toast.show('Código de barras copiado para a área de transferência.', 'success'), 1000);
}

// ── RENOVAR LIVRO (fake) ─────────────────────────────────────────
function renovarLivro(titulo) {
  Toast.show(`Empréstimo de "${titulo}" renovado por +14 dias.`, 'success', '📗', 4000);
}

// ── ENVIAR CHAMADO (fake) ─────────────────────────────────────────
function enviarChamado(e) {
  e.preventDefault();
  const assunto = document.querySelector('#form-chamado [name="assunto"]')?.value;
  const msg     = document.querySelector('#form-chamado [name="mensagem"]')?.value;
  if (!assunto || assunto === '') { Toast.show('Selecione uma categoria.', 'warning'); return; }
  if (!msg || msg.trim().length < 10) { Toast.show('Mensagem muito curta (mín. 10 caracteres).', 'warning'); return; }
  Toast.show('Chamado #UNI-' + Math.floor(Math.random()*9000+1000) + ' aberto! Resposta em até 48h.', 'success', '📩', 5000);
  document.querySelector('#form-chamado').reset();
}

// ── ALTERAR SENHA (fake) ──────────────────────────────────────────
function alterarSenha(e) {
  e.preventDefault();
  const nova  = document.querySelector('#form-senha [name="nova"]')?.value;
  const conf  = document.querySelector('#form-senha [name="conf"]')?.value;
  if (!nova || nova.length < 6) { Toast.show('Nova senha muito curta (mín. 6 caracteres).', 'warning'); return; }
  if (nova !== conf) { Toast.show('As senhas não coincidem.', 'error'); return; }
  Toast.show('Senha alterada com sucesso!', 'success', '🔐', 4000);
  document.querySelector('#form-senha').reset();
}

// ── MINI CALENDAR ─────────────────────────────────────────────────
const MiniCal = (() => {
  const el = document.getElementById('mini-calendar');
  if (!el) return {};

  // days with events in May 2025
  const eventDays = new Set([2, 5, 8, 12, 15, 20]);
  let curr = new Date(2025, 4, 1); // May 2025

  const MONTHS = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
  const DAYS   = ['D','S','T','Q','Q','S','S'];

  function render() {
    const year = curr.getFullYear(), month = curr.getMonth();
    const first = new Date(year, month, 1).getDay();
    const days  = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    let html = `
      <div class="mini-cal-nav">
        <button onclick="MiniCal.prev()">‹</button>
        <span class="mini-cal-month">${MONTHS[month]} ${year}</span>
        <button onclick="MiniCal.next()">›</button>
      </div>
      <div class="mini-cal">
        ${DAYS.map(d => `<div class="mini-cal-hdr">${d}</div>`).join('')}
        ${Array.from({length: first}, () => '<div class="mini-cal-day empty"></div>').join('')}
        ${Array.from({length: days}, (_, i) => {
          const d = i + 1;
          const isToday = today.getFullYear() === year && today.getMonth() === month && today.getDate() === d;
          const hasEv = eventDays.has(d);
          return `<div class="mini-cal-day ${isToday?'today':''} ${hasEv?'has-event':''}">${d}</div>`;
        }).join('')}
      </div>`;
    el.innerHTML = html;
    Cursor.bindHovers && Cursor.bindHovers();
  }

  function prev() { curr.setMonth(curr.getMonth() - 1); render(); }
  function next() { curr.setMonth(curr.getMonth() + 1); render(); }

  render();
  return { prev, next, render };
})();

// ── PROGRESS BARS ANIMATION ───────────────────────────────────────
(() => {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const fill = entry.target;
        fill.style.width = fill.dataset.width || fill.style.width;
        observer.unobserve(fill);
      }
    });
  }, { threshold: .2 });
  document.querySelectorAll('.pbar-fill').forEach(el => {
    el.dataset.width = el.style.width;
    el.style.width = '0';
    observer.observe(el);
  });
})();

// ── DEMO ACCOUNT QUICK FILL ───────────────────────────────────────
function fillDemo(user, pass) {
  const u = document.getElementById('login-user');
  const p = document.getElementById('login-pass');
  if (u) u.value = user;
  if (p) p.value = pass;
  Toast.show('Conta demo preenchida. Clique em Entrar.', 'info', '🔑', 2500);
}

// ── KEYBOARD SHORTCUTS ────────────────────────────────────────────
document.addEventListener('keydown', e => {
  // Ctrl+K → focus search
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    const s = document.getElementById('global-search');
    if (s) { s.focus(); s.select(); }
  }
});

// ── CHART: Notas por disciplina (Canvas bar chart) ────────────────
function drawNotasChart(canvasId, data) {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const W = canvas.width, H = canvas.height;
  const pad = { t: 20, r: 10, b: 40, l: 30 };
  const chartW = W - pad.l - pad.r;
  const chartH = H - pad.t - pad.b;
  const barW = chartW / data.length * .55;
  const gap  = chartW / data.length;

  ctx.clearRect(0, 0, W, H);

  // Grid lines
  for (let i = 0; i <= 5; i++) {
    const y = pad.t + chartH - (chartH / 5 * i);
    ctx.beginPath();
    ctx.moveTo(pad.l, y); ctx.lineTo(pad.l + chartW, y);
    ctx.strokeStyle = 'rgba(255,255,255,.05)'; ctx.lineWidth = 1; ctx.stroke();
    ctx.fillStyle = 'rgba(160,160,192,.5)';
    ctx.font = '10px JetBrains Mono, monospace';
    ctx.textAlign = 'right';
    ctx.fillText((i * 2).toString(), pad.l - 4, y + 4);
  }

  // Bars
  data.forEach((d, i) => {
    const x = pad.l + i * gap + gap / 2 - barW / 2;
    const h = d.val / 10 * chartH;
    const y = pad.t + chartH - h;

    const colors = {
      teal: '#00ddb0', violet: '#8b6cf7', amber: '#f5a623',
      rose: '#f5617a', sky: '#38c8f5', muted: '#50507a'
    };
    const col = d.val >= 7 ? 'teal' : d.val >= 5 ? 'amber' : 'rose';

    // Bar
    const grad = ctx.createLinearGradient(0, y, 0, y + h);
    grad.addColorStop(0, colors[col] + 'dd');
    grad.addColorStop(1, colors[col] + '44');
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.roundRect(x, y, barW, h, [4, 4, 0, 0]);
    ctx.fill();

    // Value
    if (d.val > 0) {
      ctx.fillStyle = colors[col];
      ctx.font = 'bold 11px Outfit, sans-serif';
      ctx.textAlign = 'center';
      ctx.fillText(d.val.toFixed(1).replace('.', ','), x + barW / 2, y - 5);
    }

    // Label
    ctx.fillStyle = 'rgba(160,160,192,.7)';
    ctx.font = '9px JetBrains Mono, monospace';
    ctx.textAlign = 'center';
    ctx.fillText(d.label, x + barW / 2, pad.t + chartH + 14);
  });
}

// Expose globals
window.Toast    = Toast;
window.Modal    = Modal;
window.MiniCal  = MiniCal;
window.copyText = copyText;
window.solicitarServico = solicitarServico;
window.gerarBoleto = gerarBoleto;
window.renovarLivro = renovarLivro;
window.enviarChamado = enviarChamado;
window.alterarSenha = alterarSenha;
window.fillDemo = fillDemo;
window.drawNotasChart = drawNotasChart;
