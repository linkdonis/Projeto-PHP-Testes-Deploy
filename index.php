<?php
declare(strict_types=1);
require_once __DIR__ . '/src/funcoes.php';

$total = calcular_total(19.90, 2);
?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><title>Laboratório CI/CD</title></head>
<body>
  <h1>Laboratório DevOps: PHP + GitHub Actions</h1>
  <p>Produto: R$ 19,90 | Quantidade: 2</p>
  <p>Total calculado: R$ <?= number_format($total, 2, ',', '.') ?></p>
  <p>Versão publicada após testes automatizados.</p>
</body>
</html>
