# 2026 Fullstack PL Challenge

Projeto fullstack para gerenciamento de usuários, disponibilidades e agendamentos.

Stack principal:
- Backend: Laravel
- Banco: PostgreSQL
- Infra: Docker
- Frontend: (definido posteriormente)

---

## Arquitetura

Estrutura base:

Controller → Form Request → Service → Model (Eloquent) → PostgreSQL

---

## Como rodar o projeto

### 1. Subir o ambiente

Na raiz do projeto:

```bash
docker compose up -d --build
docker exec -it laravel_app bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
