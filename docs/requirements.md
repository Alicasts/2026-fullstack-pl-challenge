# Requirements

Status: Extraído e documentado.

Source of truth:
- docs/challange.docx

## Objetivo

Criar uma aplicação web para registro e gerenciamento de agendamentos de atendimentos entre clientes e atendentes, com controle de usuários e disponibilidade de agenda.

## Requisitos Não Funcionais

- RQNF1: Backend em PHP 7+ ou superior.
- RQNF1: Banco de dados deve ser PostgreSQL, Oracle, MySql ou MS SQL Server.
- RQNF1: Frontend deve demonstrar conhecimentos sólidos em JavaScript, CSS e HTML. O uso de frameworks como React, Vue ou Angular é opcional.
- RQNF2: Todas as respostas do backend devem usar códigos HTTP apropriados: 200, 201, 400, 401, 403, 404, 500, etc.
- RQNF3: O frontend deve tratar mensagens de erro retornadas pelo backend e exibir feedback amigável ao usuário.
- RQNF4: Campos marcados com asterisco (*) são obrigatórios.

## Requisitos Funcionais

### RQF1 - Módulo de Usuários

RQF-USER-001 - Listagem de Usuários

- A lista de usuários é a mesma para todos os perfis.
- Deve existir pelo menos um usuário com perfil de Administrador.
- Administradores podem excluir outros usuários.
- Atendentes apenas visualizam a lista de usuários.
- Apenas administradores podem ir para a tela/modal de inclusão de novos usuários.
- Atendentes podem editar apenas o próprio usuário.
- Administradores podem editar qualquer usuário.
- Botões de edição e exclusão devem ser exibidos conforme permissões:
  - exclusão disponível apenas para administradores.
- Excluir usuário deve acionar um modal de confirmação.
  - Se confirmado, remover o registro.
  - Se cancelado, nenhuma ação deve ser tomada.

RQF-USER-002 - Inserção de Usuários

- Apenas administradores podem acessar a tela de cadastro de usuários.
- Campos obrigatórios:
  - Nome
  - Tipo de Usuário
  - Senha*
  - Confirme a Senha*
  - E-mail***
- Tipos de usuário válidos:
  - Administrador
  - Atendente
- E-mail deve ser válido e único na base de dados.
- Senha deve ter mínimo de 8 caracteres.
- Confirme a Senha deve ser igual ao campo Senha.
- Ao salvar, o sistema deve validar e persistir o novo usuário.

RQF-USER-003 Edição de Usuários

- Administradores podem editar dados de qualquer usuário.
- Atendentes podem editar apenas os dados do próprio usuário.
- Os campos editáveis são os mesmos do cadastro, exceto E-mail e Senha.
- As mesmas validações de cadastro devem ser aplicadas na edição.
- Ao salvar, o sistema deve validar e persistir as alterações.

### RQF2 - Módulo de Agendamentos

RQF-SCHEDULE-001 - Dados não especificados

- Dados e atributos não especificados podem ser criados como mocks. Exemplo: informações de cliente associados a um agendamento.

RQF-SCHEDULE-002 - Cadastro de Disponibilidade do Atendente

- Apenas administradores podem cadastrar ou alterar a disponibilidade dos atendentes.
- Campos obrigatórios:
  - Atendente
  - Dia da Semana
  - Hora Inicial*
  - Hora Final*
  - Ativo?*
- Deve ser possível definir janelas de disponibilidade por dia da semana.
- Hora Final deve ser maior que Hora Inicial.

RQF-SCHEDULE-003 - Consulta de Horários Disponíveis

- Ao selecionar um atendente e uma data, o sistema deve listar apenas horários disponíveis para novo agendamento.
- Horários ocupados não devem ser exibidos como opção válida.


## Observações de escopo

- Focar no gerenciamento de usuários e disponibilidade de atendentes.
- Implementar a consulta de horários disponíveis como requisito mínimo para agendamentos.
- Usar dados fictícios para informações de cliente quando necessário.
- Não é obrigatório implementar um sistema complexo de gerenciamento de clientes além do necessário para demonstração de disponibilidade/agendamentos.