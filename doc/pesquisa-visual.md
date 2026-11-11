# Identidade Visual — Ferroramas

## 1. Processo de definição da identidade visual

 identidade visual do Ferroramas começou a ser definida durante o desenvolvimento da Landing Page, na etapa anterior da atividade.

A equipe testou diferentes tons de azul e percebeu que os mais escuros transmitiam uma aparência mais profissional e tecnológica. Por isso, essa identidade foi mantida e expandida para a tela de login e para as telas internas.

Nesta etapa, a revisão foi além das cores: também foram revisadas as tabelas, os formulários, o dashboard e as mensagens do sistema. Algumas mudanças vieram de observações do professor durante o acompanhamento.

## 2. Conceitos de teoria das cores utilizados

Harmonia monocromática em azul, complementada por cores de estado.

- **Azul-marinho como cor principal:** transmite confiança, segurança e tecnologia, combinando com um sistema de monitoramento ferroviário.
- **Um tom de azul para cada função:** na revisão, a equipe encontrou cerca de 25 tons de azul diferentes no CSS, muitos quase iguais. Eles foram reduzidos a poucos tons, cada um com uma função definida.
- **Cores de estado só para estados:** vermelho, amarelo e verde nunca aparecem como decoração, para que o usuário aprenda o que cada uma significa.
- **A cor não trabalha sozinha:** todo estado também tem texto ou ícone, já que nem todas as pessoas distinguem bem as cores.

### Cores de estado

| Cor | Significado | Onde aparece |
|---|---|---|
| Vermelho | Perigo, erro | Alertas críticos, erros, botão Excluir |
| Amarelo | Atenção | Alertas de atenção |
| Verde | Sucesso | Mensagens após cadastrar, editar ou excluir |
| Azul | Informação | Alertas informativos |


## 3. Paleta de cores escolhida

As cores ficam definidas uma única vez, como variáveis no início do `style/style.css`.

| Cor | HEX | Função |
|---|---|---|
| Azul-noite | `#071A3D` | Fundos escuros: barra da Landing Page, painel do login |
| Azul-marinho | `#151D4B` | Cor principal: navbar, botões principais, cabeçalho das tabelas |
| Azul profundo | `#0A2A66` | Títulos azuis e botão Editar |
| Azul de destaque | `#1666E8` | Ícones, links e botão Entrar |
| Azul-claro | `#6FB1FF` | Detalhes sobre fundos escuros |
| Azul acinzentado | `#4A5F8A` | Textos secundários |
| Fundo claro | `#F5F8FC` | Fundo das páginas |
| Fundo azulado | `#EEF4FC` | Seções e cabeçalho das tabelas |
| Borda clara | `#DCE9F8` | Bordas de cards e tabelas |
| Branco | `#FFFFFF` | Cards, formulários e modais |
| Vermelho | `#DC3545` | Erros, exclusão e alertas críticos |
| Amarelo | `#F5B400` | Alertas de atenção |
| Amarelo escuro | `#9A6B00` | Ícone dos alertas de atenção (ver item 8) |

A Landing Page e o login usam a mesma foto e o mesmo degradê azul, escuro à esquerda (onde ficam o texto e o formulário) e transparente à direita (onde aparece a locomotiva).

## 4. Tipografia e elementos gráficos

- **Fonte:** Arial em todo o sistema, por ser legível em tamanhos pequenos, como em tabelas e formulários.
- **Títulos sempre em negrito:** antes, alguns títulos apareciam finos e outros em negrito. O Arial só tem dois pesos, e o padrão do Bootstrap (500) aparecia como normal. Todos os títulos passaram a usar o peso 700.
- **Hierarquia:** títulos maiores e em negrito; subtítulos para as seções; descrições menores e em cor suave.
- **Ícones:** biblioteca Ionicons, sempre o mesmo ícone para a mesma funcionalidade (por exemplo, o trem sempre representa trens).

## 5. Decisões de layout e hierarquia visual

- Navbar padronizada em todas as telas internas.
- Cadastro e edição em modais em sensores, trens, rotas e usuários: o usuário não sai da listagem, e todas as telas funcionam do mesmo jeito.
- Colunas fixas nas tabelas: ID (ou Nome) sempre à esquerda e Ações sempre à direita, com a mesma largura em todas as listagens.
- Ações só com ícones: lápis para editar e lixeira para excluir.
- Dashboard em faixas: clima e banner no topo, atalhos no meio, e o resumo da operação ao lado dos alertas embaixo.
- Responsividade com Bootstrap (ver item 8).


## 6. Problemas de usabilidade e inconsistências visuais identificadas

