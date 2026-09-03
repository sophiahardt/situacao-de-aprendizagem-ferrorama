# Ferroramas — Sistema Web para Gerenciamento de Operações Ferroviárias em Tempo Real

## Sobre o projeto

O **Ferroramas** é um sistema web desenvolvido para auxiliar no gerenciamento e monitoramento de operações ferroviárias em tempo real.

A proposta do sistema é centralizar informações relacionadas a trens, sensores, rotas, usuários, alertas, histórico de dados e relatórios em uma única plataforma, facilitando o acompanhamento das operações e a visualização das informações.

O sistema possui diferentes níveis de acesso, permitindo que as funcionalidades sejam disponibilizadas de acordo com o tipo de usuário, como **administrador** e **funcionário**.

O projeto está sendo desenvolvido como parte da Situação de Aprendizagem Ferroramas, utilizando tecnologias voltadas ao desenvolvimento web e banco de dados.

---

## Objetivo do sistema

O objetivo do Ferroramas é desenvolver um sistema web simples, organizado e eficiente para auxiliar no gerenciamento das operações ferroviárias.

O sistema busca:

- Centralizar informações sobre trens, sensores e rotas;
- Permitir o cadastro, visualização, edição e exclusão de informações;
- Monitorar dados relacionados aos trens e sensores;
- Apresentar informações como velocidade, localização e status dos trens;
- Permitir a consulta de alertas e do histórico de dados coletados;
- Possibilitar a geração e visualização de relatórios;
- Controlar o acesso às funcionalidades de acordo com o tipo de usuário;
- Auxiliar na identificação de falhas e ocorrências;
- Facilitar a tomada de decisões baseada em dados;
- Contribuir para maior controle e segurança das operações ferroviárias.

---

##  Requisitos Funcionais

 RF1   Fazer Login — o sistema deve permitir que usuários cadastrados realizem login utilizando suas credenciais de acesso. 
 <br>
 RF2   Cadastrar Sensores — o sistema deve permitir o cadastro de sensores, registrando suas informações de identificação e características.
 <br> 
 RF3 
   Cadastrar Trens — o sistema deve permitir o cadastro de trens, registrando suas informações de identificação e características. 
<br>
 RF4   Cadastrar Usuários — o sistema deve permitir o cadastro de usuários, registrando suas informações pessoais e credenciais de acesso.
 <br>
 RF5   Cadastrar Rotas — o sistema deve permitir o cadastro de rotas, registrando origem, destino e demais dados necessários.
 <br>
 RF6   Gerar Relatório — o sistema deve permitir a geração de relatórios com base nos dados registrados e coletados.
 <br>
 RF7   Editar Sensores — o sistema deve permitir a edição das informações dos sensores cadastrados.
 <br>
 RF8   Editar Trens — o sistema deve permitir a edição das informações dos trens cadastrados.
 <br>
 RF9   Editar Usuários — o sistema deve permitir a edição das informações dos usuários cadastrados.
 <br>
 RF10  Editar Rotas — o sistema deve permitir a edição das informações das rotas cadastradas.
 <br>
 RF11  Excluir Sensores — desde que não existam registros associados que impeçam sua remoção.
 <br>
 RF12  Excluir Trens — desde que não existam registros associados que impeçam sua remoção.
 <br>
 RF13  Excluir Usuários — de acordo com suas permissões de acesso.
 <br>
 RF14  Excluir Rotas — desde que não existam registros associados que impeçam sua remoção.
 <br>
 RF15  Visualizar Sensores — informações cadastradas e dados de monitoramento.
 <br>
 RF16  Visualizar Trens — informações cadastradas e dados de monitoramento.
 <br>
 RF17  Visualizar Usuários — de acordo com suas permissões de acesso.
 <br>
 RF18  Visualizar Rotas — rotas cadastradas e suas respectivas informações.
 <br>
 RF19  Visualizar Relatórios — relatórios gerados a partir dos dados registrados.
 <br>
 RF20  Consultar Histórico — dados coletados pelos sensores (data, horário, sensor e trem associado).
 <br>
 RF21  Encerrar Sessão — permitir que o usuário encerre sua sessão de acesso.
 <br>
 RF22  Visualizar Dashboard — trens ativos, sensores ativos, alertas, rotas, localização dos trens e demais indicadores.
 <br>
 RF23  Vincular Sensores — vínculo de sensores aos trens e rotas correspondentes.
 <br>
 RF24  Consultar Alertas — visualização dos alertas registrados pelo sistema.
 <br>
 RF25  Controlar Acesso por Tipo de Usuário — diferenciando permissões entre funcionário e administrador.
 <br>
 RF26  Validar Dados dos Formulários — impedindo o envio de informações inválidas ou incompletas.
 <br>
 RF27  Visualizar Localização dos Trens — no sistema de monitoramento.
 <br>
 RF28  Visualizar Dados de Monitoramento dos Trens — velocidade e demais dados coletados pelos sensores.


