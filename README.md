# Pay-It

O Pay-it é uma plataforma de pagamentos que permite depósitos e transferências de dinheiro entre usuários. A aplicação é organizada utilizando Domain-Driven Design (DDD) para garantir escalabilidade, modularidade e manutenabilidade.

## Índice

- [Recursos](#recursos)
- [Requisitos Funcionais](#requisitos-funcionais)
- [Requisitos Não Funcionais](#requisitos-nao-funcionais)
- [SLA](#sla)
- [Modelos](#modelos)
- [Estrutura de Banco de Dados](#estrutura-de-banco-de-dados)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Domínios](#domínios)
  - [Domínio de Usuários (User Domain)](#domínio-de-usuários-user-domain)
  - [Domínio de Carteiras (Wallet Domain)](#domínio-de-carteiras-wallet-domain)
  - [Domínio de Transações (Transaction Domain)](#domínio-de-transações-transaction-domain)
  - [Domínio de Notificações (Notification Domain)](#domínio-de-notificações-notification-domain)
  - [Domínio de Autorização](#domínio-de-autorização)
- [Setup](#setup)
- [Possíveis Melhorias Futuras](#possiveis-melhorias-futuras)

## Recursos

- Cadastro de usuários com nome completo, CPF, e-mail e senha. CPF e e-mail devem ser únicos no sistema.
- Transferências de dinheiro entre usuários comuns e lojistas.
- Lojistas só podem receber dinheiro, não enviar. Apenas usuários comuns podem enviar dinheiro.
- Validação de saldo antes da transferência.
- Notificações de transações.
- Auditoria de operações.

## Requisitos Funcionais

### 1. Transferências
- Implementar e testar todos os cenários possíveis para transferências entre usuários.
- Garantir que as transferências sejam devidamente registradas e que as notificações sejam enviadas após a conclusão.

### 2. Autorização
- Certificar-se de que o serviço de autorização externo está integrado e funcionando corretamente para cada transferência.
- Implementar mecanismos de fallback ou retry caso o serviço de autorização falhe.

### 3. Notificações
- Implementar o envio de notificações via diferentes canais (e-mail, SMS) após transferências e depósitos.
- Configurar estratégias de retry para casos onde o serviço de notificação esteja indisponível.

## Requisitos Não Funcionais

### 1. Performance e Escalabilidade
- Realizar testes de carga para garantir que a aplicação suporte altos volumes de transações financeiras simultâneas.
- Revisar a arquitetura para melhorar a escalabilidade, como a separação de domínios em microserviços no futuro.

### 2. Segurança
- Verificar e proteger contra ataques CSRF e XSS.
- Implementar validação e sanitização de entradas para evitar injeções SQL e outras vulnerabilidades.

### 3. Concorrência e Integridade dos Dados
- Confirmar que o uso de locks (Pessimistic Locking) previne condições de corrida e garante a integridade dos dados em cenários de alta concorrência.
- Implementar handling de deadlocks.

### 4. Observabilidade e Monitoramento
- Configurar monitoramento para transações financeiras, incluindo logs detalhados, métricas de performance e alertas para falhas.
- Integrar com uma solução de observabilidade como o Datadog para melhor visibilidade do sistema em produção.

### 5. Testes Automatizados
- Garantir uma cobertura de testes alta, incluindo testes unitários, de integração e de carga.
- Configurar um pipeline de CI/CD robusto para facilitar a entrega contínua com segurança.

### 6. Documentação
- Completar e revisar a documentação da API, incluindo detalhes sobre as rotas, parâmetros, respostas e possíveis erros.
- Documentar a arquitetura da aplicação, padrões de design usados, e instruções para desenvolvedores futuros.

## SLA

- Availability: 99%
- Accuracy: 100%
- Capacity: 100 RPS
- Latency: p95 = 0.8s, p99 = 3s , max= 5s

## Modelos

### User

Modelo base para os usuários, contendo atributos comuns a todos os tipos de usuário.

### CommonUser

Extende `User`, representando os usuários comuns.

### MerchantUser

Extende `User`, representando os lojistas.

### Wallet

Modelo para a carteira dos usuários, contendo o saldo.

### Transaction

Modelo para as transações entre usuários.

## Estrutura de Banco de Dados

### Tabelas

#### users

- `id`: Identificador único do usuário.
- `name`: Nome completo do usuário.
- `cpf`: CPF do usuário.
- `email`: E-mail do usuário.
- `password`: Senha do usuário.
- `type`: Tipo de usuário (`common_user`, `merchant_user`).
- `timestamps`: Data de criação e atualização do registro.

#### wallets

- `id`: Identificador único da carteira.
- `user_id`: Chave estrangeira para a tabela `users`.
- `balance`: Saldo da carteira.
- `timestamps`: Data de criação e atualização do registro.

#### transactions

- `id`: Identificador único da transação.
- `sender_id`: Chave estrangeira para a tabela `users`, indicando o usuário remetente.
- `receiver_id`: Chave estrangeira para a tabela `users`, indicando o usuário destinatário.
- `amount`: Valor da transação.
- `timestamps`: Data de criação e atualização do registro.


## Estrutura do Projeto

```plaintext
app/
├── Domains/
│   ├── Users/
│   │   ├── Entities/
│   │   │   ├── User.php
│   │   │   ├── CommonUser.php
│   │   │   └── MerchantUser.php
│   │   ├── Services/
│   │   │   ├── UserService.php
│   │   │   └── AuthenticationService.php
│   │   └── Repositories/
│   │       └── UserRepository.php
│   ├── Wallets/
│   │   ├── Entities/
│   │   │   └── Wallet.php
│   │   ├── Services/
│   │   │   └── WalletService.php
│   │   └── Repositories/
│   │       └── WalletRepository.php
│   ├── Transactions/
│   │   ├── Entities/
│   │   │   └── Transaction.php
│   │   ├── Services/
│   │   │   └── TransferService.php
│   │   └── Repositories/
│   │       └── TransactionRepository.php
│   ├── Notifications/
│   │   ├── Entities/
│   │   │   └── Notification.php
│   │   ├── Services/
│   │   │   └── NotificationService.php
│   │   └── Repositories/
│   │       └── NotificationRepository.php
│   └── Audits/
│       ├── Entities/
│       │   └── AuditLog.php
│       ├── Services/
│       │   └── AuditService.php
│       └── Repositories/
│           └── AuditRepository.php
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php
│   │   ├── WalletController.php
│   │   └── TransactionController.php
├── Providers/
│   └── AppServiceProvider.php
└── Services/
    └── Contracts/
        └── TransferServiceInterface.php
```


## Domínios

### Domínio de Usuários (User Domain)

Responsável por gerenciar informações e operações relacionadas aos usuários.

#### Entidades

- `User`: Entidade base para usuários.
- `CommonUser`: Representa os usuários comuns.
- `MerchantUser`: Representa os lojistas.

#### Serviços

- `UserService`: Gerenciamento de usuários.
- `AuthenticationService`: Autenticação e autorização.

### Domínio de Carteiras (Wallet Domain)

Gerencia as carteiras dos usuários, incluindo saldo e operações financeiras.

#### Entidades

- `Wallet`: Representa a carteira de um usuário.

#### Serviços

- `WalletService`: Gerenciamento de saldo.

### Domínio de Transações (Transaction Domain)

Responsável pelas operações de transferência de dinheiro entre os usuários.

#### Entidades

- `Transaction`: Representa uma transação financeira.

#### Serviços

- `TransferService`: Realiza transferências de dinheiro.

### Domínio de Notificações (Notification Domain)

Lida com a comunicação e notificações para os usuários.

#### Entidades

- `Notification`: Representa uma notificação.

#### Serviços

- `NotificationService`: Gerenciamento de notificações.

### Domínio de Autorização

Lida com os serviços de autorização de transação.

## Setup
  - Ajustar permissões da pasta storage
  - Criar database 'payit', usuário e ajustar .env com as credenciais.
  - Docker compose up

## Possíveis Melhorias Futuras
- Considerar a migração para um banco de dados mais adequado para operações financeiras, caso a concorrência seja um problema crescente.
- Avaliar a possibilidade de usar Event Sourcing para garantir consistência e auditabilidade em operações financeiras.
- Explorar o uso de Command Pattern e outros padrões de design para encapsular melhor as operações de negócios.