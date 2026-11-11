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

Foi mantida a fonte Arial, com estilo sem serifa, por apresentar boa legibilidade e uma aparência simples e adequada a sistemas administrativos.

A hierarquia dos textos foi organizada por meio de diferentes tamanhos e pesos:

- Títulos em destaque e com maior peso.
- Subtítulos para identificar as seções.
- Textos menores para descrições e informações complementares.
- Botões com textos objetivos para indicar suas ações.

Para os elementos gráficos, foram utilizados ícones da biblioteca Ionicons, escolhidos de acordo com suas funcionalidades, como trens, sensores, usuários, rotas e relatórios.

A utilização de ícones semelhantes entre as páginas ajuda a manter a padronização visual e facilita o reconhecimento das funcionalidades.

## 5. Decisões de layout e hierarquia visual

A equipe buscou manter uma estrutura semelhante entre as páginas, facilitando a navegação e evitando diferenças visuais desnecessárias.

As principais decisões foram:

- Utilização de uma navbar padronizada para manter a navegação consistente.
- Organização de informações em cards, facilitando a identificação de cada funcionalidade.
- Utilização de títulos e subtítulos para separar os conteúdos.
- Padronização de botões, cores e ícones.
- Uso de espaçamentos e alinhamentos para evitar excesso de informações concentradas em uma única área.
- Organização responsiva com Bootstrap, permitindo adaptar os elementos a diferentes tamanhos de tela.

Na Home Page, também foi mantido um destaque visual para o nome do sistema e para o acesso às rotas, reforçando a identidade do projeto.

## 6. Problemas de usabilidade e inconsistências visuais identificadas

Durante a revisão do frontend, foi identificada uma diferença visual entre a Landing Page e algumas páginas internas do sistema. Enquanto a Landing Page já utilizava tons mais escuros de azul, outras telas ainda apresentavam cores diferentes nos botões, títulos e na barra de navegação.

Essa diferença prejudicava a continuidade visual e poderia transmitir a impressão de que as páginas pertenciam a sistemas diferentes.

Para resolver o problema, a equipe decidiu expandir a identidade visual definida na etapa anterior, reduzindo a quantidade de tonalidades de azul e padronizando os principais elementos das demais telas.

Também foi revisada a tela de login, substituindo a imagem de fundo por uma opção mais adequada à identidade visual definida.

## 7. Mudanças realizadas e justificativas

As principais alterações foram:

- **Usuários, trens, rotas e sensores:** padronização das cores da navbar e dos botões, utilizando principalmente o azul-marinho.
- **Home Page:** alteração da cor do banner principal para combinar com a identidade visual definida na Landing Page.
- **Landing Page:** padronização dos botões, títulos, ícones e fundos, reduzindo a quantidade de tonalidades de azul.
- **Tela de login:** substituição da imagem de fundo por uma imagem mais profissional, alteração da cor do botão de entrada e do título "FERRORAMAS", além da padronização do ícone do trem.

Essas mudanças tiveram como objetivo dar continuidade à identidade visual definida anteriormente, tornando as páginas mais consistentes, organizadas e relacionadas à proposta ferroviária do sistema.

## 8. Testes de contraste, responsividade e legibilidade

A equipe considerou a legibilidade dos textos, o contraste entre os elementos e a adaptação da interface a diferentes tamanhos de tela.

Foram utilizados recursos do Bootstrap para organizar os elementos de forma responsiva, como o sistema de grid e as classes de espaçamento e alinhamento.

Os testes de responsividade devem considerar:

- Visualização em computadores.
- Visualização em telas menores.
- Funcionamento do menu de navegação recolhido.
- Organização dos cards e formulários em diferentes larguras.

Também é importante verificar se os textos e botões continuam legíveis e se os elementos interativos permanecem acessíveis.

**Registro dos testes:** inserir capturas de tela das diferentes resoluções testadas e registrar eventuais ajustes realizados.

## 9. Fontes consultadas

- [Bootstrap 5.3 — Documentação oficial](https://getbootstrap.com/docs/5.3/): referência para organização responsiva, componentes e estrutura das páginas.
- [Ionicons — Documentação oficial](https://ionic.io/ionicons): referência para utilização dos ícones.
- [W3C — Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/WAI/standards-guidelines/wcag/): referência para critérios de acessibilidade, contraste e legibilidade.

A própria Landing Page, desenvolvida na etapa anterior, foi utilizada como referência interna para dar continuidade à identidade visual.

## 10. Ferramentas utilizadas

Durante o desenvolvimento e a revisão da interface, foram utilizadas:

- **HTML:** estruturação dos conteúdos das páginas.
- **CSS:** definição das cores, estilos e identidade visual.
- **Bootstrap 5.3.3:** organização dos elementos e responsividade.
- **Ionicons:** utilização de ícones relacionados às funcionalidades.
- **Visual Studio Code:** edição dos arquivos do sistema.
- **Git e GitHub:** versionamento e registro das alterações.
- **Inteligência Artificial:** apoio na organização de ideias, sugestões de melhorias visuais e auxílio durante o desenvolvimento e a revisão da interface.

As decisões finais de design foram avaliadas e definidas pela equipe, considerando as necessidades e a identidade do sistema Ferroramas.