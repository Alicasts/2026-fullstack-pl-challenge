# Current State

Phase:
Implementation — Incremento 12 concluído (Frontend Módulo de Disponibilidades — EPIC 6 completo)

Completed:
- Infraestrutura Docker + Laravel + PostgreSQL
- Migration de users consolidada
- Seed de usuário ADMIN executado com sucesso
- Model User ajustado
- Ambiente estabilizado (sem SQLite/cache issues)
- Login via Sanctum implementado e testado
- Autenticação com tokens funcionando
- Rotas da API protegidas com `auth:sanctum`
- Controle de acesso por perfil preparado com middleware simples
- Listagem de usuários disponível para perfis autenticados
- Cadastro de usuários implementado com permissão exclusiva para ADMIN
- Validação de cadastro implementada
- Edição de usuários implementada para ADMIN e ATTENDANT com regras de permissão e validação
- Exclusão de usuários implementada com regra de proteção do último administrador
- CRUD de disponibilidades implementado para administradores com validações básicas
- Login do frontend implementado com formulário, consumo do endpoint /api/login, salvamento do token e redirecionamento para /users
- Listagem de usuários implementada na página /users com tabela Bootstrap e tratamento de erro
- Cadastro de usuários no frontend implementado em /users/new com formulário Bootstrap, campos obrigatórios, validação via API, acesso restrito a ADMIN e botão "Novo Usuário" visível apenas para administradores
- Edição de usuários no frontend implementada em /users/:id/edit com formulário Bootstrap, campos Nome e Tipo editáveis, e-mail somente leitura, ação "Editar" visível apenas para ADMIN, feedback de sucesso e tratamento de erros 422/403
- Exclusão de usuários no frontend implementada na listagem com modal Bootstrap de confirmação, ação "Excluir" visível apenas para ADMIN, consumo de `DELETE /api/users/{id}`, atualização local da listagem e mensagens de sucesso/erro alinhadas à API
- Correção de RQF-USER-003: ATTENDANT pode editar o próprio perfil; botão "Editar" visível apenas na própria linha para ATTENDANTs; campo "Tipo de Usuário" somente leitura para ATTENDANTs na tela de edição; ADMIN mantém acesso irrestrito a todos os usuários
- Módulo de disponibilidades no frontend implementado (EPIC 6):
  - Listagem de disponibilidades em /availabilities com tabela Bootstrap (atendente, dia da semana, hora inicial, hora final, status badge), modal de confirmação para exclusão, acesso restrito a ADMIN
  - Cadastro de disponibilidades em /availabilities/new com formulário Bootstrap, seleção de atendente (apenas ATTENDANTs), dia da semana, hora inicial/final (input type="time"), status, validação via API, acesso restrito a ADMIN
  - Edição de disponibilidades em /availabilities/:id/edit com formulário pré-preenchido, mesmos campos do cadastro, acesso restrito a ADMIN
  - Exclusão de disponibilidades com modal de confirmação Bootstrap, consumo de `DELETE /api/availabilities/{id}` e atualização local da listagem
- Navegação entre módulos: link "Disponibilidades" adicionado na tela de usuários (visível para ADMIN)
- Build do frontend executado com sucesso (80 módulos, sem erros ou warnings)

In Progress:
- Nenhum

Pending:
- Módulo de agendamentos (modelagem, ocupação, regras de negócio) — fora do escopo da challenge mínima
- EPIC 7 — Entrega: README final, revisão Docker, scripts de seed automatizados

Notes:
- O domínio de usuários está funcional na camada de dados e no frontend (listagem, cadastro, edição e exclusão para ADMIN e ATTENDANT conforme permissões).
- O módulo de disponibilidades está funcional ponta a ponta: backend (CRUD + slots) e frontend (listagem, cadastro, edição e exclusão, acesso ADMIN).
- A autenticação está concluída e a base de autorização por perfil já está pronta.
- Débito técnico aceito: chamadas independentes a `GET /api/me` em cada página; sem AuthContext ou cache de usuário neste estágio da challenge.
- Inconsistência de convenção `day_of_week` no backend (validação `between:0,6` vs uso de `dayOfWeekIso` 1–7 no endpoint de slots) está documentada em open-questions e não é escopo do frontend corrigir.