---

## Tecnologias utilizadas

- **HTML5** — estruturação das páginas
- **CSS3** — estilização e identidade visual
- **JavaScript** — validações e interações da interface
- **PHP** — desenvolvimento da lógica e integração com o banco de dados
- **MySQL** — armazenamento e gerenciamento dos dados
- **Apache** — servidor utilizado para execução do projeto localmente
- **XAMPP** — ambiente utilizado para execução do Apache e MySQL
- **Visual Studio Code** — editor de código utilizado pela equipe
- **GitHub** — versionamento e armazenamento do código-fonte
- **GitHub Projects** — organização e acompanhamento das tarefas (Kanban)

---

## Integrantes da equipe

**Sophia Lara Hardt** Líder do projeto 
<br>
**Isabella Balas Rech** 
<br>
**Brayan Anzini**  
<br>
**André De Quadros Goudinho** 

---

## Metodologia de Desenvolvimento

Para a organização do desenvolvimento do **Ferroramas**, a equipe utiliza uma abordagem baseada em **Scrum** e **Kanban**:

* **Scrum:** Utilizado como referência para organizar o desenvolvimento em etapas, definir prioridades e acompanhar a evolução das atividades.
* **Kanban:** Por meio do **GitHub Projects**, é utilizado para visualizar e controlar o andamento das tarefas. Os cards são organizados de acordo com o status de desenvolvimento, permitindo identificar o que já foi concluído, o que está em andamento, o que está em revisão e o que ainda precisa ser desenvolvido.

### Estrutura das Tarefas no Kanban
Cada tarefa do Kanban deve apresentar, sempre que aplicável:

- **Descrição da ação**
- **Responsável pela execução**
- **Requisito Funcional relacionado**
- **Status da atividade**
- **Prioridade da tarefa**

---

## Pesquisa Visual e Identidade do Projeto

### Identidade Visual do Projeto
A identidade visual do **Sistema Ferroviário** foi desenvolvida de forma colaborativa pela equipe, com o objetivo de transmitir **modernidade e eficiência** aos usuários.

Buscamos adotar um design prático, simples e intuitivo, priorizando a experiência do usuário (UX):
* **Telas de Login e Cadastro:** Apresentam fundo branco com texto em preto, garantindo a clareza na escrita e fácil leitura.
* **Elemento Visual Temático:** Na tela de login, utilizamos a imagem de um trem para representar visualmente o sistema e reforçar sua temática.
* **Navegação:** Aplicamos botões que combinam textos em negrito e ícones, facilitando a navegação e tornando a interface mais simples.

### Paleta de Cores

 **Branco** - Se destaca sobre o azul, proporcionando melhor legibilidade. 
 <br>
 **Azul & Branco** - Transmitem segurança e confiabilidade ao usuário. 
 <br>
 **Vermelho** - Utilizado para alertas, avisos e ações críticas. 
 <br>
 **Verde** - Indica elementos ativos ou em funcionamento, contribuindo para uma comunicação visual clara e eficiente. 

---

## Outras Informações

O **Ferroramas** está sendo desenvolvido de forma colaborativa utilizando **GitHub** para versionamento de código e **GitHub Projects** para gerenciamento das atividades.

* **Qualidade de Código:** Durante o desenvolvimento, a equipe realiza revisões do código, testes das funcionalidades e organização contínua da estrutura do projeto, buscando manter os padrões definidos e garantir a integração entre as diferentes partes do sistema.
* **Desenvolvimento Incremental:** O projeto será desenvolvido em etapas progressivas:
  1. Desenvolvimento das interfaces (Front-end);
  2. Criação e integração do banco de dados;
  3. Implementação das funcionalidades em **PHP**;
  4. Validações e testes do sistema;
  5. Integração dos dados de monitoramento.