| Problema encontrado | Como foi resolvido |
|---|---|
| Telas internas com cores diferentes da Landing Page | Paleta única, aplicada em todas as telas |
| Login não parecia do mesmo sistema (observação do professor) | Mesma foto e degradê da Landing Page, com painel azul sólido |
| Cerca de 25 tons de azul e cores escritas direto no HTML | Tons agrupados em variáveis e classes de botão |
| Títulos com pesos diferentes entre as telas | Todos os títulos com peso 700 |
| ID e botões mudavam de lugar ao trocar de tela | Colunas com largura fixa |
| Botões com texto e ícone ao mesmo tempo (observação do professor) | Só ícones, com o nome ao passar o mouse |
| Botão Salvar cortado no modal de usuário | Rolagem interna no modal |
| Botão de excluir relatório não funcionava | Link de exclusão corrigido |
| Espaço do mapa vazio no dashboard | Substituído pelos alertas de monitoramento |
| Funcionário não via o botão de cadastro | Botão liberado para quem tem permissão |

## 7. Mudanças realizadas e justificativas

| Decisão | Justificativa |
|---|---|
| Login igual à Landing Page | O botão "Acessar o sistema" leva direto ao login; com a mesma imagem, a passagem entre as telas fica contínua. O painel sólido garante a leitura dos campos. |
| Botões de ação só com ícones | Lápis e lixeira são símbolos conhecidos. A coluna Ações diminuiu de 220px para 110px, sobrando espaço para as informações. |
| Um tom de azul por função | Na harmonia monocromática, cada tom precisa de um papel claro. Com as variáveis, o mesmo elemento tem a mesma cor em todas as telas. |
| Colunas fixas nas tabelas | O que se repete em todas as telas deve ficar no mesmo lugar, para o usuário não precisar procurar. |
| Alertas em quadros curtos | Mostram só o essencial (ícone, título, detalhe e horário), e a gravidade é percebida rapidamente. |
| Sair volta para a Landing Page | Fecha o ciclo de navegação: o usuário sai do sistema pelo mesmo lugar por onde entrou. |

**Dificuldade enfrentada:** no modal de cadastro de usuário, o botão Salvar ficava cortado em telas mais baixas. O `<form>` ficava entre o corpo e o rodapé do modal e impedia a rolagem do Bootstrap. A solução foi adicionar ao formulário as classes `d-flex flex-column overflow-hidden`, fazendo o conteúdo rolar e mantendo os botões sempre visíveis.

## 8. Testes de contraste, responsividade e legibilidade

### Contraste

Calculado pela fórmula do WCAG 2.1. O mínimo (nível AA) é 4,5:1 para texto e 3:1 para ícones.

| Combinação | Contraste | Resultado |
|---|---:|---|
| Texto branco na navbar e nos botões (#151D4B) | 16,04:1 |
| Texto branco no botão Entrar (#1666E8) | 5,12:1 |
| Texto branco no botão Excluir (#DC3545) | 4,53:1 |
| Cabeçalho das tabelas (#151D4B sobre #EEF4FC) | 14,50:1 |
| Texto secundário (#4A5F8A sobre branco) | 6,37:1 |
| Ícone de atenção — antes (#F5B400) | 1,68:1 |
| Ícone de atenção — depois (#9A6B00) | 4,28:1 |

O único problema foi o ícone amarelo dos alertas: ele passou a usar um amarelo escuro, e o amarelo original ficou só na faixa do card.

### Responsividade

- A navbar recolhe em um botão de menu em telas pequenas.
- As tabelas rolam para o lado em vez de espremer as colunas.
- Os formulários passam de duas colunas para uma.
- Os alertas passam de 3 para 2 por linha.

## 9. Fontes consultadas

- Bootstrap 5.3 — Documentação oficial: responsividade, modais, tabelas e botões.
- Ionicons — Documentação oficial: ícones.
- W3C — WCAG: critérios de acessibilidade.
- W3C — Contraste mínimo (1.4.3): valores usados nos testes de contraste.
- W3C — Uso de cor (1.4.1): por que a cor não pode ser o único sinal.
- WebAIM — Contrast Checker: ferramenta para conferir contraste.
- MDN — Variáveis CSS: organização da paleta em variáveis.
- A própria Landing Page, desenvolvida na etapa anterior, foi utilizada como referência interna.

## 10. Ferramentas utilizadas

- **HTML, CSS e JavaScript:** estrutura, estilos e interações.
- **Bootstrap 5.3.3:** grid responsivo, modais e componentes.
- **Ionicons:** ícones.
- **Visual Studio Code:** edição dos arquivos.
- **Git e GitHub:** versionamento e registro das alterações.
- **Inteligência Artificial (Claude e ChatGPT):** apoio na implementação das mudanças definidas pela equipe e pelo professor (organização das cores em variáveis, modais, remoção de CSS sem uso, cálculo de contraste) e na revisão deste documento.

As decisões finais de design foram avaliadas e definidas pela equipe, considerando as necessidades e a identidade do sistema Ferroramas.