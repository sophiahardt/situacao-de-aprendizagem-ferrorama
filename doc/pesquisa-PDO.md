# Pesquisa sobre PDO

## O que é o PDO?

PDO significa PHP Data Objects. É uma extensão do PHP que fornece uma interface padronizada para que aplicações PHP possam se comunicar com bancos de dados relacionais.
O PDO funciona por meio de drivers, que permitem a comunicação com diferentes sistemas de gerenciamento de bancos de dados, como MySQL, PostgreSQL e SQLite. Dessa forma, ele oferece uma estrutura mais padronizada para realizar operações no banco de dados. Este módulo surgiu a partir da versão 5 de PHP.

## Para que o PDO é utilizado no PHP?

O PDO é utilizado para conectar aplicações desenvolvidas em PHP a banco de dados e realizar operações como:

* Consultar dados;
* Inserir registros;
* Alterar informações;
* Excluir registros;
* Executar comandos SQL;
* Controlar transações;
* Utilizar Prepared Statements;
* Tratar erros durante a comunicação com o banco;

Antes do PDO, era comum utilizar extensões específicas para cada banco de dados. O PDO surgiu como uma alternativa que padroniza a forma de acesso aos dados.

## Como funciona uma onexão utilizando PDO?

Uma conexão com PDO é realizada por mrio da classe PDO. PAra conectar ao MySQL, é necessário informar o servidor, o banco de dados, o usuário e a senha.

Exemplo:

```php
<?php

$host = "localhost";
$banco = "db_ferrovia";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
```

O **DSN (Data Source Name)** informa ao PDO qual driver deve ser utilizado, qual servidor será acessado e qual banco de dados será utilizado.

Nesse exemplo, mysql indica o driver, localhost indica o servidor e db_ferrorama representa o banco de dados.

O bloco try...catch é utilizado para tratar possíveis erros durante a conexão. Quando configurado para trabalhar com exceções, o PDO pode lançar uma PDOException caso ocorra algum problema.

# Principais características  do PDO:

Entre as principais características do PDO estão:

* Interface padronizada para acesso a banco de dados;
* Suporte a diferentes bancos por meio de drivers;
* Suporte a Prepared Statements;
* Tratamento de erros por meio de exceções;
* Suporte a transações;
* Diferentes modos para recuperar resultados;
* API orientada a objetos;
* Maior portabilidade entre diferentes sistemas de banco de dados.

O PDO utiliza métodos como prepare(), execute() e query() para executar comandos SQL.

Também é possível utilizar métodos como fetch() e fetchAll() para recuperar os resultados das consultas.

## PDO x MySQLi

| Característica                | PDO                      | MySQLi        |
| ----------------------------- | ------------------------ | ------------- |
| Suporte ao MySQL              | Sim                      | Sim           |
| Suporte a outros bancos       | Sim, por meio de drivers | Não           |
| Prepared Statements           | Sim                      | Sim           |
| Orientação a objetos          | Sim                      | Sim           |
| Interface procedural          | Não                      | Sim           |
| Transações                    | Sim                      | Sim           |
| Portabilidade                 | Maior                    | Menor         |
| Recursos específicos do MySQL | Mais limitado            | Maior suporte |

O **MySQLi** foi desenvolvido especificamente para trabalhar com bancos de dados MySQL. Já o **PDO** possui uma interface que pode ser utilizada com diferentes bancos, desde que exista um driver compatível.

Por isso, o PDO pode ser mais interessante em projetos que precisam de maior portabilidade, enquanto o MySQLi pode ser uma escolha adequada quando o projeto utiliza exclusivamente MySQL e necessita de recursos específicos desse banco.

## Vantagens do PDO

As principais vantagens de utilizar PDO são:

* Maior portabilidade entre diferentes bancos de dados;
* Interface orientada a objetos;
* Suporte a Prepared Statements;
* Tratamento de erros por meio de exceções;
* Suporte a transações;
* Código organizado e padronizado;
* Facilidade para trabalhar com diferentes bancos de dados.

## Desvantagens do PDO

Apesar de suas vantagens, o PDO também possui algumas limitações:

* É necessário possuir o driver correspondente ao banco de dados;
* Alguns recursos específicos do MySQL podem ser mais facilmente utilizados com MySQLi;
* Para projetos exclusivamente voltados ao MySQL, o MySQLi pode oferecer recursos específicos que não estão disponíveis da mesma forma no PDO.

## O que são Prepared Statements?

**Prepared Statements** são instruções SQL preparadas previamente que utilizam parâmetros para receber valores fornecidos pela aplicação.

Exemplo:

```php
$sql = "SELECT * FROM usuario WHERE email = :email";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":email" => $email
]);
```

Nesse caso, o valor de `$email` é enviado como um parâmetro separado da instrução SQL, em vez de ser inserido diretamente na string do comando.

Essa prática é importante principalmente para aumentar a segurança da aplicação e ajudar a prevenir ataques de **SQL Injection**.

Além disso, Prepared Statements podem ser úteis quando a mesma instrução SQL precisa ser executada várias vezes utilizando valores diferentes.

Tanto o **PDO** quanto o **MySQLi** oferecem suporte a Prepared Statements.

## Quando o PDO pode ser uma boa escolha?

O PDO pode ser uma boa escolha quando:

* O projeto pode utilizar diferentes tipos de banco de dados;
* A equipe deseja uma interface padronizada;
* É necessário utilizar Prepared Statements;
* O sistema precisa trabalhar com transações;
* A aplicação utiliza uma arquitetura orientada a objetos;
* A portabilidade entre bancos de dados é importante;
* A equipe deseja utilizar uma estrutura organizada para acesso aos dados.

No caso do projeto **Ferroramas**, a equipe utilizará o **MySQLi** nesta etapa, conforme definido nos requisitos da implementação. O PDO foi estudado como uma alternativa para conexão e manipulação de bancos de dados em PHP.

## Conclusão

O PDO e o MySQLi são alternativas utilizadas para conectar aplicações PHP a bancos de dados.

O MySQLi é específico para o MySQL e oferece suporte a recursos próprios desse banco. Já o PDO fornece uma interface padronizada e permite trabalhar com diferentes sistemas de banco de dados por meio de drivers.

Além disso, ambos oferecem suporte a Prepared Statements, que são importantes para aumentar a segurança das aplicações contra ataques de SQL Injection.

Para o projeto Ferroramas, a escolha nesta etapa é o **MySQLi**, mantendo o **PDO** como uma alternativa estudada pela equipe.

## Fontes utilizadas:

* PHP Documentation. PDO — PHP Data Objects. Disponível em: https://www.php.net/manual/en/book.pdo.php
* PHP Documentation. Connections and Connection management. Disponível em: https://www.php.net/manual/en/pdo.connections.php
* PHP Documentation. MySQLi — Overview. Disponível em: https://www.php.net/manual/en/mysqli.overview.php
