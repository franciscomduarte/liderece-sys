# CLAUDE.md — Instruções para o Claude Code

## Projeto
SGC — Sistema de Gestão de Competências
Órgão Público Federal | Laravel 11 + PostgreSQL + Livewire 3 + Tailwind CSS 3

---

## Stack e versões
- PHP 8.3
- Laravel 11
- Livewire 3
- Tailwind CSS 3
- PostgreSQL 16
- Pest PHP (testes)
- Alpine.js (já incluso no Livewire 3)
- Chart.js 4 (CDN no layout)
- maatwebsite/excel (exportar CSV/XLSX)
- barryvdh/laravel-dompdf (exportar PDF)
- livewire/sortable (drag-to-reorder)

---

## Comandos essenciais
```bash
php artisan migrate                                    # Rodar migrations
php artisan migrate:fresh                              # Resetar banco (dev only)
php artisan db:seed --class=DevelopmentSeeder          # Seed (dev only)
php artisan sgc:setup                                  # Criar admin inicial (produção)
php artisan test                                       # Rodar todos os testes
php artisan test --coverage --min=80                   # Com cobertura mínima
php artisan queue:work                                 # Processar jobs de notificação
npm run dev                                            # Vite em modo desenvolvimento
npm run build                                          # Build de produção
```

---

## Estrutura de perfis
- `admin`    → `/admin/*`    → acesso total ao sistema
- `gestor`   → `/gestor/*`   → apenas servidores e dados de sua `area_id`
- `servidor` → `/servidor/*` → apenas seus próprios dados

Middleware de perfil: `'perfil:admin'`, `'perfil:gestor'`, `'perfil:servidor'`
Qualquer acesso indevido → redirect `/unauthorized`

---

## Regras de negócio — NUNCA violar

1. **Avaliação enviada é IMUTÁVEL.**
   Após `status='enviada'`, nenhum campo pode ser alterado. Nem o admin pode editar.

2. **Apenas 1 ciclo com `status='ativo'` por vez.**
   Constraint `UNIQUE INDEX` no banco + validação no `CicloService`.

3. **Gestor acessa APENAS dados de sua `area_id`.**
   Validar via Policy em TODA operação, não apenas na listagem.

4. **Contestação tem prazo fixo.**
   `prazo = avaliacao.enviada_at + ciclo.prazo_contestacao_dias`
   Após o prazo, botão "Contestar" desaparece e status vira `'encerrada'`.

5. **Média = média aritmética simples.**
   Somar todas as notas dos itens / quantidade de itens. Arredondar para 1 casa decimal.

6. **Servidor só pode contestar UMA VEZ por avaliação.**
   Unique constraint em `contestacoes.avaliacao_id`.

7. **Sistema nasce VAZIO em produção.**
   Nenhum dado padrão além do usuário admin.
   Seed roda APENAS com `APP_ENV=local` ou `development`.

8. **Primeiro acesso redireciona para troca de senha OBRIGATÓRIA.**
   Nenhuma outra rota é acessível até a senha ser trocada.

---

## Design system — Sovereign Architect

### Cores (configurar em tailwind.config.js)
```
primary:                  #0058be
primary-container:        #2170e4
primary-fixed:            #d8e2ff
on-primary-fixed:         #001a42
on-primary-fixed-variant: #004395
tertiary:                 #006947
tertiary-container:       #00855b
tertiary-fixed:           #6ffbbe
on-tertiary-fixed:        #002113
on-tertiary-fixed-variant:#005236
secondary:                #595e6f
secondary-fixed:          #dee2f7
on-secondary-fixed:       #161b2a
on-secondary-fixed-variant:#414657
background:               #f6fafe
surface:                  #f6fafe
surface-container-lowest: #ffffff
surface-container-low:    #f0f4f8
surface-container:        #eaeef2
surface-container-high:   #e4e9ed
surface-container-highest:#dfe3e7
on-surface:               #171c1f
on-surface-variant:       #424754
outline:                  #727785
outline-variant:          #c2c6d6
error:                    #ba1a1a
error-container:          #ffdad6
on-error-container:       #93000a
```

### Regras de estilo obrigatórias
- Sidebar: `bg-[#1a1f2e]` (NUNCA alterar esta cor)
- Background geral: `bg-[#f6fafe]`
- Cards/superfícies: `bg-white` (surface-container-lowest)
- Separação de seções: diferença de cor de fundo (NUNCA borda 1px)
- Border radius padrão: `rounded-xl` (0.75rem)
- Sombra padrão: `shadow-[0_12px_40px_rgba(23,28,31,0.06)]`
- Fonte headlines: `font-['Manrope'] font-bold` ou `font-extrabold`
- Fonte corpo: `font-['Inter']`
- Ícones: Material Symbols Outlined SEMPRE
  ```html
  <span class="material-symbols-outlined">dashboard</span>
  ```

