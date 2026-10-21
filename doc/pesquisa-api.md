# APIs: conceitos, comunicação entre sistemas e REST

## 1. O que é uma API e qual é sua finalidade

**API** (*Application Programming Interface*, ou Interface de Programação de Aplicações) é um conjunto de regras e definições que permite que um software se comunique com outro. Ela expõe funcionalidades e dados de um sistema de forma controlada, sem que quem a utiliza precise conhecer como o sistema funciona internamente.

Uma analogia comum é a de um restaurante: o cliente (aplicação consumidora) faz o pedido ao garçom (API), que leva a solicitação à cozinha (sistema que fornece o serviço) e devolve o prato pronto (resposta). O cliente não precisa saber como a cozinha trabalha.

### Finalidades principais

- **Integração entre sistemas:** permite que aplicações diferentes, escritas em linguagens distintas, troquem informações (ex.: um e-commerce consultando a API de uma transportadora para calcular o frete).
- **Abstração e encapsulamento:** esconde a complexidade interna e expõe apenas o necessário.
- **Reutilização:** a mesma API pode atender site, aplicativo mobile e outros serviços.
- **Segurança e controle de acesso:** centraliza autenticação, autorização e limites de uso.
- **Escalabilidade e modularidade:** facilita dividir sistemas em serviços menores (arquitetura de microsserviços).

### Exemplos do cotidiano

- Login com conta Google ou GitHub em outros sites.
- Consulta de endereço pelo CEP (ex.: ViaCEP).
- Pagamentos online via gateways como Stripe ou Mercado Pago.
- Aplicativos de clima que consultam serviços meteorológicos.

---

## 2. Como funciona a comunicação entre sistemas por meio de APIs

A comunicação segue, na maioria dos casos, o modelo **cliente–servidor** com o padrão **requisição–resposta** (*request–response*):
1. O **cliente** (aplicação consumidora) envia uma **requisição** a um endereço (*endpoint*) da API.
2. O **servidor** (que hospeda a API) recebe, valida, processa a requisição e consulta o que for necessário (banco de dados, outros serviços).
3. O servidor devolve uma **resposta** com um código de status e, normalmente, dados em um formato padronizado.

```text
┌─────────┐   1. Requisição HTTP    ┌─────────┐        ┌──────────────┐
│ Cliente │ ──────────────────────► │   API   │ ─────► │ Banco/outros │
│ (app)   │ ◄────────────────────── │ (server)│ ◄───── │   sistemas   │
└─────────┘   3. Resposta (JSON)    └─────────┘        └──────────────┘
                                           2. Processamento
```

### Elementos de uma requisição HTTP

| Elemento | Função | Exemplo |
|---|---|---|
| **URL / Endpoint** | Identifica o recurso acessado | `https://api.exemplo.com/usuarios/42` |
| **Método** | Indica a ação desejada | `GET`, `POST`, `PUT`, `PATCH`, `DELETE` |
| **Cabeçalhos (headers)** | Metadados da requisição | `Authorization`, `Content-Type`, `Accept` |
| **Corpo (body)** | Dados enviados ao servidor (em `POST`, `PUT`, `PATCH`) | `{"nome": "Ana"}` |
| **Parâmetros (query string)** | Filtros, paginação, ordenação | `?pagina=2&limite=10` |

### Elementos de uma resposta HTTP

- **Código de status:** resultado da operação.
  - `2xx` – sucesso (`200 OK`, `201 Created`, `204 No Content`)
  - `3xx` – redirecionamento
  - `4xx` – erro do cliente (`400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`)
  - `5xx` – erro do servidor (`500 Internal Server Error`, `503 Service Unavailable`)
- **Cabeçalhos:** metadados da resposta (ex.: `Content-Type: application/json`).
- **Corpo:** os dados retornados, geralmente em **JSON** (também podem ser XML, entre outros).

### Exemplo prático

**Requisição:**

```http
GET /usuarios/42 HTTP/1.1
Host: api.exemplo.com
Accept: application/json
Authorization: Bearer <token>
```

**Resposta:**

