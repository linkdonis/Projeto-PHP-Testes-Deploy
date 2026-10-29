# Laboratório CI/CD — PHP + Docker Compose + GitHub Actions

Projeto didático para demonstrar **testes automáticos antes do deploy**. O teste de sintaxe, os testes de lógica e a verificação HTTP rodam em `ubuntu-latest`; o deploy só executa em um Runner local no Ubuntu Server quando o job `testes` termina com sucesso.

## Estrutura

- `src/funcoes.php`: regra de negócio (`calcular_total`).
- `tests/test_funcoes.php`: testes automatizados em PHP puro (sem Composer).
- `index.php`: página demonstrativa.
- `health.php`: endpoint para teste HTTP.
- `Dockerfile` e `compose.yaml`: contêiner Apache/PHP.
- `.github/workflows/ci-cd.yml`: workflow **funcional**.
- `exemplos/ci-cd-falha.yml`: **exemplo intencionalmente quebrado**, não ativo.
- `exemplos/ERRO_LOGICO.txt` e `ERRO_SINTAXE.txt`: orientações para reproduzir falhas.

## Antes de começar

1. Crie um repositório **privado e controlado**, com branch `main`.
2. Configure um Runner Ubuntu Server `self-hosted` com as labels `linux`, `x64` e `aula-devops`.
3. Instale Docker Engine + plugin Compose no Runner e confira `docker compose version`.
4. Certifique-se de que o usuário do serviço Runner consegue executar `docker ps` **sem sudo**. **Acesso ao grupo Docker concede privilégios elevados**: use VM de laboratório dedicada.
5. Garanta que a porta 8080 do Ubuntu está disponível e acessível apenas na rede necessária.
6. Envie este projeto para o GitHub. Em **Actions**, acompanhe os jobs `testes` e `deploy`.

## Executar testes localmente

Com PHP 8.3 instalado:

```bash
php -l src/funcoes.php
php tests/test_funcoes.php
```

Sem PHP instalado, mas com Docker:

```bash
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php -l src/funcoes.php
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php tests/test_funcoes.php
```

## Testar o Compose manualmente

```bash
docker compose -p aula-php up -d --build
curl -fsS http://127.0.0.1:8080/health.php
docker compose -p aula-php ps
```

Abra `http://IP-DO-UBUNTU:8080` para visualizar a página. Se o Runner usar a mesma VM da instalação manual, a aplicação será atualizada pelo workflow.

## Experimentos guiados

**1. Sucesso:** envie o código correto. O job `testes` deve ficar verde e o `deploy` deve executar na VM se o evento estiver associado à `main`.

**2. Erro de sintaxe:** siga `exemplos/ERRO_SINTAXE.txt` e envie um novo commit. O lint falha; `deploy` fica ignorado. Depois corrija.

**3. Erro de lógica:** siga `exemplos/ERRO_LOGICO.txt`. A sintaxe continua válida, mas os testes de negócio falham; `deploy` não executa. Depois corrija.

**4. Workflow intencionalmente quebrado:** compare o arquivo em `exemplos/ci-cd-falha.yml`. Ele invoca um teste inexistente. Para experimentar, altere **temporariamente** o comando correspondente no workflow real; não copie o arquivo de exemplo para `.github/workflows/` em paralelo, pois seria outra automação ativa.

## Como o bloqueio funciona

O job `deploy` usa `needs: testes`: por padrão, se `testes` falhar, o deploy é **ignorado**. Para PRs, `github.ref` não corresponde a `refs/heads/main`, então o job `deploy` também não executa. Execuções manuais pela `main` podem publicar após passar pelos testes.

## Limites do exercício

O pipeline **previne o deploy para erros que os testes detectarem**, mas não garante ausência de bugs. Se a publicação falhar após iniciar a nova versão, não há rollback automático neste laboratório. Não envie código não confiável a Runners self-hosted com Docker.

## Referências

- https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax
- https://docs.github.com/en/actions/reference/security/secure-use
- https://docs.docker.com/reference/cli/docker/compose/run/
