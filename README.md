# Ferroramas — Sistema Web para Gerenciamento de Operações Ferroviárias em Tempo Real

## Sobre o projeto

O Ferroramas é um sistema web desenvolvido para auxiliar no gerenciamento e monitoramento de operações ferroviárias em tempo real.

A proposta do sistema é centralizar informações relacionadas a trens, sensores, rotas, usuários, alertas, histórico de dados e relatórios em uma única plataforma, facilitando o acompanhamento das operações e a visualização das informações.

O sistema possui diferentes níveis de acesso, permitindo que as funcionalidades sejam disponibilizadas de acordo com o tipo de usuário, como administrador e funcionário.

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

## Requisitos Funcionais

- **RF1 Fazer Login** — o sistema deve permitir que usuários cadastrados realizem login utilizando suas credenciais de acesso.
- **RF2 Cadastrar Sensores** — o sistema deve permitir o cadastro de sensores, registrando suas informações de identificação e características.
- **RF3 Cadastrar Trens** — o sistema deve permitir o cadastro de trens, registrando suas informações de identificação e características.
- **RF4 Cadastrar Usuários** — o sistema deve permitir o cadastro de usuários, registrando suas informações pessoais e credenciais de acesso.
- **RF5 Cadastrar Rotas** — o sistema deve permitir o cadastro de rotas, registrando origem, destino e demais dados necessários.
- **RF6 Gerar Relatório** — o sistema deve permitir a geração de relatórios com base nos dados registrados e coletados.
- **RF7 Editar Sensores** — o sistema deve permitir a edição das informações dos sensores cadastrados.
- **RF8 Editar Trens** — o sistema deve permitir a edição das informações dos trens cadastrados.
- **RF9 Editar Usuários** — o sistema deve permitir a edição das informações dos usuários cadastrados.
- **RF10 Editar Rotas** — o sistema deve permitir a edição das informações das rotas cadastradas.
- **RF11 Excluir Sensores** — desde que não existam registros associados que impeçam sua remoção.
- **RF12 Excluir Trens** — desde que não existam registros associados que impeçam sua remoção.
- **RF13 Excluir Usuários** — de acordo com suas permissões de acesso.
- **RF14 Excluir Rotas** — desde que não existam registros associados que impeçam sua remoção.
- **RF15 Visualizar Sensores** — informações cadastradas e dados de monitoramento.
- **RF16 Visualizar Trens** — informações cadastradas e dados de monitoramento.
- **RF17 Visualizar Usuários** — de acordo com suas permissões de acesso.
- **RF18 Visualizar Rotas** — rotas cadastradas e suas respectivas informações.
- **RF19 Visualizar Relatórios** — relatórios gerados a partir dos dados registrados.
- **RF20 Consultar Histórico** — dados coletados pelos sensores (data, horário, sensor e trem associado).
- **RF21 Encerrar Sessão** — permitir que o usuário encerre sua sessão de acesso.
- **RF22 Visualizar Dashboard** — trens ativos, sensores ativos, alertas, rotas, localização dos trens e demais indicadores.
- **RF23 Vincular Sensores** — vínculo de sensores aos trens e rotas correspondentes.
- **RF24 Consultar Alertas** — visualização dos alertas registrados pelo sistema.
- **RF25 Controlar Acesso por Tipo de Usuário** — diferenciando permissões entre funcionário e administrador.
- **RF26 Validar Dados dos Formulários** — impedindo o envio de informações inválidas ou incompletas.
- **RF27 Visualizar Localização dos Trens** — no sistema de monitoramento.
- **RF28 Visualizar Dados de Monitoramento dos Trens** — velocidade e demais dados coletados pelos sensores.

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
- **Bootstrap 5.3** — grid responsivo, modais, tabelas e botões
- **Ionicons 7.1** — ícones da interface
- **Open-Meteo** — API de clima exibida no dashboard
- **ViaCEP** — API que preenche o endereço a partir do CEP no cadastro de usuários
