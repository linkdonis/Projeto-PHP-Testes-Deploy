# Roteiro da aula — Testes automáticos antes do deploy

Duração aproximada: **3 horas**. Utilize os slides na sequência, com laboratório em repositório privado e Runner local de testes.

| Intervalo | Slides | Atividade |
|---|---|---|
| 0–40 min | 1–12 | Problema do deploy direto, CI/CD, lint, teste funcional, HTTP, arquitetura. |
| 40–75 min | 13–23 | Ler os arquivos PHP, executar teste local, montar o contêiner e observar códigos de saída. |
| 75–135 min | 24–36 | Montar o YAML: eventos, testes, Docker CI, dependências e deploy no Runner local. |
| 135–160 min | 37–39 | Demonstrar sucesso, falha de sintaxe e falha de lógica. |
| 160–180 min | 40 | Prática em duplas e perguntas. |

## Checklist antes da aula

1. Verifique que a VM Ubuntu Server está disponível, contém Docker Compose e expõe a porta 8080 apenas para a rede do laboratório.
2. Confirme que o Runner da VM está **Online**, com labels `self-hosted`, `linux`, `x64`, `aula-devops`, e que a conta do serviço consegue usar Docker.
3. Crie um repositório privado para demonstração e configure a branch `main`.
4. Publique uma versão correta para que os alunos possam observar que ela permanece no ar após um teste falhar.
5. Teste os experimentos em uma cópia do repositório antes da aula.

## Experimento A: sucesso

```bash
git add .
git commit -m 'feat: configurar testes antes do deploy'
git push origin main
```

Os jobs `testes` e `deploy` deverão ficar verdes **se houver Runner local disponível e nenhum conflito de porta/permissão**.

## Experimento B: erro de sintaxe

Remova o `;` no `return round($preco * $quantidade, 2);` de `src/funcoes.php`. O PHP deverá falhar em `php -l`, e o Job `deploy` deverá ser ignorado. Depois restaure.

## Experimento C: erro de negócio

Troque `*` por `+` no cálculo do total, preservando o `;`. O lint passa, mas o teste de lógica falha. Depois restaure.

## Cuidados

- O workflow `exemplos/ci-cd-falha.yml` está fora de `.github/workflows` por ser intencionalmente incorreto; não ativá-lo sem explicar a turma.
- Se um teste falha **antes** do deploy, a aplicação previamente publicada na VM não é alterada por aquela execução.
- Se o deploy falhar **depois de começar**, o exemplo não restaura automaticamente a versão anterior.
- Acesso ao Docker equivale a privilégios muito elevados. Não compartilhe Runner self-hosted de laboratório com projetos públicos ou código não confiável.