```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "id": 42,
  "nome": "Ana Souza",
  "email": "ana@exemplo.com"
}
```

### Formatos de dados e protocolos

- **JSON** (*JavaScript Object Notation*): formato leve e legível, o mais usado atualmente.
- **XML**: formato mais verboso, comum em sistemas legados (ex.: SOAP).
- **Protocolos/estilos de API:** REST, SOAP, GraphQL e gRPC.

### Autenticação e segurança

Para controlar quem acessa a API, são usados mecanismos como **API Keys**, **tokens JWT**, **OAuth 2.0** e **HTTPS** (criptografia em trânsito). Também é comum aplicar **limite de requisições** (*rate limiting*).

---

## 3. O que são APIs REST

**REST** (*Representational State Transfer*) é um **estilo arquitetural** para sistemas distribuídos, proposto por Roy Fielding em sua tese de doutorado (2000). Uma **API REST** (ou *RESTful*) é aquela que segue os princípios desse estilo, geralmente sobre o protocolo HTTP. REST não é um protocolo nem um padrão formal, mas um conjunto de restrições de projeto.

### Princípios (restrições) do REST

1. **Cliente–servidor:** separação de responsabilidades entre interface (cliente) e armazenamento/processamento (servidor).
2. **Sem estado (*stateless*):** cada requisição contém todas as informações necessárias para ser compreendida; o servidor não guarda o contexto do cliente entre requisições.
3. **Cacheável:** respostas devem indicar se podem ou não ser armazenadas em cache, melhorando desempenho e escalabilidade.
4. **Interface uniforme:** padronização na forma de interagir com os recursos (identificação por URI, manipulação por representações, mensagens autodescritivas e, idealmente, hipermídia – HATEOAS).
5. **Sistema em camadas:** o cliente não precisa saber se está conectado diretamente ao servidor final ou a intermediários (proxies, gateways, balanceadores).
6. **Código sob demanda (opcional):** o servidor pode enviar código executável ao cliente (ex.: scripts).

### Recursos e URIs

Em REST, tudo é tratado como **recurso** (usuário, pedido, produto), identificado por uma **URI**. Boas práticas incluem usar **substantivos no plural** e evitar verbos na URL:

```text
✅ GET    /usuarios          → lista usuários
✅ GET    /usuarios/42       → busca o usuário 42
✅ POST   /usuarios          → cria um usuário
✅ PUT    /usuarios/42       → substitui o usuário 42
✅ PATCH  /usuarios/42       → atualiza parcialmente o usuário 42
✅ DELETE /usuarios/42       → remove o usuário 42

❌ GET /buscarUsuario?id=42
❌ POST /criarUsuario
```

### Mapeamento entre métodos HTTP e operações (CRUD)

| Método HTTP | Operação CRUD | Idempotente* | Seguro** |
|---|---|:---:|:---:|
| `GET` | Read (ler) | Sim | Sim |
| `POST` | Create (criar) | Não | Não |
| `PUT` | Update (substituir) | Sim | Não |
| `PATCH` | Update (parcial) | Não necessariamente | Não |
| `DELETE` | Delete (remover) | Sim | Não |

\* *Idempotente:* repetir a mesma requisição várias vezes produz o mesmo efeito no servidor que uma única vez.
\** *Seguro:* a requisição não altera o estado do recurso no servidor.

### Modelo de maturidade de Richardson

Descreve níveis de adoção do REST:
- **Nível 0:** HTTP usado apenas como meio de transporte (um único endpoint).
- **Nível 1:** uso de recursos (URIs distintas).
- **Nível 2:** uso correto dos métodos HTTP e códigos de status.
- **Nível 3:** hipermídia (HATEOAS), com links que guiam o cliente pelas ações disponíveis.

### Vantagens e limitações

| Vantagens | Limitações |
|---|---|
| Simplicidade e ampla adoção | *Over-fetching* / *under-fetching* (retorna mais ou menos dados do que o necessário) |
| Independência de linguagem e plataforma | Pode exigir várias requisições para montar uma tela complexa |
| Escalável (stateless + cache) | Não define um padrão rígido, gerando APIs inconsistentes |
| Aproveita a infraestrutura da web (HTTP) | Menos adequado para comunicação em tempo real (alternativas: WebSockets, gRPC) |

