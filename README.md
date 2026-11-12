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

## Problema que o sistema busca resolver

- A falta de uma plataforma centralizada para acompanhar as operações ferroviárias pode dificultar o controle de trens, sensores e rotas. Também pode tornar mais difícil identificar falhas, acompanhar informações e consultar ocorrências.
- O Ferroramas foi desenvolvido para reunir essas informações em um único sistema, facilitando o monitoramento e o gerenciamento das operações ferroviárias.

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

## Estrutura geral do projeto

Os principais diretórios e arquivos estão organizados da seguinte forma:

```text
situacao-de-aprendizagem-ferrorama/
├── assets/
│   └── img/
├── database/
│   └── db.sql
├── doc/
│   ├── pesquisa-visual.md
│   └── ...
├── infra/
│   ├── conexao.php
│   └── protecao.php
├── public/
│   ├── gerar-senha.php
│   ├── tela-login.php
│   ├── tela-geral-home.php
│   ├── trem/
│   ├── sensor/
│   ├── rota/
│   ├── relatorio/
│   └── user/
├── script/
├── style/
│   └── style.css
├── index.php
└── README.md
```

## Perfis de acesso e permissões

O sistema possui dois perfis de acesso:

- **Administrador:** acesso às funcionalidades administrativas, incluindo o gerenciamento de usuários e ações que exigem permissão administrativa.
- **Funcionário:** acesso às funcionalidades operacionais disponíveis para seu perfil, respeitando as restrições definidas pelo sistema.

As permissões são verificadas durante o uso do sistema. Algumas ações também dependem dos vínculos existentes entre usuários, trens, sensores e rotas.

## Funcionalidades implementadas

- **Login e controle de acesso:** login com e-mail e senha (senhas guardadas com `password_hash`), sessão por tipo de usuário e página de acesso negado. Ao sair, o usuário volta para a Landing Page.
- **Landing Page:** apresentação do sistema, com acesso ao login.
- **Dashboard:** clima atual da localização do usuário, atalhos para cada módulo, resumo da operação (quantidade de trens, rotas e sensores) e alertas de monitoramento.
- **Sensores, trens, rotas e usuários:** listagem com cadastro e edição em modais (janelas sobre a própria listagem) e exclusão com confirmação.
- **Relatórios:** listagem, cadastro, edição e exclusão.
- **Validações:** campos obrigatórios, e-mail, telefone, CEP e UF no cadastro de usuários; valores numéricos em trens e rotas; e-mail repetido não é aceito.
- **Busca de CEP:** ao digitar o CEP no cadastro de usuário, o endereço é preenchido automaticamente.

## Relação entre usuário, trem e rota

- O sistema segue a ordem usuário → trem → rota: cada trem tem um usuário responsável, e cada trem anda em uma rota (uma rota pode ter vários trens).
- O trem é cadastrado sem rota; o vínculo é feito no cadastro ou na edição da rota, escolhendo o trem que anda nela.
- Um trem não pode ser excluído enquanto estiver associado a uma rota, nem enquanto tiver sensores.
- Ao excluir uma rota, os trens dela ficam sem rota (desde que a rota não tenha sensores).
- Um usuário não pode ser excluído enquanto for responsável por algum trem, e o único administrador do sistema não pode ser excluído.

## Requisitos necessários para execução

- Computador com Windows ou outro sistema compatível com o XAMPP;
- XAMPP instalado;
- Apache e MySQL/MariaDB;
- Navegador atualizado, como Google Chrome, Microsoft Edge ou Firefox;
- Conexão com a internet para carregar bibliotecas externas e utilizar as APIs Open-Meteo e ViaCEP.

O Visual Studio Code é recomendado para visualizar e editar os arquivos do projeto, mas não é obrigatório para executar a aplicação.

## Como executar o projeto

1. Instale o XAMPP e inicie o Apache e o MySQL.
2. Coloque a pasta do projeto dentro de `C:\xampp\htdocs\`.
3. Crie o banco de dados executando o arquivo `database/db.sql` (por exemplo, na aba SQL do phpMyAdmin). Ele cria o banco `db_ferrovia`, as tabelas e os cargos Administrador e Funcionário.
4. Confira os dados de conexão em `infra/conexao.php`. O projeto está configurado para o MySQL na porta 3307; se o seu MySQL usa a porta padrão, troque para 3306.
5. Crie o primeiro administrador:
   - acesse `http://localhost/situacao-de-aprendizagem-ferrorama/public/gerar-senha.php`, que mostra o hash da senha definida no arquivo;
   - no phpMyAdmin, insira o usuário com esse hash:

   ```sql
   INSERT INTO usuario (id_cargo, nome, email, senha_hash)
   VALUES (1, 'Administrador', 'admin@email.com', 'COLE_O_HASH_AQUI');
   ```

6. Acesse `http://localhost/situacao-de-aprendizagem-ferrorama/` para abrir a Landing Page e clique em **Acessar o sistema**.

## Integrantes da equipe

