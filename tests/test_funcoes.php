<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/funcoes.php';

function conferir(string $nome, bool $condicao): void
{
    if (!$condicao) {
        fwrite(STDERR, "FALHOU: {$nome}\n");
        exit(1);
    }
    echo "OK: {$nome}\n";
}

conferir('2 itens de R$ 19,90', calcular_total(19.90, 2) === 39.80);
conferir('quantidade zero', calcular_total(18.00, 0) === 0.00);
conferir('arredondamento', calcular_total(1.005, 1) === 1.01);

try {
    calcular_total(-1.00, 2);
    conferir('preço negativo recusado', false);
} catch (InvalidArgumentException $e) {
    conferir('preço negativo recusado', true);
}

echo "Todos os testes passaram.\n";
