# PlayLab — Conhecimento em jogo

Plataforma de jogos educativos em **PHP** que consome **APIs públicas** e transforma os dados JSON em quizzes interativos.
Projeto da atividade *API pública para sites PHP*.

## Como a atividade é atendida
- **Requisição HTTP + JSON:** o PHP (`includes/http.php`, cURL) faz `GET` nas APIs, decodifica o JSON e apresenta os dados.
- **Consulta com formulário:** a página **Explorar Pokémon** (`explorar.php`) tem título, formulário, campo de pesquisa, botão *Consultar* e área de resultados (PokeAPI).
- **Jogos:** cada pergunta é gerada no servidor (`includes/perguntas.php`) a partir de dados reais das APIs.

## APIs utilizadas
| API | Uso |
|---|---|
| [PokeAPI](https://pokeapi.co) | Quem é esse Pokémon? e página Explorar |
| Base local `data/paises.json` + [flagcdn](https://flagcdn.com) | Bandeiras, capitais e duelo de países (a REST Countries v3.1 foi desativada e a v5 exige chave) |
| [Open-Meteo](https://open-meteo.com) | Termômetro Mundial (temperatura atual) |

Nenhuma exige chave de API.

## Funcionalidades
Cadastro/login (senha com `password_hash`, proteção CSRF), catálogo com pesquisa, filtros e favoritos, 5 jogos de 10 perguntas, XP com bônus por sequência, níveis, conquistas, ranking, perfil com histórico, desempenho por jogo e **modo de revisão** de erros. Dados salvos em SQLite.

A resposta correta de cada pergunta fica só no servidor (sessão), então o jogador não consegue vê-la no navegador.

## Como executar
Requisitos: PHP 8.1+ com extensões `pdo_sqlite` e `curl`, e acesso à internet.

```bash
php -S localhost:8000
```
Abra http://localhost:8000. O banco (`data/playlab.sqlite`) é criado automaticamente no primeiro acesso.

## Estrutura
```
index.php  login.php  cadastro.php  logout.php  catalogo.php  jogo.php  explorar.php  perfil.php  ranking.php
api/       pergunta.php  responder.php  favorito.php
includes/  bootstrap.php (sessão, banco, XP)  http.php (cliente HTTP+cache)  perguntas.php (jogos)  jogos.php  layout.php
js/game.js   css/style.css
```

## Próximos passos
Desafio diário, mais jogos (elementos químicos, obras de arte, filmes, palavras) após validar as APIs.

## Vídeo de demonstração
(cole aqui o link do YouTube)
