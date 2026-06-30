# Current State

Phase:
Implementation — Incremento 5 concluído (Availability CRUD)

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
- Testes de atualização, exclusão e disponibilidades executados com sucesso

In Progress:
- Nenhum

Pending:
- Nenhum
- Regras de negócio do domínio de agendamentos
- Evolução das regras específicas por perfil nos módulos de negócio

Notes:
- O domínio de usuários está funcional na camada de dados.
- A autenticação está concluída e a base de autorização por perfil já está pronta para os próximos módulos.
- O cadastro de usuários já está disponível para administradores e validado com testes.
- O módulo de disponibilidades já está disponível para administradores e validado com testes.