### Badges de tipo de competência
```
Comportamental: bg-[#d8e2ff] text-[#004395]
Técnica:        bg-[#6ffbbe] text-[#002113]
Gerencial:      bg-[#dee2f7] text-[#414657]
```

### Status de ciclo no banner
```
Ativo:   bg-[#00855b]/10 border border-[#006947]/20 text-[#006947]
Inativo: bg-amber-50 border border-amber-200 text-amber-700
```

### Botão primário padrão
```html
class="bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white
       px-6 py-3 rounded-xl font-bold shadow-lg shadow-[#0058be]/20
       hover:scale-[1.02] active:scale-[0.98] transition-all"
```

### Item de sidebar ativo
```html
class="bg-blue-600/10 text-blue-400 border-r-4 border-blue-500 rounded-l-xl"
```

### Item de sidebar inativo
```html
class="text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-all"
```

### Tabela padrão
- Header: `bg-[#f0f4f8] text-xs font-bold uppercase tracking-widest`
- Linhas: `hover:bg-[#e4e9ed] transition-colors`
- Sem bordas entre linhas — usar `divide-y divide-[#eaeef2]`

---

## Fidelidade visual — REGRA ABSOLUTA

As telas HTML do protótipo são a fonte de verdade visual.
Para CADA tela implementada:

1. Comparar lado a lado com o HTML de referência
2. Verificar: cores, espaçamentos, tipografia, ícones, badges
3. Verificar: estados hover, focus, disabled, empty, loading
4. Verificar: responsividade mobile (375px) e desktop (1280px)
5. Nenhuma decisão de design própria — seguir o protótipo
6. Dúvida de estilo → consultar o HTML de referência

---

## Arquitetura — onde colocar cada coisa

| O quê                           | Onde                               |
|---------------------------------|------------------------------------|
| Lógica de negócio               | `app/Services/`                    |
| Autorização                     | `app/Policies/`                    |
| Interatividade UI               | `app/Livewire/`                    |
| Notificações / jobs assíncronos | `app/Jobs/`                        |
| Queries complexas               | Model scopes ou Services           |
| Validação de forms              | Livewire `rules()` ou Form Objects |
| Cálculo de média                | `AvaliacaoService::calcularMedia()`|
| Verificar prazo contestação     | `ContestacaoService::dentroDoProzo()` |
| Verificar ciclo ativo           | `CicloService::cicloAtivo()`       |

NUNCA colocar lógica de negócio diretamente em:
- Controllers (apenas roteamento)
- Views/Blade (apenas apresentação)
- Migrations (apenas estrutura)

---

## Testes — obrigações

Ao finalizar CADA fase:
1. Rodar: `php artisan test --coverage --min=80`
2. Cobertura mínima: Services 90%, Policies 100%, Middleware 100%
3. ZERO testes falhando antes de avançar para próxima fase
4. Factories criadas para todas as entidades da fase
5. Testar sempre os casos de ERRO, não apenas o caminho feliz:
   - Dados inválidos
   - Permissão negada
   - Prazo expirado
   - Banco vazio
   - Ciclo inativo

Rodar antes de cada commit:
```bash
php artisan test
npm run build
```

---

## O que NÃO fazer

- NÃO criar endpoints REST/API (sistema é full Blade/Livewire)
- NÃO usar React, Vue ou qualquer framework JS SPA
- NÃO hardcodar dados de áreas, competências ou servidores
- NÃO criar seed automático fora do `DevelopmentSeeder`
- NÃO permitir que gestor acesse dados de outra área
- NÃO deixar avaliação editável após `status='enviada'`
- NÃO criar dois ciclos com `status='ativo'`
- NÃO usar bordas 1px para separar seções (usar bg diferente)
- NÃO tomar decisões de design — seguir o protótipo HTML
- NÃO avançar de fase sem cobertura de testes mínima

---

## Escopo da v1 — fora do projeto

- ✗ PDI (Plano de Desenvolvimento Individual)
- ✗ Nível esperado por cargo
- ✗ Avaliação 360° por pares
- ✗ Calibração / Comitê de alinhamento
- ✗ Catálogo de cursos
- ✗ Banco de talentos
- ✗ SSO / Login gov.br / AD / SIAPE
- ✗ App mobile nativo
- ✗ Dark mode
- ✗ Múltiplos idiomas
- ✗ API REST pública

---

## Instrução para início de cada fase

Antes de implementar qualquer coisa:
1. Leia este CLAUDE.md na íntegra
2. Consulte os HTMLs de protótipo do módulo a implementar
3. Implemente seguindo o protótipo pixel a pixel
4. Escreva os testes listados para a fase
5. Rode `php artisan test --coverage --min=80`
6. Se algum teste falhar ou cobertura < 80%, corrija antes de reportar como concluído
