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
| RQF-USER-001 | A lista de usuários é a mesma para todos os perfis. Deve existir pelo menos um usuário com perfil de Administrador. Administradores podem excluir outros usuários. Atendentes apenas visualizam a lista de usuários. Apenas administradores podem ir para a tela/modal de inclusão de novos usuários. Atendentes podem editar apenas o próprio usuário. Administradores podem editar qualquer usuário. Botões de edição e exclusão devem ser exibidos conforme permissões. Exclusão disponível apenas para administradores. Excluir usuário deve acionar um modal de confirmação. Se confirmado, remover o registro. Se cancelado, nenhuma ação deve ser tomada. | `GET /api/users` autenticado retorna a mesma coleção para ADMIN e ATTENDANT. | `ListUsersTest::test_admin_and_attendant_receive_the_same_users_list` | ✅ |
| RQF-USER-002 | Apenas administradores podem acessar a tela de cadastro de usuários. Campos obrigatórios: nome, tipo de usuário, senha, confirme a senha e e-mail. Tipos de usuário válidos: Administrador e Atendente. E-mail deve ser válido e único. Senha deve ter mínimo de 8 caracteres. Confirme a senha deve ser igual ao campo senha. Ao salvar, o sistema deve validar e persistir o novo usuário. | `POST /api/users` com `FormRequest`, `Hash::make()` e persistência via Eloquent. | `CreateUserTest` | ✅ |
| RQF-USER-003 | Administradores podem editar dados de qualquer usuário. Atendentes podem editar apenas os dados do próprio usuário. Os campos editáveis são os mesmos do cadastro, exceto e-mail e senha. As mesmas validações de cadastro devem ser aplicadas na edição. Ao salvar, o sistema deve validar e persistir as alterações. | `PUT /api/users/{user}` com `UserController@update`, `UpdateUserRequest`, autorização por perfil e atualização via Eloquent. | `UpdateUserTest` | ✅ |
| RQF-USER-001 | Exclusão de usuários disponível apenas para administradores. O sistema deve preservar ao menos um administrador; se a tentativa violar essa regra, retorna `422` com a mensagem informada. | `DELETE /api/users/{user}` com `UserController@destroy`, proteção do último administrador e remoção via Eloquent. | `DeleteUserTest` | ✅ |

## RQF2 - Módulo de Disponibilidades

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| RQF-AVAILABILITY-001 | Cadastro e gerenciamento simples de disponibilidades por atendente, com campos obrigatórios e validações básicas. | `GET/POST/PUT/DELETE /api/availabilities` com `AvailabilityController`, `FormRequest` e Eloquent. | `AvailabilityTest` | ✅ |

## RQF3 - Módulo de Agendamentos

| Requisito | Descrição | Implementação | Teste | Status |
|-----------|-----------|---------------|--------|--------|
| RQF-SCHEDULE-001 | Dados e atributos não especificados podem ser criados como mocks. Exemplo: informações de cliente associados a um agendamento. | — | — | ⏳ |
| RQF-SCHEDULE-002 | Apenas administradores podem cadastrar ou alterar a disponibilidade dos atendentes. Campos obrigatórios: atendente, dia da semana, hora inicial, hora final e ativo. Deve ser possível definir janelas de disponibilidade por dia da semana. Hora final deve ser maior que hora inicial. | — | — | ⏳ |
| RQF-SCHEDULE-003 | Ao selecionar um atendente e uma data, o sistema deve listar apenas horários disponíveis para novo agendamento. Horários ocupados não devem ser exibidos como opção válida. | — | — | ⏳ |

## Legenda

- ⏳ Pendente
- 🚧 Em desenvolvimento
- ✅ Concluído
