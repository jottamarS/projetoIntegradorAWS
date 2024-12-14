# Dashboard para Salão de Cabeleireiro

## Descrição
Este projeto é um sistema de gerenciamento desenvolvido para um salão de cabeleireiro. Ele foi implementado em **PHP** e visa facilitar o processo de agendamento de serviços, gerenciamento de clientes e controle de serviços prestados no salão.  

O sistema possui um conjunto completo de funcionalidades para administração, incluindo a possibilidade de cadastrar, editar, validar e pesquisar informações sobre clientes e serviços, além de aprovar ou recusar agendamentos.  

O projeto foi desenvolvido para ser hospedado na nuvem, especificamente na **AWS (Amazon Web Services)**, com um banco de dados que armazena todas as informações relacionadas aos agendamentos, clientes e serviços.

---

## Funcionalidades
- **Cadastro de Clientes:** O administrador pode cadastrar novos clientes no sistema, incluindo dados como nome, telefone, e-mail e outras informações relevantes.
- **Agendamentos:** Os clientes podem agendar serviços, e o administrador pode aprovar ou recusar esses agendamentos.
- **Cadastro e Edição de Serviços:** O administrador pode cadastrar novos serviços (ex.: corte de cabelo, manicure) e editar os existentes.
- **Validação de Serviços:** Antes de um serviço ser oferecido, ele pode ser validado pelo administrador.
- **Pesquisa de Clientes e Agendamentos:** Funcionalidade de pesquisa rápida para localizar clientes e visualizar os agendamentos realizados.
- **Interface de Administração:** Uma interface simples em HTML, onde o administrador pode gerenciar todas as funcionalidades acima.

---

## Tecnologias Utilizadas
- **Backend:** PHP  
- **Frontend:** HTML, CSS (utilizado para criação da interface de administração)  
- **Banco de Dados:** MySQL  
- **Serviços de Nuvem:** AWS (Amazon Web Services)  
- **Servidor Web:** Apache  

---

## Estrutura do Projeto

```yaml
/ (raiz do projeto)
├── /assets/                # Arquivos estáticos (CSS, JS, imagens)
├── /config/                # Arquivos de configuração (ex: conexões com banco)
├── /controllers/           # Arquivos PHP que gerenciam a lógica do sistema
├── /models/                # Modelos PHP (representações das tabelas do banco de dados)
├── /views/                 # Arquivos HTML, exibição de interfaces
├── /index.php              # Página principal do sistema (dashboard)
└── /db/                    # Arquivos relacionados ao banco de dados


---

## Instalação

### Requisitos
- PHP 7.4 ou superior
- MySQL ou MariaDB
- Apache ou servidor compatível com PHP
- Conta na AWS para hospedagem

### Passo a Passo para Instalação

1. **Clone o repositório** ou faça o download dos arquivos do projeto.

2. **Configuração do Banco de Dados**:
   - Crie um banco de dados no MySQL ou MariaDB.
   - Importe o script de criação do banco de dados (caso exista) para configurar as tabelas.
   - Configure a conexão com o banco de dados no arquivo `config/db_config.php`.

3. **Configuração do Servidor na AWS**:
   - Faça o upload dos arquivos para o servidor EC2 na AWS.
   - Certifique-se de que o Apache e o PHP estão corretamente configurados e funcionando.
   - Configure o banco de dados MySQL na AWS (RDS ou instância própria) e garanta que a conexão com o servidor web esteja funcionando.

4. **Configuração do Frontend**:
   - A interface foi criada utilizando HTML. Todos os arquivos relacionados ao frontend estão na pasta `/views/`.

---

## Acesso ao Sistema
Após a configuração do servidor, acesse o sistema através do navegador, utilizando o IP ou domínio da instância AWS:  
http://<seu-endereco-ip-ou-dominio>/index.php


---

## Funcionalidades Detalhadas

### Cadastro e Pesquisa de Clientes
- O administrador pode cadastrar novos clientes com informações básicas.
- Também é possível realizar buscas rápidas pelos clientes cadastrados.

### Agendamentos
- Os clientes podem agendar serviços.
- O administrador tem a opção de aprovar ou recusar esses agendamentos diretamente na interface.

### Serviços
- O administrador pode cadastrar e editar os serviços disponíveis no salão.
- Cada serviço pode ter uma descrição, preço e duração.

### Painel de Controle
- O painel de controle exibe um resumo das informações do sistema, como agendamentos pendentes e serviços disponíveis.

