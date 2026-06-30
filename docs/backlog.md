# Backlog Técnico

## EPIC 1 — Infraestrutura (INCREMENTO 1)

- [x] Criar projeto Laravel
- [x] Configurar Docker (app + postgres)
- [x] Configurar .env
- [x] Conectar Laravel ao PostgreSQL
- [x] Rodar migrations base
- [ ] Criar migration inicial de users (estrutura mínima)
- [ ] Validar ambiente (health check endpoint)

---

## EPIC 2 — Arquitetura

- [ ] Definir camadas
- [ ] Definir padrão de controllers
- [ ] Definir padrão de services
- [ ] Definir repositories
- [ ] Definir tratamento de erros
- [ ] Definir estrutura das respostas HTTP

---

## EPIC 2.1 — Autenticação e Autorização

- [x] Implementar login com Sanctum
- [x] Proteger rotas de API com `auth:sanctum`
- [x] Preparar controle de acesso por perfil com middleware simples
- [x] Testes de autenticação e autorização

---

## EPIC 3 — Usuários

- [x] Modelagem
- [x] Migration
- [x] Seed administrador
- [x] Listagem de usuários
- [x] Cadastro de usuários
- [x] Validações de cadastro
- [x] Testes de cadastro
- [ ] CRUD

---

## EPIC 4 — Disponibilidade

- [ ] Modelagem
- [ ] CRUD
- [ ] Regras de disponibilidade
- [ ] Testes

---

## EPIC 5 — Agendamentos

- [ ] Modelagem
- [ ] Consulta de horários
- [ ] Ocupação
- [ ] Testes

---

## EPIC 6 — Frontend

- [ ] Layout
- [ ] Usuários
- [ ] Disponibilidade
- [ ] Consulta
- [ ] Tratamento de erros

---

## EPIC 7 — Entrega

- [ ] README
- [ ] Docker funcional
- [ ] Banco inicializado
- [ ] Seeds
- [ ] Scripts
- [ ] Revisão final