---

# Requisições HTTP, métodos e endpoints de API

## 1. Requisição HTTP

O protocolo HTTP funciona no modelo **requisição–resposta**: o cliente (navegador ou aplicação front-end) envia uma requisição ao servidor, e o servidor responde com o recurso solicitado ou com uma mensagem de status.

### Estrutura de uma requisição

- **Método:** a ação que o cliente quer realizar (GET, POST, PUT, PATCH, DELETE).
- **Cabeçalho (*header*):** informações adicionais, como tipo de conteúdo e autenticação.
- **Corpo (*body*):** dados transmitidos ao servidor. É opcional e aparece, por exemplo, em requisições `POST`.

### Estrutura de uma resposta

Toda resposta traz um **código de status HTTP**, que indica o resultado da requisição:

| Código | Significado |
|---|---|
| `200` | Requisição bem-sucedida |
| `404` | Recurso não encontrado |
| `500` | Erro interno no servidor |

Esse modelo é essencial para a comunicação entre sistemas diferentes e serve de base para a construção de **APIs REST**.

```http
POST /usuarios HTTP/1.1
Host: api.exemplo.com
Content-Type: application/json

{
  "nome": "Ana Souza",
  "email": "ana@exemplo.com"
}
```

---

## 2. Métodos HTTP

Os métodos HTTP indicam ao servidor **qual ação deve ser realizada** em uma requisição. Cada um tem uma finalidade: buscar, enviar, atualizar ou excluir informações.

### GET: buscar dados

Solicita dados ao servidor, como ao acessar uma página ou consultar informações. Não tem como objetivo alterar dados no servidor, e parâmetros de consulta podem ser enviados pela URL.
- **Exemplo:** buscar a lista de produtos ou de usuários.

```http
GET /usuarios?pagina=1 HTTP/1.1
```

### POST: enviar dados / criar recurso

Envia dados ao servidor, normalmente para criar um novo recurso. É muito usado em formulários e cadastros. Os dados vão no **corpo** da requisição.
- **Exemplo:** ao preencher um cadastro com nome e e-mail, os dados são enviados via `POST` para criar um novo usuário.

### PUT: atualização completa

Atualiza um recurso que já existe, enviando os dados necessários para **substituí-lo por completo**. Utiliza o identificador do recurso.
- **Exemplo:** `PUT /usuarios/42` para editar todos os dados do usuário de ID 42.

### PATCH: atualização parcial

Altera **somente determinadas informações** de um recurso, sem precisar reenviar todos os dados (diferente do `PUT`).
- **Exemplo:** o usuário quer mudar apenas o telefone: `PATCH /usuarios/42` com somente o campo `telefone`.

### DELETE: excluir recurso

Remove um recurso do servidor. Normalmente é preciso informar o identificador do recurso.
- **Exemplo:** `DELETE /usuarios/42` para excluir um usuário, produto ou publicação pelo ID.

### Resumo

| Método | Ação | Exemplo de uso |
|---|---|---|
| `GET` | Buscar informações | Listar produtos |
| `POST` | Criar / enviar informações | Cadastrar usuário |
| `PUT` | Atualizar recurso completamente | Editar todos os dados de um usuário |
| `PATCH` | Atualizar recurso parcialmente | Alterar apenas o telefone |
| `DELETE` | Remover recurso | Excluir um produto |

---

## 3. O que são endpoints de API

Um **endpoint de API** é o local digital onde uma API recebe solicitações para acessar recursos de um servidor. Geralmente é representado por uma **URL**.
O endpoint funciona como um **ponto de contato entre cliente e servidor**:

1. A aplicação precisa de um recurso e envia a solicitação ao endpoint correspondente.
2. O servidor recebe a solicitação e processa as informações.
3. O servidor retorna uma resposta ao cliente.

