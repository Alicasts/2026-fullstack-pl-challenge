# 2026 Fullstack PL Challenge

Projeto Laravel 13 com PostgreSQL e Docker.

## Stack

- Backend: Laravel
- Banco: PostgreSQL
- Infra: Docker
- Autenticação: Sanctum

## Início rápido

```bash
sh dev build
```

Esse comando prepara tudo para o primeiro uso:

- constrói as imagens Docker;
- sobe o PostgreSQL;
- gera a `APP_KEY` se necessário;
- executa migrations;
- aplica o seed inicial;
- deixa a stack pronta para uso.

## Desenvolvimento

```bash
sh dev start-dev
```

Esse comando sobe a aplicação em background e deixa o ambiente pronto para desenvolvimento diário.

## Acesso local

- Aplicação: `http://localhost:8080`
- PostgreSQL: `localhost:5432`

## Autenticação

Endpoint disponível:

- `POST /api/login`

Exemplo de resposta:

```json
{
  "token": "...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@app.com",
    "role": "ADMIN"
  }
}
```

## Usuários

Endpoints disponíveis:

- `GET /api/users`
- `POST /api/users` somente para `ADMIN`

Exemplo de cadastro:

```json
{
  "name": "Maria Silva",
  "email": "maria@example.com",
  "role": "ATTENDANT",
  "password": "password123",
  "password_confirmation": "password123"
}
```

## Testes

```bash
docker compose run --rm app php artisan test
```

## Estrutura

- O código principal da aplicação fica em `backend/`.
- A infraestrutura fica em `docker/` e `docker-compose.yml`.
- A documentação do projeto fica em `docs/`.
