<div align="center">

# 🎮 PlayLab

### Conhecimento em jogo

**Plataforma de jogos educativos em PHP que transforma dados de APIs públicas em quizzes interativos.**

🐘 **PHP** &nbsp;·&nbsp; 🗄️ **SQLite** &nbsp;·&nbsp; ⚡ **JavaScript** &nbsp;·&nbsp; 🔓 **APIs sem chave**

*Projeto da atividade **API pública para sites PHP***

[🎥 Vídeo de demonstração](#-vídeo-de-demonstração) · [🚀 Como executar](#-como-executar) · [🧩 APIs utilizadas](#-apis-utilizadas)

</div>

---

## 📖 Sobre o projeto

O **PlayLab** é um site em PHP onde o usuário cria uma conta, escolhe um jogo no catálogo e responde a perguntas geradas **na hora** a partir de dados reais de APIs públicas. Cada acerto rende **XP**, que faz o jogador subir de nível, desbloquear conquistas e disputar o ranking.

O objetivo da atividade é praticar o consumo de APIs em PHP: fazer a **requisição HTTP**, **decodificar o JSON** e **apresentar os dados** de forma útil e bonita.

---

## ✅ Como a atividade é atendida

| Requisito | Onde está no projeto |
|---|---|
| **Requisição HTTP a uma API pública** | `includes/http.php` faz `GET` com cURL, com cache e tratamento de erros |
| **Receber e decodificar JSON** | As respostas são convertidas com `json_decode` e usadas nas páginas e nos jogos |
| **Página de consulta com formulário** | `explorar.php` (**Explorar Pokémon**): título, formulário, campo de pesquisa, botão *Consultar* e área de resultados |
| **Apresentar os dados ao usuário** | Resultados da PokeAPI na página Explorar e perguntas dos jogos montadas a partir das APIs |
| **Uso criativo da API** | Cinco jogos de 10 perguntas, com XP, níveis, conquistas e ranking |

---

## 🧩 APIs utilizadas

| API | Para que serve | Chave? |
|---|---|:---:|
| [**PokeAPI**](https://pokeapi.co) | Jogo *Quem é esse Pokémon?* e a página *Explorar Pokémon* | ❌ |
| [**Open-Meteo**](https://open-meteo.com) | Jogo *Termômetro Mundial* (temperatura atual das cidades) | ❌ |
| **Base local** `data/paises.json` + [**flagcdn**](https://flagcdn.com) | Jogos *Bandeiras*, *Capitais* e *Duelo de Países* | ❌ |

> 💡 **Por que uma base local de países?** A REST Countries v3.1 foi desativada e a v5 exige chave de API. Para o projeto continuar funcionando sem cadastro, os dados dos países ficam em `data/paises.json` e só as imagens das bandeiras vêm da flagcdn.

---

## 🕹️ Jogos

| Jogo | Categoria | Dificuldade | Como funciona |
|---|---|:---:|---|
| 🚩 **Desafio das Bandeiras** | Geografia | Fácil | Descubra de qual país é a bandeira mostrada |
| 🏛️ **Mestre das Capitais** | Geografia | Médio | Qual é a capital do país sorteado? |
| ⚔️ **Duelo de Países** | Geografia | Médio | Dois países, uma pergunta: qual tem maior população ou área? |
| ⚡ **Quem é esse Pokémon?** | Entretenimento | Fácil | Adivinhe o Pokémon pela silhueta |
| 🌡️ **Termômetro Mundial** | Ciência | Difícil | Qual das duas cidades está mais quente agora, em tempo real? |

Cada partida tem **10 perguntas** de múltipla escolha.

---

## ✨ Funcionalidades

- 🔐 **Cadastro e login** com senha protegida por `password_hash` e proteção **CSRF**
- 📚 **Catálogo** com pesquisa, filtro por categoria e dificuldade, e **favoritos** (⭐)
- 🎯 **5 jogos** com perguntas geradas no servidor a partir das APIs
- 🔥 **XP com bônus por sequência de acertos**
- 🏅 **Níveis e conquistas**
- 🏆 **Ranking** entre os jogadores
- 👤 **Perfil** com histórico, desempenho por jogo e **modo de revisão de erros**
- 🔎 **Explorar Pokémon**: consulta livre à PokeAPI por formulário

### 🧮 Como funciona a pontuação

| Regra | Valor |
|---|---|
| Acerto | **10 XP** |
| Bônus por sequência | **+2 XP** por acerto seguido, até **+10 XP** |
| Erro | 0 XP e a sequência volta a zero |
| Subir de nível | A cada **100 XP** |

### 🏅 Conquistas

| | Conquista | Como desbloquear |
|:-:|---|---|
| 🌱 | Primeiros passos | Responda a sua primeira pergunta |
| 🎯 | Mira afiada | Acumule 10 acertos |
| 🔥 | Em chamas | Acumule 50 acertos |
| 🏆 | Lenda | Acumule 100 acertos |
| 🧭 | Explorador | Jogue todos os jogos do catálogo |
| ⭐ | Nível 5 | Alcance o nível 5 |

---

## 🔒 Segurança

- A **resposta correta nunca vai para o navegador**: ela fica guardada só na sessão do servidor, então não dá para "colar" olhando o código da página.
- Senhas guardadas com `password_hash` (nunca em texto puro).
- Rotas de resposta protegidas por **token CSRF** e por sessão.
- Consultas ao banco com *prepared statements* (PDO) e saída escapada contra XSS.

---

## 🚀 Como executar

### Requisitos

- **PHP 8.1 ou superior**
- Extensões `pdo_sqlite` e `curl` ativadas
- Acesso à internet (para as APIs)

### Passo a passo

```bash
# 1. Clone o repositório e entre na pasta do projeto
git clone https://github.com/queirozmariana/PW2.git
cd "PW2/Integração com APIs/PlayLab"

# 2. Inicie o servidor embutido do PHP
php -S localhost:8000
```

Abra **http://localhost:8000** no navegador, crie uma conta e jogue. 🎉

> O banco de dados (`data/playlab.sqlite`) é criado **automaticamente** no primeiro acesso. Não precisa instalar nem configurar nada.

### 🩹 Problemas comuns

| Sintoma | Solução |
|---|---|
| Os jogos de Pokémon ou Clima mostram erro de conexão | Verifique se `extension=curl` e `extension=openssl` estão ativas no `php.ini` (sem `;` na frente) e se há internet |
| `could not find driver` | Ative `extension=pdo_sqlite` e `extension=sqlite3` no `php.ini` |
| A tela não muda depois de atualizar o código | Reinicie o `php -S` e use **Ctrl + F5** |

---

## 🗂️ Estrutura do projeto

```
PlayLab/
├── index.php  login.php  cadastro.php  logout.php   → entrada e autenticação
├── catalogo.php  jogo.php  explorar.php             → catálogo, jogo e consulta à PokeAPI
├── perfil.php  ranking.php                          → perfil do jogador e ranking
├── api/
│   ├── pergunta.php     → gera uma pergunta nova (JSON)
│   ├── responder.php    → confere a resposta, calcula XP e salva
│   └── favorito.php     → favorita/desfavorita um jogo
├── includes/
│   ├── bootstrap.php    → sessão, banco SQLite, XP, níveis e conquistas
│   ├── http.php         → cliente HTTP (cURL) com cache
│   ├── perguntas.php    → lógica de cada jogo
│   ├── jogos.php        → catálogo de jogos
│   └── layout.php       → cabeçalho e rodapé
├── js/game.js           → interface do jogo no navegador
├── css/style.css        → visual do site
└── data/
    └── paises.json      → base local de países
```

---

## 🔄 Como uma pergunta é gerada

```
Navegador ──► api/pergunta.php ──► includes/perguntas.php ──► includes/http.php ──► API pública
    ▲                                                                                   │
    └────────────── JSON com a pergunta (sem a resposta certa) ◄────────────────────────┘
```

1. O navegador pede uma pergunta ao servidor.
2. O PHP busca os dados na API (ou no cache) e monta a pergunta e as opções.
3. A resposta correta fica na **sessão**; o navegador recebe só as opções.
4. Ao clicar, `api/responder.php` confere, calcula o XP e grava no banco.

---

## 🛠️ Tecnologias

**PHP** · **SQLite (PDO)** · **cURL** · **JavaScript** puro · **HTML5** · **CSS3**

---

## 🔮 Próximos passos

- 📅 Desafio diário
- 🧪 Novos jogos: elementos químicos, obras de arte, filmes e palavras (após validar as APIs)

---

## 🎥 Vídeo de demonstração

> 👉 **[Assistir à demonstração](https://youtu.be/GbykHN8tuAs)**

---

<div align="center">

Feito com 💛 por **Mariana**

*PlayLab · projeto de integração com APIs públicas (PokeAPI, Open-Meteo e base de países) · PHP*

</div>
