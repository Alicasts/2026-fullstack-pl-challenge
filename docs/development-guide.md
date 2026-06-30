# Development Guide

## Objetivo

Este projeto é desenvolvido de forma incremental, priorizando simplicidade, legibilidade e aderência aos requisitos da challenge.

## Princípios

- Implementar apenas um requisito por incremento.
- Evitar overengineering.
- Preferir convenções do Laravel.
- Não criar abstrações sem necessidade.
- Código deve ser fácil de revisar.
- Documentação é a fonte de verdade.

## Arquitetura

Controller

↓

Form Request

↓

Model (Eloquent)

↓

PostgreSQL

## Convenções

Não utilizar:

- Repository
- Base Controller
- Response Helpers
- Services sem necessidade real

Utilizar:

- Route Model Binding
- Form Requests
- Eloquent
- PHPUnit
- Sanctum

## Fluxo

Cada incremento deve conter:

- implementação;
- testes;
- atualização da documentação;
- commit específico.

## Documentação

Atualizar apenas arquivos existentes.

README apenas quando necessário.

## Objetivo final

Entregar um MVP completo, simples, consistente e fácil de explicar durante uma avaliação técnica.