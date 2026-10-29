<?php
declare(strict_types=1);

function calcular_total(float $preco, int $quantidade): float
{
    if ($preco < 0 || $quantidade < 0) {
        throw new InvalidArgumentException('Preço e quantidade devem ser positivos.');
    }

    return round($preco * $quantidade, 2);
}
