# PontoGestor

Sistema web de gerenciamento de funcionários e registro de ponto, desenvolvido com **Laravel 13**, **PHP 8.3+** e **MySQL**.

O PontoGestor permite que administradores gerenciem seus funcionários e acompanhem as marcações de ponto, enquanto funcionários registram entradas e saídas e gerenciam suas próprias credenciais.

O projeto foi desenvolvido como parte de um desafio técnico, com foco em organização de código, segurança, regras de negócio, testes automatizados e boas práticas de desenvolvimento.

## Funcionalidades

### Administrador

* Autenticação com controle de acesso por perfil.
* Cadastro de funcionários.
* Listagem de funcionários vinculados ao administrador autenticado.
* Edição de dados cadastrais.
* Alteração da senha de funcionários.
* Exclusão lógica de funcionários (*soft delete*).
* Consulta ao relatório de registros de ponto.
* Filtragem do relatório por período.
* Paginação das listagens.
* Preenchimento automático de endereço por CEP utilizando a API ViaCEP.

### Funcionário

* Autenticação individual.
* Dashboard com informações do funcionário.
* Registro de entrada e saída por meio de um único botão.
* Alternância automática entre os tipos de marcação.
* Consulta ao histórico pessoal de registros.
* Alteração da própria senha mediante confirmação da senha atual.

## Tecnologias utilizadas

| Tecnologia     | Finalidade                       |
| -------------- | -------------------------------- |
| PHP 8.3+       | Linguagem de programação         |
| Laravel 13     | Framework backend                |
| MySQL          | Banco de dados relacional        |
| Eloquent ORM   | Persistência e relacionamentos   |
| Blade          | Renderização das interfaces      |
| Tailwind CSS 4 | Estilização                      |
| Vite 8         | Compilação dos recursos frontend |
| JavaScript     | Integração com ViaCEP            |
| Pest 4         | Testes automatizados             |
| Laravel Pint   | Padronização do código PHP       |

## Arquitetura

O projeto utiliza uma organização em camadas:

**Controller → Request → Service → Repository**

### Controller

Recebe as requisições HTTP, delega o processamento às camadas apropriadas e retorna respostas ou redirecionamentos.

### Request

Centraliza as regras de validação e autorização das entradas fornecidas pelo usuário.

### Service

Concentra as regras de negócio e coordena as operações necessárias para executar cada funcionalidade.

### Repository

Responsável pelo acesso e pela persistência dos dados, utilizando Eloquent ou SQL puro, conforme a necessidade.

### Model

Representa as entidades do domínio, seus relacionamentos e conversões de atributos.

Essa separação busca facilitar a manutenção, a evolução do sistema e a implementação de testes.

## Requisitos

Antes de iniciar a instalação, tenha disponível:

* PHP 8.3 ou superior.
* Composer.
* MySQL.
* Node.js compatível com Vite 8.
* npm.
* Git.

Também é necessário habilitar as extensões PHP exigidas pelo Laravel e pelo driver MySQL.

## Instalação

### 1. Clonar o repositório

```bash
git clone https://github.com/Laiendes/ponto-gestor.git
cd ponto-gestor
```

### 2. Instalar as dependências PHP

```bash
composer install
```

### 3. Criar o arquivo de ambiente

No Git Bash:

```bash
cp .env.example .env
```

No Prompt de Comando do Windows:

```cmd
copy .env.example .env
```

### 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 5. Configurar o banco de dados

Crie um banco MySQL chamado:

```sql
CREATE DATABASE ponto_gestor
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Em seguida, configure as variáveis correspondentes no arquivo `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ponto_gestor
DB_USERNAME=root
DB_PASSWORD=
```

Altere o usuário e a senha conforme as configurações do seu ambiente.

### 6. Executar as migrations

```bash
php artisan migrate
```

Esse comando cria as tabelas necessárias para o funcionamento da aplicação.

### 7. Configurar o administrador inicial

O sistema utiliza um seeder para criar o primeiro administrador.

Configure as seguintes variáveis no `.env`:

```dotenv
ADMIN_NAME="Administrador Inicial"
ADMIN_EMAIL=admin@example.com
ADMIN_CPF=
ADMIN_PASSWORD=
```

Preencha:

* `ADMIN_NAME`: nome do administrador.
* `ADMIN_EMAIL`: e-mail utilizado no login.
* `ADMIN_CPF`: CPF válido com 11 dígitos.
* `ADMIN_PASSWORD`: senha com pelo menos 12 caracteres.

As credenciais não são definidas diretamente no código-fonte.

### 8. Executar o seeder

```bash
php artisan db:seed
```

O seeder valida os dados informados e cria o administrador inicial.

Caso o mesmo administrador já exista, a execução não cria um registro duplicado.

### 9. Instalar as dependências frontend

```bash
npm ci
```

### 10. Compilar os recursos frontend

```bash
npm run build
```

## Executando a aplicação

Inicie o servidor Laravel:

```bash
php artisan serve
```

A aplicação ficará disponível, por padrão, em:

`http://127.0.0.1:8000`

Para desenvolver com atualização automática dos recursos frontend, execute em outro terminal:

```bash
npm run dev
```

Acesse a aplicação utilizando as credenciais do administrador configuradas anteriormente.

## Como utilizar

### Fluxo do administrador

