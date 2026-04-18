# Fluxo de Trabalho e Princípios - Claude Code

## Orquestração de Fluxo de Trabalho

### 1. Plano como Padrão (Plan Node Default)
* Entre no modo de planejamento para **QUALQUER** tarefa não trivial (mais de 3 etapas ou decisões arquiteturais).
* Se algo der errado, **PARE** e planeje novamente imediatamente — não continue forçando.
* Use o modo de planejamento para etapas de verificação, não apenas para construção.
* Escreva especificações detalhadas antecipadamente para reduzir ambiguidades.

### 2. Estratégia de Subagentes
* Use subagentes liberalmente para manter a janela de contexto principal limpa.
* Delegue pesquisa, exploração e análise paralela aos subagentes.
* Para problemas complexos, utilize mais processamento através de subagentes.
* Uma tarefa por subagente para garantir uma execução focada.

### 3. Ciclo de Autoaperfeiçoamento (Self-Improvement Loop)
* Após **QUALQUER** correção do usuário: atualize o arquivo `tasks/lessons.md` com o padrão.
* Escreva regras para si mesmo que evitem o mesmo erro.
* Itere implacavelmente sobre essas lições até que a taxa de erro caia.
* Revise as lições no início da sessão para o projeto relevante.

### 4. Verificação Antes de Concluir
* Nunca marque uma tarefa como concluída sem provar que ela funciona.
* Compare o comportamento entre o estado principal e suas mudanças quando relevante.
* Pergunte a si mesmo: "Um engenheiro sênior aprovaria isso?"
* Execute testes, verifique logs e demonstre a correção.

### 5. Exija Elegância (Equilibrado)
* Para mudanças não triviais: pause e pergunte "existe uma maneira mais elegante?".
* Se uma correção parecer improvisada (hacky): "Sabendo tudo o que sei agora, implemente a solução elegante".
* Pule isso para correções simples e óbvias — não exagere na engenharia.
* Desafie seu próprio trabalho antes de apresentá-lo.

### 6. Correção Automática de Bugs
* Ao receber um relatório de bug: apenas conserte. Não peça ajuda constante.
* Analise logs, erros e testes falhos — então resolva-os.
* Zero necessidade de troca de contexto por parte do usuário.
* Corrija testes de CI (Integração Contínua) falhos sem precisar ser instruído.

---

## Gestão de Tarefas

1. **Planeje Primeiro**: Escreva o plano em `tasks/todo.md` com itens marcáveis.
2. **Verifique o Plano**: Valide antes de iniciar a implementação.
3. **Acompanhe o Progresso**: Marque os itens como concluídos conforme avança.
4. **Explique as Mudanças**: Forneça um resumo de alto nível a cada etapa.
5. **Documente os Resultados**: Adicione uma seção de revisão em `tasks/todo.md`.
6. **Capture Lições**: Atualize `tasks/lessons.md` após correções.

---

## Princípios Centrais

* **Simplicidade Primeiro**: Torne cada mudança o mais simples possível. Impacte o mínimo de código.
* **Sem Preguiça**: Encontre as causas raízes. Sem correções temporárias. Padrões de desenvolvedor sênior.
* **Impacto Mínimo**: As mudanças devem tocar apenas o necessário. Evite introduzir novos bugs.

---

## Erros Registrados

### DB::table(): não adicionar cláusulas de soft delete em tabelas sem `deleted_at`
**Erro**: `SQLSTATE[42703]: Undefined column: coluna inscricoes.deleted_at não existe`
**Causa**: Ao usar `DB::table('inscricoes')->whereNull('inscricoes.deleted_at')` em uma tabela que não usa `SoftDeletes`, o PostgreSQL rejeita a query porque a coluna não existe. Isso não ocorre com Eloquent (que só adiciona a cláusula quando o trait `SoftDeletes` está presente no Model).
**Regra**: Antes de usar `whereNull('tabela.deleted_at')` em um `DB::table()`, verificar se o Model correspondente usa `SoftDeletes`. Se não usar, remover a cláusula. Em caso de dúvida, usar Eloquent (`Model::query()`) que gerencia isso automaticamente.

