(() => {
  const TOTAL = 10;
  const root = document.getElementById('game');
  const jogo = root.dataset.jogo;
  const csrf = document.querySelector('meta[name=csrf]').content;
  let n = 0, pontos = 0, acertos = 0, sequencia = 0;

  const el = (tag, attrs = {}, ...filhos) => {
    const e = document.createElement(tag);
    Object.entries(attrs).forEach(([k, v]) => k === 'class' ? e.className = v : e.setAttribute(k, v));
    filhos.forEach(f => e.append(f));
    return e;
  };

  function erro(msg, retry) {
    root.replaceChildren(el('p', {class: 'alerta'}, msg));
    if (retry) {
      const b = el('button', {class: 'btn', type: 'button'}, 'Tentar novamente');
      b.onclick = retry; root.append(b);
    }
  }

  async function proxima() {
    root.replaceChildren(el('p', {}, 'Carregando pergunta…'));
    let r, d;
    try {
      r = await fetch('api/pergunta.php?jogo=' + encodeURIComponent(jogo));
    } catch (e) { return erro('Falha de conexão com o servidor.', proxima); }
    const texto = await r.text();
    try { d = JSON.parse(texto); }
    catch (e) { return erro(`Resposta inesperada do servidor (HTTP ${r.status}): ` + texto.replace(/<[^>]*>/g, ' ').trim().slice(0, 200), proxima); }
    if (r.status === 401) { location.href = 'login.php'; return; }
    if (!r.ok) return erro(d.erro || 'Erro ao carregar a pergunta.', proxima);
    n++;
    desenhar(d);
  }

  function desenhar(q) {
    const topo = el('div', {class: 'placar'},
      el('span', {}, `Pergunta ${n}/${TOTAL}`), el('span', {}, `${pontos} XP`), el('span', {}, `🔥 ${sequencia}`));
    const caixa = el('div', {class: 'pergunta'});
    let img = null;
    if (q.imagem) {
      img = el('img', {src: q.imagem, alt: 'Imagem da pergunta', class: q.estilo === 'silhueta' ? 'silhueta' : 'bandeira'});
      caixa.append(q.estilo === 'silhueta' ? el('div', {class: 'moldura'}, img) : img);
    }
    caixa.append(el('h2', {}, q.pergunta));
    const opcoes = el('div', {class: 'opcoes'});
    const botoes = q.opcoes.map((txt, i) => {
      const b = el('button', {class: 'opcao', type: 'button'}, txt);
      b.onclick = () => responder(i, botoes, img, feedback);
      opcoes.append(b); return b;
    });
    const feedback = el('div', {class: 'feedback'});
    root.replaceChildren(topo, caixa, opcoes, feedback);
  }

  async function responder(i, botoes, img, feedback) {
    botoes.forEach(b => b.disabled = true);
    let r, d;
    try {
      r = await fetch('api/responder.php', {method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF': csrf}, body: JSON.stringify({indice: i})});
      d = await r.json();
    } catch (e) { botoes.forEach(b => b.disabled = false); feedback.replaceChildren(el('p', {class: 'alerta'}, 'Falha de conexão. Tente de novo.')); return; }
    if (!r.ok) { feedback.replaceChildren(el('p', {class: 'alerta'}, d.erro || 'Erro.')); return; }
    botoes[d.indiceCorreto].classList.add('certa');
    if (!d.acertou) botoes[i].classList.add('errada');
    if (img) img.classList.add('revelada');
    pontos += d.pontos; sequencia = d.sequencia; if (d.acertou) acertos++;
    const prox = el('button', {class: 'btn', type: 'button'}, n >= TOTAL ? 'Ver resultado' : 'Próxima pergunta');
    prox.onclick = n >= TOTAL ? fim : proxima;
    feedback.replaceChildren(
      el('p', {class: d.acertou ? 'ok' : 'ruim'}, d.acertou ? `Acertou! +${d.pontos} XP` : 'Não foi dessa vez.'),
      el('p', {class: 'info'}, d.info), prox);
  }

  function fim() {
    const a = el('button', {class: 'btn', type: 'button'}, 'Jogar de novo');
    a.onclick = () => { n = 0; pontos = 0; acertos = 0; sequencia = 0; proxima(); };
    root.replaceChildren(
      el('div', {class: 'resultado'}, el('h2', {}, 'Partida concluída!'),
        el('p', {}, `Você acertou ${acertos} de ${TOTAL} e ganhou ${pontos} XP.`),
        a, el('a', {class: 'btn claro', href: 'catalogo.php'}, 'Voltar ao catálogo'),
        el('a', {href: 'perfil.php'}, 'Ver meu perfil')));
  }

  proxima();
})();