- **Sophia Lara Hardt:** liderança do projeto, organização das atividades e participação nas melhorias da página inicial.
- **Isabella Balas Rech:** participação no desenvolvimento do sistema e nas atividades da equipe.
- **Brayan Anzini:** desenvolvimento da tela de Dashboard e participação na integração das funcionalidades.
- **André De Quadros Goudinho:** participação nos ajustes de estilização e organização visual do sistema.

## Metodologia de Desenvolvimento

Para a organização do desenvolvimento do Ferroramas, a equipe utiliza uma abordagem baseada em Scrum e Kanban:

- **Scrum:** Utilizado como referência para organizar o desenvolvimento em etapas, definir prioridades e acompanhar a evolução das atividades.
- **Kanban:** Por meio do GitHub Projects, é utilizado para visualizar e controlar o andamento das tarefas. Os cards são organizados de acordo com o status de desenvolvimento, permitindo identificar o que já foi concluído, o que está em andamento, o que está em revisão e o que ainda precisa ser desenvolvido.

### Estrutura das Tarefas no Kanban

Cada tarefa do Kanban deve apresentar, sempre que aplicável:

- Descrição da ação
- Responsável pela execução
- Requisito Funcional relacionado
- Status da atividade
- Prioridade da tarefa

## Pesquisa Visual e Identidade do Projeto

A identidade visual do Sistema Ferroviário foi desenvolvida de forma colaborativa pela equipe, com o objetivo de transmitir modernidade e eficiência aos usuários.

Buscamos adotar um design prático, simples e intuitivo, priorizando a experiência do usuário (UX).

O processo completo, com referências, justificativas, problemas encontrados e testes, está em `doc/pesquisa-visual.md`.

### Resumo

- **Cores:** harmonia monocromática em azul-marinho, que transmite confiança, segurança e tecnologia. Cada tom de azul tem uma função (fundo escuro, cor principal, títulos, destaque, detalhes) e está definido como variável no início do `style/style.css`.
- **Estados:** vermelho para erros, exclusão e alertas críticos; amarelo para atenção; verde para sucesso; azul para informação. Os estados também usam texto e ícone, para não depender só da cor.
- **Tipografia:** Arial em todo o sistema, com títulos em negrito em todas as telas.
- **Ícones:** Ionicons, sempre o mesmo ícone para a mesma funcionalidade. Na coluna Ações das tabelas, os botões usam só o ícone do lápis (editar) e da lixeira (excluir).
- **Login e Landing Page:** usam a mesma foto e o mesmo degradê azul.
- **Tabelas:** as colunas de ID (ou Nome) e de Ações têm posição e largura fixas em todas as listagens.
- **Contraste:** as combinações de texto e fundo foram testadas pelos critérios do WCAG (nível AA).

### Paleta principal

| Cor | HEX | Uso |
|---|---|---|
| Azul-noite | `#071A3D` | Fundos escuros, painel do login |
| Azul-marinho | `#151D4B` | Navbar, botões principais, cabeçalho das tabelas |
| Azul profundo | `#0A2A66` | Títulos e botão Editar |
| Azul de destaque | `#1666E8` | Ícones, links e botão Entrar |
| Azul-claro | `#6FB1FF` | Detalhes sobre fundos escuros |
| Fundo claro | `#F5F8FC` | Fundo das páginas |
| Vermelho | `#DC3545` | Erros, exclusão e alertas críticos |
| Amarelo | `#F5B400` | Alertas de atenção |

## Outras Informações

O Ferroramas está sendo desenvolvido de forma colaborativa utilizando GitHub para versionamento de código e GitHub Projects para gerenciamento das atividades.

**Qualidade de Código:** Durante o desenvolvimento, a equipe realiza revisões do código, testes das funcionalidades e organização contínua da estrutura do projeto, buscando manter os padrões definidos e garantir a integração entre as diferentes partes do sistema.

**Desenvolvimento Incremental:** O projeto será desenvolvido em etapas progressivas:

1. Desenvolvimento das interfaces (Front-end);
2. Criação e integração do banco de dados;
3. Implementação das funcionalidades em PHP;
4. Validações e testes do sistema;
5. Integração dos dados de monitoramento.

## Imagens da versão final

Esta seção será preenchida com capturas de tela das principais páginas do sistema. Os arquivos das imagens ainda não estão disponíveis no repositório; quando adicionados, poderão ser organizados em `assets/img/` com estes nomes:

- Landing Page: `landing-page.png`
- Login: `login.png`
- Dashboard: `dashboard.png`
- Trens: `trens.png`
- Sensores: `sensores.png`
- Rotas: `rotas.png`
- Relatórios: `relatorios.png`

## Limitações conhecidas

- A execução do sistema depende de um servidor web e de um banco de dados configurados corretamente.
- As informações de clima dependem da disponibilidade da API Open-Meteo e de uma conexão com a internet.
- A consulta automática de CEP depende da disponibilidade da API ViaCEP.
- O monitoramento depende dos dados disponíveis e registrados pelos sensores no sistema.
- O projeto pode receber melhorias e novas funcionalidades em versões futuras.
     