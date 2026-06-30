# Architecture Decision Records (ADR)

Este documento registra apenas decisões consolidadas.

Uma ADR nunca deve registrar hipóteses ou decisões tomadas apenas por conveniência.

---

# ADR-001 — Backend utilizando Laravel

## Contexto

O desafio exige backend em PHP (preferencialmente PHP 7+) e permite a utilização de frameworks.

## Decisão

Utilizar Laravel como framework backend.

## Justificativa

O Laravel fornece uma estrutura consolidada para desenvolvimento de aplicações web, reduzindo código de infraestrutura e permitindo concentrar o esforço nos requisitos de negócio da challenge.

Além disso, oferece recursos nativos como:

- Roteamento
- Injeção de Dependência
- Migrations
- Seeders
- Validação
- Eloquent ORM
- Testes
- Tratamento de exceções

## Alternativas

- PHP puro
- Symfony
- Slim

## Consequências

### Positivas

- Menor tempo de desenvolvimento.
- Código mais organizado.
- Arquitetura conhecida e amplamente utilizada.
- Facilidade para implementação de testes.
- Melhor legibilidade para avaliadores familiarizados com Laravel.

### Negativas

- Dependência do framework.
- Curva de aprendizado inicial para quem possui pouca experiência em PHP.

---

# ADR-002 — Banco PostgreSQL

## Contexto

O desafio permite PostgreSQL, Oracle, MySQL ou SQL Server.

## Decisão

Utilizar PostgreSQL.

## Alternativas

- MySQL
- SQL Server
- Oracle

## Consequências

- Banco robusto
- Compatível com Docker
- Ambiente já preparado

---

# ADR-003 — Testes automatizados

## Contexto

O desafio valoriza qualidade de código e manutenção.

## Decisão

Toda regra de negócio deverá possuir testes automatizados.

## Alternativas

- Apenas testes manuais
- Apenas testes de integração

## Consequências

- Maior segurança para evolução incremental
- Menor risco de regressão

# ADR-004 — Authentication com Sanctum

## Contexto

- O desafio exige controle de permissões entre Administrador e Atendente.
- Será necessária autenticação para proteger endpoints.
- O desafio não especifica JWT, sessão ou outro mecanismo.
- A implementação de login via `/api/login` já está concluída.

## Decisão

- Utilizar Laravel Sanctum para autenticação baseada em tokens.

## Justificativa

- Solução oficial do Laravel.
- Integração simples.
- Menor complexidade que JWT.
- Atende completamente aos requisitos da challenge.
- Facilita proteção de rotas com middleware nativo.
- O login já emite Personal Access Token e as rotas protegidas passam a usar `auth:sanctum`.

## Alternativas

- JWT (complexidade desnecessária).
- Sessão tradicional (menos adequada para API).
- Basic Auth (não atende bem ao cenário).

## Consequências

* Positivas
- Menor esforço de manutenção.
- Solução conhecida por desenvolvedores Laravel.
- Boa integração com testes.
- Autenticação consolidada com base pronta para autorização por perfil.

* Negativas
- Dependência de um pacote oficial adicional (embora mantido pelo ecossistema Laravel).
