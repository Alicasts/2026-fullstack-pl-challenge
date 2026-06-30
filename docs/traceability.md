# Matriz de Rastreabilidade

Este documento relaciona os requisitos extraídos com a implementação atual do projeto.

## Infraestrutura de Autenticação

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| INF-AUTH-001 | Autenticação baseada em tokens com Sanctum, usando `POST /api/login` para emissão de Personal Access Token. | `AuthController@login` emite token e retorna usuário autenticado. | `LoginTest::test_login_succeeds_with_valid_credentials` | ✅ |
| INF-AUTH-002 | Proteção das rotas da API com `auth:sanctum`, garantindo que requisições sem token válido sejam recusadas. | Grupo de rotas autenticadas em `routes/api.php`. | `AuthorizationTest::test_access_without_token_returns_401` / `AuthorizationTest::test_access_with_valid_token_is_allowed` | ✅ |
| INF-AUTH-003 | Controle simples de acesso por perfil para distinguir ADMIN e ATTENDANT. | Middleware `role` registrado no bootstrap e aplicado em rotas específicas. | `AuthorizationTest::test_admin_is_authorized_for_admin_route` / `AuthorizationTest::test_attendant_is_blocked_for_admin_route` | ✅ |

## RQF1 - Módulo de Usuários

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| RQF-USER-001 | Lista igual para todos os perfis. ADMIN pode excluir. ATTENDANT só visualiza. Botões conforme permissão. Modal de confirmação na exclusão. | Backend: `GET /api/users` + `DELETE /api/users/{id}`. Frontend: `Users.jsx` com coluna Ações condicional, modal Bootstrap. | `ListUsersTest` / `DeleteUserTest` | ✅ |
| RQF-USER-002 | Apenas ADMIN cadastra. Campos obrigatórios: nome, tipo, senha, confirmação, e-mail. Validações de unicidade, formato e confirmação de senha. | Backend: `POST /api/users` com `StoreUserRequest`. Frontend: `UserCreate.jsx` com acesso restrito e validação 422. | `CreateUserTest` | ✅ |
| RQF-USER-003 | ADMIN edita qualquer usuário. ATTENDANT edita apenas o próprio. Campos editáveis: nome e tipo (exceto e-mail e senha). | Backend: `PUT /api/users/{id}` com `UpdateUserRequest`. Frontend: `UserEdit.jsx` — ATTENDANT acessível apenas para o próprio id; campo Tipo desabilitado para ATTENDANT. | `UpdateUserTest` | ✅ |

## RQF2 - Módulo de Disponibilidades

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| RQF-AVAILABILITY-001 (backend) | CRUD completo de disponibilidades para ADMIN, com campos obrigatórios e validações básicas. | Backend: `GET/POST/PUT/DELETE /api/availabilities` com `AvailabilityController` e `FormRequest`. | `AvailabilityTest` | ✅ |
| RQF-SCHEDULE-002 (frontend) | Apenas ADMIN cadastra ou altera disponibilidade dos atendentes. Campos: atendente, dia da semana, hora inicial, hora final, ativo. Hora final > hora inicial. | Frontend: `Availabilities.jsx`, `AvailabilityCreate.jsx`, `AvailabilityEdit.jsx` — acesso restrito a ADMIN via `GET /api/me`, seleção de atendentes filtrada por role=ATTENDANT, input type="time", validação 422. | — | ✅ |

## RQF3 - Módulo de Agendamentos

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| RQF-SCHEDULE-001 | Dados e atributos não especificados podem ser criados como mocks. | — | — | ⏳ |
| RQF-SCHEDULE-003 | Ao selecionar atendente e data, listar apenas horários disponíveis. Horários ocupados não exibidos. | Backend: `GET /api/available-slots` implementado. Frontend: tela não implementada. | — | 🚧 |

## Legenda

- ⏳ Pendente
- 🚧 Parcialmente implementado (backend pronto, frontend pendente)
- ✅ Concluído
