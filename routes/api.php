<?php

// Inclui a rota de autênticação JWT
include __DIR__ . '/api/v1/auth.php';

// Inclui rotas padrões da API
include __DIR__ . '/api/v1/default.php';

// Inclui rotas de depoimentos
include __DIR__ . '/api/v1/testimonies.php';

// Inclui rotas de usuários
include __DIR__ . '/api/v1/users.php';