Em **APIs REST**, os endpoints são acessados por meio dos métodos HTTP (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`), e cada método indica a ação desejada sobre o recurso.

### Exemplo: um mesmo recurso, vários endpoints/ações

| Ação | Método e endpoint |
|---|---|
| Consultar usuários | `GET /usuarios` |
| Consultar um usuário | `GET /usuarios/42` |
| Cadastrar usuário | `POST /usuarios` |
| Alterar dados (completo) | `PUT /usuarios/42` |
| Alterar dados (parcial) | `PATCH /usuarios/42` |
| Excluir usuário | `DELETE /usuarios/42` |

Dessa forma, os endpoints permitem que aplicações **encontrem e acessem** os recursos e funcionalidades disponibilizados por uma API.
---

# JSON, API × sistema web tradicional e consumo de API com PHP

## 1. Como funciona a troca de dados no formato JSON

**JSON** (*JavaScript Object Notation*) é um formato de texto, baseado na notação de objetos do JavaScript, usado para **armazenar e transportar dados** entre servidores e aplicações web. Funciona como um "tradutor universal" de dados: a sintaxe simples é fácil de ler para humanos e eficiente de processar para máquinas, com baixo consumo de recursos.


### Estrutura


- **Objetos**, delimitados por chaves `{}`, contêm pares **chave-valor**.
- **Arrays**, delimitados por colchetes `[]`, contêm listas ordenadas de valores.


Regras de sintaxe:


- chaves e *strings* **sempre entre aspas duplas** (`"`);
- **não são permitidos comentários**;
- **não pode haver vírgula sobrando** no final de objetos e arrays.


Essa padronização rigorosa garante que os dados sejam interpretados corretamente por diferentes linguagens de programação.


```json
{
  "nome": "João Silva",
  "idade": 30,
  "ativo": true,
  "endereco": {
    "cidade": "São Paulo",
    "cep": "01001-000"
  },
  "interesses": ["tecnologia", "segurança digital"]
}
```

### Fluxo de troca de dados

| Etapa | O que acontece |
|---|---|
| **1. Serialização** (*stringify*) | A aplicação de origem (back-end ou front-end) converte um objeto nativo da sua linguagem em uma *string* de texto no formato JSON. |
| **2. Transmissão** | A *string* é enviada pela rede, geralmente por requisições HTTP em APIs RESTful ou AJAX. |
| **3. Desserialização** (*parse*) | O sistema de destino recebe o texto e o converte em um objeto ou estrutura manipulável na sua própria linguagem (JavaScript, Python, Java, PHP, Go etc.). |

---

## 2. API × sistema web tradicional

### API e serviço web

Ambos servem como meio de comunicação:

- **API** (*Application Programming Interface*): define como diferentes componentes de software interagem. Pode usar vários protocolos, formatos de dados e arquiteturas.
- **Serviço web** (*web service*): uma API projetada para se comunicar pela web, usando HTTP ou HTTPS.

> Todos os serviços web são APIs, mas nem todas as APIs são serviços web.

### Comparação


| Característica | Sistema web tradicional | API |
|---|---|---|
| **Público** | Seres humanos | Outros softwares |
| **O que entrega** | Página pronta e renderizada (HTML) para o navegador exibir | Dados brutos (ex.: lista de produtos, saldo de uma conta) |
| **Quem decide a exibição** | O próprio sistema | A aplicação que fez o pedido |

---

## 3. Aplicação PHP para consumir uma API

Uma aplicação PHP pode consumir uma API enviando **requisições HTTP**, como o `GET`, para obter dados de outro sistema. Neste exemplo, usa-se a **API ViaCEP**, que consulta códigos de endereçamento postal: o PHP envia um CEP, recebe o endereço em JSON e usa essas informações na aplicação.

Para organizar o código:

- `Search.php`: classe responsável pela comunicação com a API;
- `index.php`: instancia a classe, chama o método e exibe o endereço ao usuário.

### `src/Search.php`

```php
<?php


namespace Jdev\Cepdigital;
class Search
{
    private $url = "https://viacep.com.br/ws/";


    public function getAddressFromZipcode(string $zipCode)
    {
        $zipCode = preg_replace('/[^0-9]/im', '', $zipCode);


        $get = file_get_contents($this->url . $zipCode . "/json");


        $get = json_decode($get, true);


        return isset($get['cep']) ? $get : null;
    }
}
```

**Explicação:**

- **Linha 3:** define o *namespace*.
- **Linha 4:** define o nome da classe.
- **Linha 6:** define o *endpoint*, ou seja, para onde a requisição será enviada.
- **Linha 10:** remove do CEP tudo que não for número (ex.: o hífen).
- **Linha 12:** usa `file_get_contents` para enviar a requisição `GET`.
- **Linha 14:** `json_decode` converte o JSON recebido em um array PHP (desserialização).
- **Linha 16:** usa o operador ternário para retornar `null` caso a resposta não contenha um CEP válido.


### `index.php`


```php
<?php
//require __DIR__.'/vendor/autoload.php';
require_once 'src/Search.php';
use Jdev\Cepdigital\Search;


$busca = new Search;


$resultado = $busca->getAddressFromZipcode('06230060');


if (is_null($resultado)) {
    echo "O CEP não foi encontrado.";
} else {
    print_r($resultado);
}
```


**Explicação:**


- **Linhas 3 e 4:** importam a classe `Search`.
- **Linha 6:** instancia um novo objeto da classe `Search`.
- **Linha 8:** acessa o método `getAddressFromZipcode`, passando o CEP como valor.
- **Linhas 10 a 14:** exibem a mensagem de erro se o resultado for nulo; caso contrário, imprimem o endereço.


> **Observação:** `file_get_contents` com URLs depende da diretiva `allow_url_fopen` estar habilitada no `php.ini`. Em aplicações maiores, é comum usar cURL ou bibliotecas como Guzzle.

---
# Disponibilização de informações, segurança e uso empresarial de APIs


## 1. Como uma API pode disponibilizar informações para outros sistemas


As APIs (*Application Programming Interfaces*) disponibilizam informações a outros sistemas por meio de uma **comunicação padronizada** entre aplicações, usando o modelo de **requisição e resposta**. As APIs baseadas na Web funcionam como uma ponte entre cliente e servidor, utilizando protocolos como o **HTTP**. Assim, sistemas diferentes interagem e compartilham dados pela internet, independentemente das tecnologias usadas em cada extremidade.


### Etapas do funcionamento


1. **O cliente (quem solicita):** a aplicação que precisa obter informações ou executar uma ação, como um aplicativo de celular ou um site.
2. **O endpoint (o endereço):** o ponto específico de acesso à API para uma determinada operação. Por exemplo:
   - `GET /tasks` consulta tarefas;
   - `POST /tasks` adiciona uma nova tarefa.
3. **O servidor (quem responde):** aguarda as requisições; ao recebê-las, verifica os dados e as permissões de acesso, processa o pedido e executa as operações necessárias.
4. **A resposta:** o servidor devolve os dados em um formato padronizado, geralmente **JSON** (outros formatos, como **XML**, também podem ser usados).
5. **O processamento pelo cliente:** o sistema que fez a solicitação usa as informações recebidas, por exemplo, exibindo dados em uma página ou atualizando um aplicativo.


```text
Cliente ──(requisição HTTP: GET /tasks)──► Endpoint (API/Servidor)
   ▲                                              │
   │                                    valida, verifica permissões
   │                                    e processa o pedido
   └────────(resposta em JSON/XML)◄───────────────┘
```


Dessa forma, as APIs permitem o compartilhamento de informações de maneira **automatizada, organizada e segura**, sem que seja necessário conhecer os detalhes internos do sistema que fornece os dados.


### Exemplo prático: aplicativo de previsão do tempo


O aplicativo envia uma requisição HTTP a uma API pedindo informações sobre uma cidade. O servidor processa o pedido e retorna dados como temperatura e condições climáticas, normalmente em JSON. O aplicativo interpreta essas informações e as apresenta ao usuário.


```http
GET /clima?cidade=Joinville HTTP/1.1
Host: api.exemplo.com
Accept: application/json
```


```json
{
  "cidade": "Joinville",
  "temperatura": 22,
  "condicao": "Parcialmente nublado"
}
```

---


## 2. Importância da autenticação, autorização e segurança no consumo de APIs


Autenticação, autorização e segurança são fundamentais no consumo de APIs porque protegem as informações e garantem que **apenas usuários e sistemas autorizados** acessem os recursos disponibilizados.


### Autenticação × Autorização


| Conceito | Pergunta que responde | Como funciona | Exemplos de mecanismos |
|---|---|---|---|
| **Autenticação** | *Quem é você?* | Verifica a identidade de um usuário ou sistema | Senhas, tokens de acesso, certificados digitais |
| **Autorização** | *O que você pode fazer?* | Define quais informações e funcionalidades o usuário ou sistema pode acessar, **após** ter a identidade verificada | Perfis e permissões (ex.: administrador × usuário comum) |


**Exemplo:** quando um funcionário faz login em um sistema, a autenticação confirma sua identidade. Em seguida, a autorização determina quais recursos ele pode acessar, de acordo com seu perfil. Um administrador, por exemplo, pode ter acesso a mais funcionalidades que um usuário comum.


### Segurança no consumo de APIs


Envolve medidas como:


- uso de **HTTPS** para proteger os dados durante a transmissão;
- **validação das requisições**;
- **controle de acesso**;
- **gerenciamento seguro de tokens e credenciais**.


Essas medidas ajudam a evitar acessos indevidos, vazamentos de informações e outras ameaças.


> Autenticação, autorização e segurança trabalham em conjunto para garantir uma comunicação confiável entre sistemas, protegendo os dados e assegurando que cada usuário ou aplicação acesse somente o que lhe é permitido.


---

## 3. Exemplos de utilização de APIs em sistemas empresariais


### 3.1 API de voz


Integrada à Inteligência Artificial (IA), permite automatizar chamadas telefônicas, interações por voz e parte das demandas de atendimento ao cliente. A tecnologia interpreta solicitações, reconhece a fala e gera respostas automatizadas, tornando a comunicação mais ágil e escalável.


### 3.2 API para SMS


Conecta os sistemas internos da empresa às plataformas de envio de mensagens, permitindo automatizar disparos, personalizar comunicações e enviar milhares de mensagens simultaneamente. Isso dá mais agilidade às campanhas (ofertas, novidades, relacionamento) e reduz o trabalho manual da equipe.


### 3.3 API para Instagram


Permite integrar o Instagram a CRMs, plataformas de marketing e ferramentas de atendimento, centralizando informações e otimizando processos. Possibilita automatizar tarefas, facilitar o gerenciamento de interações e integrar dados às estratégias comerciais.


### 3.4 API para WhatsApp


Integra as conversas a plataformas de atendimento, CRMs e sistemas comerciais, financeiros ou de marketing. Permite automatizar notificações, confirmações, atualizações de pedidos e etapas do atendimento, além de registrar as interações nos sistemas da empresa.


### Resumo


| API | Principal uso empresarial | Benefício |
|---|---|---|
| Voz (com IA) | Automatizar chamadas e atendimento | Agilidade e escalabilidade |
| SMS | Campanhas e comunicados em massa | Produtividade e conversão |
| Instagram | Integração com CRM, marketing e atendimento | Centralização de dados e alcance |
| WhatsApp | Notificações, confirmações e atendimento | Processos ágeis e informações organizadas |

---

## Referências

1. **MDN Web Docs.** *API* (Glossário). Disponível em: <https://developer.mozilla.org/pt-BR/docs/Glossary/API>. Acesso em: 21 out. 2026.
2. **MDN Web Docs.** *Uma visão geral do HTTP*. Disponível em: <https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Overview>. Acesso em: 21 out. 2026.
3. **MDN Web Docs.** *REST* (Glossário). Disponível em: <https://developer.mozilla.org/pt-BR/docs/Glossary/REST>. Acesso em: 21 out. 2026.
4. **AWS (Amazon Web Services).** *O que é uma API?* Disponível em: <https://aws.amazon.com/pt/what-is/api/>. Acesso em: 21 out. 2026.
5. **AWS (Amazon Web Services).** *O que é uma API RESTful?* Disponível em: <https://aws.amazon.com/pt/what-is/restful-api/>. Acesso em: 21 out. 2026.
6. **Red Hat.** *O que é uma API?* Disponível em: <https://www.redhat.com/pt-br/topics/api/what-are-application-programming-interfaces>. Acesso em: 21 out. 2026.
7. **FIELDING, Roy Thomas.** *Architectural Styles and the Design of Network-based Software Architectures*. Tese (Doutorado) – University of California, Irvine, 2000. Capítulo 5. *Representational State Transfer (REST)*. Disponível em: <https://ics.uci.edu/~fielding/pubs/dissertation/rest_arch_style.htm>. Acesso em: 21 out. 2026.
8. **IETF.** *RFC 9110: HTTP Semantics*. Disponível em: <https://www.rfc-editor.org/rfc/rfc9110>. Acesso em: 21 out. 2026.
9. **IETF.** *RFC 8259: The JavaScript Object Notation (JSON) Data Interchange Format*. Disponível em: <https://www.rfc-editor.org/rfc/rfc8259>. Acesso em: 21 out. 2026.
10. **FOWLER, Martin.** *Richardson Maturity Model*. Disponível em: <https://martinfowler.com/articles/richardsonMaturityModel.html>. Acesso em: 21 out. 2026.
11. **PROGRAMADORES BRASIL.** *Como funciona a Requisição HTTP: GET, POST, PUT e DELETE*. Disponível em: <https://programadoresbrasil.com/desenvolvimento-fullstack/requisicao-http-como-funciona-get-post-put-e-delete/>. Acesso em: 21 out. 2026.
12. **IBM.** *O que é um endpoint de API?* Disponível em: <https://www.ibm.com/br-pt/think/topics/api-endpoint>. Acesso em: 21 out. 2026.
13. **TECNOBLOG.** *O que é JSON? Conheça o formato padrão para a troca de dados na web*. Disponível em: <https://tecnoblog.net/responde/o-que-e-json-conheca-o-formato-padrao-para-a-troca-de-dados-na-web/>. Acesso em: 21 out. 2026.
14. **ALURA.** *O que é JSON?* Disponível em: <https://www.alura.com.br/artigos/o-que-e-json>. Acesso em: 21 out. 2026.
15. **DIO.** *APIs e JSON: entendendo a conexão entre eles*. Disponível em: <https://www.dio.me/articles/apis-e-json-entendendo-a-conexao-entre-eles>. Acesso em: 21 out. 2026.
16. **STACK OVERFLOW BLOG.** *A beginner's guide to JSON, the data format for the internet*. 2 jun. 2022. Disponível em: <https://stackoverflow.blog/2022/06/02/a-beginners-guide-to-json-the-data-format-for-the-internet/>. Acesso em: 21 out. 2026.
17. **SNOWFLAKE.** *O que é JSON?* Disponível em: <https://www.snowflake.com/pt_br/fundamentals/what-is-json/>. Acesso em: 21 out. 2026.
18. **MDN WEB DOCS.** *Trabalhando com JSON*. Disponível em: <https://developer.mozilla.org/pt-BR/docs/Learn_web_development/Core/Scripting/JSON>. Acesso em: 21 out. 2026.
19. **POSTMAN BLOG.** *API vs. web service*. Disponível em: <https://blog.postman.com/api-vs-web-service/>. Acesso em: 21 out. 2026.
20. **DIO.** *Web vs. API: o que são?* Disponível em: <https://www.dio.me/articles/web-vs-api-o-que-sao>. Acesso em: 21 out. 2026.
21. **iMASTERS.** *Como consumir APIs em aplicações PHP de forma simples*. Disponível em: <https://imasters.com.br/apis-microsservicos/como-consumir-apis-em-aplicacoes-php-de-forma-simples>. Acesso em: 21 out. 2026.
22. **ARAÚJO, Jaelcio.** *Consumindo APIs com PHP*. LinkedIn Pulse. Disponível em: <https://pt.linkedin.com/pulse/consumindo-apis-com-php-jaelcio-araujo>. Acesso em: 21 out. 2026.

