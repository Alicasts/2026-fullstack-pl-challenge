# Matriz de Rastreabilidade

## Infraestrutura de Autenticação

| Requisito | Implementação | Teste | Status |
|-----------|---------------|--------|--------|
| INF-AUTH-001 | `POST /api/login` com Sanctum e token pessoal | `LoginTest::test_login_succeeds_with_valid_credentials` | ✅ |
| INF-AUTH-002 | Rotas da API protegidas com `auth:sanctum` | `AuthorizationTest::test_access_without_token_returns_401` / `AuthorizationTest::test_access_with_valid_token_is_allowed` | ✅ |
| INF-AUTH-003 | Middleware simples de perfil para ADMIN/ATTENDANT | `AuthorizationTest::test_admin_is_authorized_for_admin_route` / `AuthorizationTest::test_attendant_is_blocked_for_admin_route` | ✅ |

| Requisito | Implementação | Teste | Status |
|-----------|---------------|--------|--------|
| RQF-USER-001 | `GET /api/users` autenticado com listagem única para todos os perfis | `ListUsersTest::test_admin_and_attendant_receive_the_same_users_list` | ✅ |
| RQF-USER-002 | — | — | ⏳ |
| RQF-USER-003 | — | — | ⏳ |
| RQF-USER-004 | — | — | ⏳ |
| RQF-SCHEDULE-001 | — | — | ⏳ |
| RQF-SCHEDULE-002 | — | — | ⏳ |
| RQF-SCHEDULE-003 | — | — | ⏳ |

Legenda

- ⏳ Pendente
- 🚧 Em desenvolvimento
- ✅ Concluído
