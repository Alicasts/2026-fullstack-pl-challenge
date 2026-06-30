# Current State

Phase:
Implementation — Incremento 7 concluído (Frontend Users List)

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
- Listagem de usuários implementada na página /users com tabela Bootstrap, botão placeholder e tratamento de erro
- Testes de atualização, exclusão, disponibilidades e build do frontend executados com sucesso

In Progress:
- Nenhum

Pending:
- Regras de negócio do domínio de agendamentos
- Evolução das regras específicas por perfil nos módulos de negócio

Notes:
- O domínio de usuários está funcional na camada de dados.
- A autenticação está concluída e a base de autorização por perfil já está pronta para os próximos módulos.
- O cadastro de usuários já está disponível para administradores e validado com testes.
- O módulo de disponibilidades já está disponível para administradores e validado com testes.
- O login do frontend já está funcional e integrado ao backend com token salvo no navegador.
- A listagem de usuários já está disponível na interface com dados carregados do backend.