1. Faça login com a conta administrativa.
2. Acesse a área de funcionários.
3. Cadastre um funcionário, informando seus dados pessoais, profissionais e endereço.
4. Utilize o CEP para preencher automaticamente os campos de endereço.
5. Consulte, edite ou exclua logicamente funcionários.
6. Acesse o relatório de ponto para visualizar as marcações realizadas.

### Fluxo do funcionário

1. Faça login utilizando as credenciais fornecidas pelo administrador.
2. Acesse a página de registro de ponto.
3. Clique em **Registrar entrada** para realizar a primeira marcação.
4. Na próxima operação, o botão será apresentado como **Registrar saída**.
5. Consulte o histórico das marcações realizadas.
6. Utilize a página de alteração de senha para atualizar suas credenciais.

## Registro de ponto

O sistema identifica automaticamente o tipo da próxima marcação a partir do último registro do funcionário.

A sequência esperada é:

| Marcação | Tipo    |
| -------- | ------- |
| 1ª       | Entrada |
| 2ª       | Saída   |
| 3ª       | Entrada |
| 4ª       | Saída   |

O usuário não informa manualmente o tipo nem a data da marcação.

A data e o horário são definidos pelo sistema no momento do registro.

A operação utiliza transação no banco de dados e bloqueio de linha para proteger a consistência da alternância em requisições concorrentes.

## Relatório administrativo

O relatório de ponto foi implementado utilizando **SQL puro**, conforme o requisito do desafio técnico.

A consulta utiliza relacionamentos entre funcionários e administradores para apresentar:

* Identificador do registro de ponto.
* Nome do funcionário.
* Cargo.
* Idade.
* Nome do administrador responsável.
* Data e horário da marcação, incluindo segundos.

O relatório permite filtrar os registros por data inicial e final.

A consulta também restringe os resultados aos funcionários vinculados ao administrador autenticado.

Os registros de funcionários excluídos logicamente permanecem disponíveis no histórico administrativo.

## Testes automatizados

O projeto utiliza **Pest 4** para os testes automatizados.

Os testes cobrem funcionalidades como:

* Autenticação e logout.
* Controle de acesso por perfil.
* Cadastro, listagem, edição e exclusão lógica de funcionários.
* Validação de CPF.
* Registro de entrada e saída.
* Alternância automática das marcações.
* Relatório administrativo.
* Filtros por período.
* Alteração de senha.
* Integridade dos relacionamentos.

### Configuração do banco de testes

Os testes utilizam um banco MySQL separado do banco principal.

Crie o banco:

```sql
CREATE DATABASE ponto_gestor_test
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Configure um arquivo `.env.testing` com as variáveis necessárias, incluindo:

```dotenv
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ponto_gestor_test
DB_USERNAME=root
DB_PASSWORD=
```

Ajuste as credenciais conforme seu ambiente.

**Atenção:** o banco de testes deve ser exclusivo para essa finalidade, pois a suíte utiliza mecanismos que recriam as estruturas necessárias durante a execução.

### Executar os testes

```bash
php artisan test
```

Na última validação realizada durante o desenvolvimento:

**114 testes passaram, totalizando 294 assertions.**

## Padronização do código

O projeto utiliza Laravel Pint para manter a consistência do código PHP.

No Windows:

```cmd
vendor\bin\pint
```

Em ambientes Unix:

```bash
./vendor/bin/pint
```

Para executar apenas a verificação de formatação:

```bash
./vendor/bin/pint --test
```

## Segurança

O projeto implementa medidas de segurança como:

* Autenticação baseada em sessão.
* Controle de acesso por perfil.
* Middleware de autorização.
* Validação dos dados recebidos.
* Verificação dos dígitos do CPF.
* Restrições de unicidade para CPF e e-mail.
* Armazenamento de senhas utilizando hash.
* Verificação da senha atual antes de permitir sua alteração.
* Proteção CSRF nos formulários.
* Restrição de acesso aos funcionários pertencentes ao administrador autenticado.
* Exclusão lógica para preservar o histórico de registros.

## Integração ViaCEP

O cadastro e a edição de funcionários possuem integração com a API ViaCEP.

Ao informar um CEP válido, o sistema consulta o serviço e preenche automaticamente:

* Logradouro.
* Bairro.
* Cidade.
* Estado.

O número e o complemento podem ser informados manualmente.

A integração utiliza JavaScript e está organizada em um arquivo próprio, separado das views Blade.

## Decisões técnicas

### Separação em camadas

A organização Controller → Request → Service → Repository foi adotada para separar responsabilidades e facilitar a manutenção.

### SQL puro no relatório

A consulta administrativa utiliza SQL puro para atender ao requisito específico do desafio e demonstrar o uso de consultas relacionais com JOIN.

### Exclusão lógica

Funcionários são excluídos logicamente para preservar a integridade e o histórico de marcações.

### Controle de acesso

Administradores e funcionários possuem áreas e permissões distintas, verificadas no backend.

### Registro automático

A alternância entre entrada e saída é determinada pelo sistema, evitando que o funcionário escolha manualmente o tipo de marcação.

### Testes automatizados

A suíte de testes foi utilizada para validar as principais regras de negócio, permissões e operações de persistência.

---

**PontoGestor — Projeto desenvolvido como desafio técnico utilizando PHP, Laravel e MySQL.**
