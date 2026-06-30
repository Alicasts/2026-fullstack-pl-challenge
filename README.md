# 2026 Fullstack PL Challenge

Aplicação web para registro e gerenciamento de agendamentos de atendimentos entre clientes e atendentes, com controle de usuários e disponibilidade de agenda.

## Stack

- **Backend:** Laravel 11 + PHP 8
- **Banco:** PostgreSQL
- **Infra:** Docker + Docker Compose
- **Autenticação:** Laravel Sanctum (Personal Access Tokens)
- **Frontend:** React + Vite + Bootstrap 5

## Início rápido

```bash
sh dev build
```

Esse comando prepara tudo para o primeiro uso:

- constrói as imagens Docker (backend e frontend);
- sobe o PostgreSQL;
- gera a `APP_KEY` se necessário;
- executa migrations;
- aplica o seed inicial (usuário ADMIN);
- deixa a stack pronta para uso.

## Desenvolvimento

```bash
sh dev start-dev
```

Sobe a aplicação em background e deixa o ambiente pronto para desenvolvimento diário.

## Acesso local

| Serviço | URL |
|---------|-----|
| Frontend | http://localhost:5173 |
| Backend API | http://localhost:8080 |
| PostgreSQL | localhost:5432 |

## Credencial padrão (seed)

```
E-mail: admin@app.com
Senha:  password
```

---

## Módulos implementados

### Autenticação

- Login com e-mail e senha via `POST /api/login`
- Token Bearer salvo no navegador
- Todas as rotas da API protegidas com `auth:sanctum`

### Módulo de Usuários

Gerenciamento completo de usuários com controle de acesso por perfil.

**Perfis:**
- `ADMIN` — acesso completo a todas as funcionalidades
- `ATTENDANT` — visualiza a lista de usuários e edita apenas o próprio perfil

**Telas:**
| Rota | Descrição | Acesso |
|------|-----------|--------|
| `/users` | Listagem de usuários com ações de editar e excluir | Todos autenticados |
| `/users/new` | Cadastro de novo usuário | ADMIN |
| `/users/:id/edit` | Edição de usuário | ADMIN (qualquer) / ATTENDANT (próprio) |

**Regras:**
- Exclusão disponível apenas para ADMIN com modal de confirmação
- Proteção do último administrador (não pode ser excluído)
- ATTENDANT não pode alterar o próprio tipo de usuário

### Módulo de Disponibilidades

Gerenciamento das janelas de disponibilidade dos atendentes por dia da semana.

**Acesso:** apenas ADMIN pode visualizar e gerenciar disponibilidades.

**Telas:**
| Rota | Descrição |
|------|-----------|
| `/availabilities` | Listagem de todas as disponibilidades cadastradas |
| `/availabilities/new` | Cadastro de nova disponibilidade |
| `/availabilities/:id/edit` | Edição de disponibilidade existente |

**Campos:**
- **Atendente** — seleção entre usuários do tipo ATTENDANT
- **Dia da Semana** — Domingo a Sábado (0–6)
- **Hora Inicial** — formato HH:MM
- **Hora Final** — deve ser maior que a hora inicial (validado pelo backend)
- **Status** — Ativo / Inativo

**Comportamento:**
- Botão "Disponibilidades" na tela de usuários navega para o módulo (visível apenas para ADMIN)
- Exclusão com modal de confirmação Bootstrap
- Feedback de sucesso e erro via alertas Bootstrap
- Redirecionamento automático após salvar

---

## API — Endpoints disponíveis

### Autenticação

```
POST /api/login
```

### Usuários

```
GET    /api/users              — lista todos os usuários (autenticado)
POST   /api/users              — cria usuário (ADMIN)
PUT    /api/users/{id}         — edita usuário (ADMIN ou próprio ATTENDANT)
DELETE /api/users/{id}         — exclui usuário (ADMIN)
GET    /api/me                 — retorna dados do usuário autenticado
```

### Disponibilidades

```
GET    /api/availabilities           — lista disponibilidades (ADMIN)
POST   /api/availabilities           — cria disponibilidade (ADMIN)
PUT    /api/availabilities/{id}      — edita disponibilidade (ADMIN)
DELETE /api/availabilities/{id}      — exclui disponibilidade (ADMIN)
GET    /api/available-slots          — consulta slots disponíveis por atendente e data (autenticado)
```

Exemplo de consulta de slots:
```
GET /api/available-slots?attendant_id=2&date=2026-07-01
```

---

## Testes (backend)

```bash
docker compose run --rm app php artisan test
```

Cobertura atual: autenticação, autorização, CRUD de usuários, CRUD de disponibilidades e consulta de slots.

---

## Estrutura do projeto

```
.
├── backend/          — Laravel 11 (API)
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Http/Requests/
│   │   ├── Models/
│   │   └── Enums/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
├── frontend/         — React + Vite
│   └── src/
│       ├── components/
│       │   ├── Login.jsx
│       │   ├── Users.jsx
│       │   ├── UserCreate.jsx
│       │   ├── UserEdit.jsx
│       │   ├── Availabilities.jsx
│       │   ├── AvailabilityCreate.jsx
│       │   └── AvailabilityEdit.jsx
│       ├── api/axios.js
│       └── App.jsx
├── docker/
├── docs/             — documentação técnica do projeto
└── docker-compose.yml
```

---

## O que foi entregue

| Funcionalidade | Backend | Frontend |
|---------------|---------|----------|
| Autenticação (login/logout) | ✅ | ✅ |
| Listagem de usuários | ✅ | ✅ |
| Cadastro de usuários | ✅ | ✅ |
| Edição de usuários | ✅ | ✅ |
| Exclusão de usuários | ✅ | ✅ |
| Controle de acesso por perfil | ✅ | ✅ |
| Listagem de disponibilidades | ✅ | ✅ |
| Cadastro de disponibilidades | ✅ | ✅ |
| Edição de disponibilidades | ✅ | ✅ |
| Exclusão de disponibilidades | ✅ | ✅ |
| Consulta de slots disponíveis | ✅ | ⏳ |
| Módulo de agendamentos | ⏳ | ⏳ |

## O que ficou fora do escopo

- **Tela de consulta de horários disponíveis (frontend):** o endpoint `GET /api/available-slots` está implementado no backend, mas não há tela correspondente no frontend.
- **Módulo de agendamentos:** modelagem, cadastro e gerenciamento de agendamentos não foram implementados. O desafio define como escopo mínimo apenas a consulta de disponibilidade, que está coberta pelo backend.
- **Regras avançadas de disponibilidade:** sobreposição de janelas e granularidade de slots (15/30/60 min) não foram definidas no enunciado e não foram implementadas.
