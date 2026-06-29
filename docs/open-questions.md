# Questões em Aberto

Estas decisões ainda NÃO foram tomadas.

Somente serão resolvidas quando houver informação suficiente.

---

## Autenticação

O desafio não especifica:

- Login
- Sessão
- JWT
- Basic Auth
- Mock

A estratégia será definida durante o desenho da arquitetura.

---

## Exclusão do último administrador

O desafio informa apenas:

> Deve existir ao menos um administrador.

Não informa:

- se pode excluir o último
- se deve bloquear
- se deve promover outro usuário

---

## Disponibilidades sobrepostas

Não está definido se um atendente pode possuir:

08:00–12:00

09:00–11:00

---

## Slot de horários

Não existe definição sobre:

- duração mínima
- intervalo entre horários
- granularidade (15, 30 ou 60 minutos)

---

## Exclusão de usuários

Não está definido o comportamento quando existirem:

- disponibilidades cadastradas
- agendamentos

---

## Timezone

O desafio não especifica timezone.

---

## Primeiro administrador

Não está definido como o primeiro administrador será criado.

---

## Alteração de perfil

Não está definido se um administrador pode alterar:

- o próprio perfil
- o perfil do último administrador