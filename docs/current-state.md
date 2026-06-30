# Current State

Phase:
Implementation — Incremento 11 concluído (Frontend Correção RQF-USER-003: edição pelo próprio ATTENDANT)

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
- Testes de atualização, exclusão, disponibilidades e build do frontend executados com sucesso

In Progress:
- Nenhum

Pending:
- Módulo de disponibilidades no frontend (EPIC 6)
- Regras de negócio do domínio de agendamentos
- Evolução das regras específicas por perfil nos módulos de negócio

Notes:
- O domínio de usuários está funcional na camada de dados e no frontend (listagem, cadastro, edição e exclusão para ADMIN).
- A autenticação está concluída e a base de autorização por perfil já está pronta para os próximos módulos.
- O módulo de disponibilidades já está disponível para administradores no backend e validado com testes.
- O login do frontend já está funcional e integrado ao backend com token salvo no navegador.
- Débito técnico aceito: chamadas duplicadas a `GET /api/me` em páginas distintas (`Users`, `UserCreate`, `UserEdit`); sem AuthContext ou cache de usuário neste estágio da challenge.