### Blade: `@json([...])` multiline dentro de `<script>` causa ParseError
**Erro**: `ParseError: Unclosed '[' on line X does not match ')'`
**Causa**: O Blade/PHP parser se confunde ao encontrar `@json([...])` com array PHP multiline dentro de uma tag `<script>`, pois os colchetes `[` do PHP e do JavaScript subsequente entram em conflito com o parser.
**Regra**: Nunca usar `@json([...])` com arrays multiline dentro de `<script>`. Em vez disso, preparar o JSON num bloco `@php` antes da tag script e emitir com `{!! $var !!}`:
```blade
@php
    $dadosJson = json_encode([...], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
@endphp
<script>
const DADOS = {!! $dadosJson !!};
</script>
```

### Tailwind v4: classes CSS custom NÃO funcionam em `app.css` (nem em `@layer components`)
**Regra definitiva**: Neste projeto com Tailwind v4 + Vite, **nenhuma classe CSS custom** (`.btn`, `.dash-stats`, etc.) funciona quando declarada no `app.css`, mesmo dentro de `@layer components`. O Tailwind v4 processa/purga o arquivo de forma que classes sem uso detectado de utilitários são descartadas.
**Solução única que funciona**: colocar o CSS em um `<style>` block diretamente no layout ou na view:
- Classes globais (botões) → `<style>` no `<head>` do `layouts/app.blade.php`
- Classes de página específica → `@push('scripts')` com `<style>` na própria view
**Exceção**: `.ce-layout`, `.ce-sidebar` etc. funcionam no `app.css` pois são detectadas via `@source` scanning das views Blade.

### Tailwind v4: classes CSS custom de grid NÃO funcionam em `app.css` nem em `@layer components`
**Erro**: Classes como `.dash-stats { display:grid; grid-template-columns:1fr 1fr }` não são aplicadas mesmo dentro de `@layer components` no `app.css` do Tailwind v4.
**Causa**: O Tailwind v4 compila e purga o CSS de forma diferente — classes de grid custom parecem não sobreviver ao processamento do Vite+Tailwind neste projeto, mesmo em `@layer components`. O `@media` aninhado dentro de `@layer` também pode não funcionar corretamente.
**Regra**: Para grids responsivos de layout de página (não reutilizáveis), usar um `<style>` block inline diretamente na view (via `@push('scripts')` ou `@section`). CSS em `<style>` nunca é processado pelo Tailwind e sempre funciona:
```blade
@push('scripts')
<style>
.minha-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media (min-width:1024px) { .minha-grid { grid-template-columns:repeat(4,1fr); } }
</style>
@endpush
```
**Exceção confirmada que funciona em `app.css`**: flex layouts simples (`.ce-layout`, `.ce-sidebar`) e `@layer components` para botões com `background`/`color`.

### Migrations de seed com dependência de dados: consolidar em uma única migration autocontida
**Erro**: Migration `000001` criava a etapa; `000004` buscava a etapa por nome e falhava em `migrate:fresh` com `RuntimeException` porque buscava por nome com caracteres especiais (ª, ã) que podem não bater, ou porque a migration anterior não havia rodado no contexto esperado.
**Causa**: Dividir em migrations separadas cria acoplamento frágil — a migration dependente assume que a anterior rodou com sucesso e gerou exatamente os dados esperados.
**Regra**: Migration de seed que depende de outra deve ser **autocontida**: criar ela mesma os dados pré-requisito se não existirem (padrão `firstOrCreate`). Buscar pelo campo mais estável e sem caracteres especiais (ex: `data` + `local` em vez de `nome` com acentos). A migration independente anterior pode ser esvaziada (no-op) para não duplicar.
**Bônus**: Sempre tornar o seed idempotente com `if (DB::table(...)->exists()) return;` para evitar duplicatas em re-runs.

### Blade: `@if` sem `@endif` correspondente
**Erro**: `syntax error, unexpected end of file, expecting "elseif" or "else" or "endif"`
**Causa**: Ao reposicionar blocos de menu via Edit parcial, um `@if` ficou sem seu `@endif` — o bloco de `Etapa` estava dentro de um `@if(isAdmin())` que nunca foi fechado, causando um segundo `@if` aninhado sem par.
**Regra**: Antes de qualquer edição em arquivo Blade com `@if/@endif`, contar os pares existentes na região afetada. Após a edição, verificar visualmente que cada `@if` tem exatamente um `@endif` correspondente no mesmo escopo.