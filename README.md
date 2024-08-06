# Pay-It

O Pay-it é uma plataforma de pagamentos que permite depósitos e transferências de dinheiro entre usuários. A aplicação é organizada utilizando Domain-Driven Design (DDD) para garantir escalabilidade, modularidade e manutenabilidade.

## Índice

- [Recursos](#recursos)
- [SLA](#sla)
- [Modelos](#modelos)
- [Scaling strategy](#scaling-strategy)
- [Estrutura de Banco de Dados](#estrutura-de-banco-de-dados)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Domínios](#domínios)
  - [Domínio de Usuários (User Domain)](#domínio-de-usuários-user-domain)
  - [Domínio de Carteiras (Wallet Domain)](#domínio-de-carteiras-wallet-domain)
  - [Domínio de Transações (Transaction Domain)](#domínio-de-transações-transaction-domain)
  - [Domínio de Notificações (Notification Domain)](#domínio-de-notificações-notification-domain)
  - [Domínio de Auditoria (Audit Domain)](#domínio-de-auditoria-audit-domain)

## Recursos

- Cadastro de usuários com nome completo, CPF, e-mail e senha. CPF e e-mail devem ser únicos no sistema.
- Transferências de dinheiro entre usuários comuns e lojistas.
- Lojistas só podem receber dinheiro, não enviar. Apenas usuários comuns podem enviar dinheiro.
- Validação de saldo antes da transferência.
- Notificações de transações.
- Auditoria de operações.

## SLA

- Availability: 99%
- Accuracy: 100%
- Capacity: 1k RPS
- Latency: p95 = 0.8s, p99 = 3s , max= 5s

## scaling strategy
- Deve ser construido de modo a escalar verticalmente.

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

### Domínio de Auditoria (Audit Domain)

Mantém o registro de todas as operações e alterações realizadas no sistema para fins de auditoria e segurança.

#### Entidades

- `AuditLog`: Representa um registro de auditoria.

#### Serviços

- `AuditService`: Gerenciamento de auditoria.
