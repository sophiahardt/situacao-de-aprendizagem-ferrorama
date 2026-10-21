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

## Referências

1. **MDN Web Docs.** *API* (Glossário). Disponível em: <https://developer.mozilla.org/pt-BR/docs/Glossary/API>. Acesso em: 21 out. 2026.
2. **MDN Web Docs.** *Uma visão geral do HTTP*. Disponível em: <https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Overview>. Acesso em: 21 out. 2026.
3. **MDN Web Docs.** *REST* (Glossário). Disponível em: <https://developer.mozilla.org/pt-BR/docs/Glossary/REST>. Acesso em: 21 out. 2026.
4. **AWS (Amazon Web Services).** *O que é uma API?* Disponível em: <https://aws.amazon.com/pt/what-is/api/>. Acesso em: 21 out. 2026.
5. **AWS (Amazon Web Services).** *O que é uma API RESTful?* Disponível em: <https://aws.amazon.com/pt/what-is/restful-api/>. Acesso em: 21 out. 2026.
6. **Red Hat.** *O que é uma API?* Disponível em: <https://www.redhat.com/pt-br/topics/api/what-are-application-programming-interfaces>. Acesso em: 21 out. 2026.
7. **FIELDING, Roy Thomas.** *Architectural Styles and the Design of Network-based Software Architectures*. Tese (Doutorado) – University of California, Irvine, 2000. Capítulo 5: *Representational State Transfer (REST)*. Disponível em: <https://ics.uci.edu/~fielding/pubs/dissertation/rest_arch_style.htm>. Acesso em: 21 out. 2026.
8. **IETF.** *RFC 9110: HTTP Semantics*. Disponível em: <https://www.rfc-editor.org/rfc/rfc9110>. Acesso em: 21 out. 2026.
9. **IETF.** *RFC 8259: The JavaScript Object Notation (JSON) Data Interchange Format*. Disponível em: <https://www.rfc-editor.org/rfc/rfc8259>. Acesso em: 21 out. 2026.
10. **FOWLER, Martin.** *Richardson Maturity Model*. Disponível em: <https://martinfowler.com/articles/richardsonMaturityModel.html>. Acesso em: 21 out. 2026.
