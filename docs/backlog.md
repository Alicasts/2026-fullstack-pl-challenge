# Backlog Técnico

## EPIC 1 — Infraestrutura (INCREMENTO 1)

- [x] Criar projeto Laravel
- [x] Configurar Docker (app + postgres)
- [x] Configurar .env
- [x] Conectar Laravel ao PostgreSQL
- [x] Rodar migrations base
- [x] Criar migration inicial de users (estrutura mínima)
- [ ] Validar ambiente (health check endpoint)

---

## EPIC 2 — Arquitetura

- [x] Definir camadas (Controller → FormRequest → Model → PostgreSQL)
- [x] Definir padrão de controllers
- [ ] Definir padrão de services
- [ ] Definir repositories
- [x] Definir tratamento de erros
- [x] Definir estrutura das respostas HTTP

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
- [x] Edição de usuários (RQF-USER-003)
- [x] Exclusão de usuários (RQF-USER-001)

---

## EPIC 4 — Disponibilidade

- [x] Modelagem
- [x] CRUD
- [ ] Regras de disponibilidade (sobreposição, slots)
- [x] Testes

---

## EPIC 5 — Agendamentos

- [ ] Modelagem
- [x] Consulta de horários (endpoint GET /api/available-slots)
- [ ] Ocupação
- [ ] Testes completos de agendamento

---

## EPIC 6 — Frontend

- [x] Layout (Bootstrap via CDN, roteamento React Router)
- [x] Usuários (listagem, cadastro, edição, exclusão, permissões por perfil)
- [x] Disponibilidade (listagem, cadastro, edição, exclusão — ADMIN only)
- [ ] Consulta de horários disponíveis (tela frontend para RQF-SCHEDULE-003)
- [x] Tratamento de erros (alertas Bootstrap, feedback 422/403/404)

---

## EPIC 7 — Entrega

- [ ] README final completo
- [ ] Docker funcional end-to-end verificado
- [ ] Banco inicializado com seed automatizado
- [ ] Scripts de inicialização documentados
- [ ] Revisão final
