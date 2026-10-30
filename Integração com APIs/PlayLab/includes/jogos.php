<?php
// Catálogo de jogos disponíveis na plataforma.
const JOGOS = [
    'bandeiras' => ['nome' => 'Desafio das Bandeiras', 'categoria' => 'Geografia', 'dificuldade' => 'Fácil', 'icone' => '🚩',
        'api' => 'Base de países + flagcdn', 'descricao' => 'Descubra de qual país é a bandeira mostrada.'],
    'capitais' => ['nome' => 'Mestre das Capitais', 'categoria' => 'Geografia', 'dificuldade' => 'Médio', 'icone' => '🏛️',
        'api' => 'Base de países + flagcdn', 'descricao' => 'Qual é a capital do país sorteado?'],
    'duelo' => ['nome' => 'Duelo de Países', 'categoria' => 'Geografia', 'dificuldade' => 'Médio', 'icone' => '⚔️',
        'api' => 'Base de países + flagcdn', 'descricao' => 'Dois países, uma pergunta: qual tem maior população ou área?'],
    'pokemon' => ['nome' => 'Quem é esse Pokémon?', 'categoria' => 'Entretenimento', 'dificuldade' => 'Fácil', 'icone' => '⚡',
        'api' => 'PokeAPI', 'descricao' => 'Adivinhe o Pokémon pela silhueta.'],
    'clima' => ['nome' => 'Termômetro Mundial', 'categoria' => 'Ciência', 'dificuldade' => 'Difícil', 'icone' => '🌡️',
        'api' => 'Open-Meteo', 'descricao' => 'Qual das duas cidades está mais quente agora, em tempo real?'],
];
