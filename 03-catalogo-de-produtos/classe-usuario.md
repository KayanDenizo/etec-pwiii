# Classe `Usuario`

Classe em PHP (PDO) que cuida dos usuários da **Loja Etim**: inserir, alterar, apagar e listar, além do login. Foi feita seguindo o mesmo modelo da classe [`Produto`](classes/Produto.class.php): mesmo `conecta()`, mesmo uso de `prepare` + `bindValue` + `execute`.

Arquivo: [`classes/Usuario.class.php`](classes/Usuario.class.php)

## Tabela no banco

A tabela fica no banco `loja_etim`, junto de `produtos` e `imagens` (script completo em [`sql/banco.sql`](sql/banco.sql)):

```sql
CREATE TABLE IF NOT EXISTS usuarios(
    id_usuario int AUTO_INCREMENT PRIMARY KEY,
    nome varchar(100) NOT NULL,
    email varchar(150) NOT NULL UNIQUE,
    senha varchar(255) NOT NULL
);
```

A coluna `senha` tem 255 caracteres porque guarda o **hash** da senha (gerado por `password_hash`), e não a senha digitada.

## Métodos

| Método | O que faz | Retorno |
|---|---|---|
| `conecta()` | Abre a conexão PDO com o banco `loja_etim` | `true` / `false` |
| `inserirUsuario($nome, $email, $senha)` | Insere um usuário (a senha é gravada como hash) | `true` / `false` |
| `alterarUsuario($id, $nome, $email, $senha = "")` | Altera nome e e-mail; só troca a senha se uma nova for informada | `true` / `false` |
| `apagarUsuario($id)` | Apaga o usuário pelo id | `true` / `false` |
| `listarUsuarios()` | Lista todos os usuários em ordem de nome (sem a senha) | array de usuários |
| `buscarUsuarioPorId($id)` | Busca um usuário para preencher o formulário de alteração | array ou `false` |
| `emailCadastrado($email, $id_ignorado = 0)` | Diz se o e-mail já pertence a outro usuário (evita duplicar) | `true` / `false` |
| `logar($email, $senha)` | Confere e-mail e senha com `password_verify` | array do usuário ou `false` |

## Código da classe

```php
<?php
class Usuario{
    private $id_usuario;
    private $nome;
    private $email;
    private $senha;
    private $pdo;

    public function conecta(){
        $dns = "mysql:dbname=loja_etim;host=localhost;charset=utf8mb4";
        $user = "root";
        $pass = "";
        try {
            $this->pdo = new PDO($dns, $user, $pass);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function inserirUsuario($nome, $email, $senha){
        //a senha nunca e gravada pura: password_hash gera um hash seguro
        $sql = "INSERT INTO usuarios SET nome = :n, email = :e, senha = :s";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":s", password_hash($senha, PASSWORD_DEFAULT));

        return $sql->execute();
    }

    public function alterarUsuario($id, $nome, $email, $senha = ""){
        //se a senha vier vazia, mantem a senha atual
        if (!empty($senha)) {
            $sql = "UPDATE usuarios SET nome = :n, email = :e, senha = :s WHERE id_usuario = :id";
        } else {
            $sql = "UPDATE usuarios SET nome = :n, email = :e WHERE id_usuario = :id";
        }
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);
        if (!empty($senha)) {
            $sql->bindValue(":s", password_hash($senha, PASSWORD_DEFAULT));
        }

        return $sql->execute();
    }

    public function apagarUsuario($id){
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);

        return $sql->execute();
    }

    public function listarUsuarios(){
        //a senha (hash) nao e devolvida na listagem
        $sql = "SELECT id_usuario, nome, email FROM usuarios ORDER BY nome";
        $sql = $this->pdo->prepare($sql);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarUsuarioPorId($id){
        $sql = "SELECT id_usuario, nome, email FROM usuarios WHERE id_usuario = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id, PDO::PARAM_INT);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function emailCadastrado($email, $id_ignorado = 0){
        //checa se o e-mail ja pertence a outro usuario
        //(no alterar, passa o id do proprio usuario para ele ser ignorado)
        $sql = "SELECT id_usuario FROM usuarios WHERE email = :e AND id_usuario <> :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $id_ignorado, PDO::PARAM_INT);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function logar($email, $senha){
        //busca o usuario pelo e-mail e confere a senha com o hash gravado
        $sql = "SELECT id_usuario, nome, email, senha FROM usuarios WHERE email = :e";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":e", $email);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            unset($usuario['senha']);
            return $usuario;
        }

        return false;
    }
}
```

## Como usar

Todas as páginas usam a classe do mesmo jeito que a `Produto`: cria o objeto, chama `conecta()` e depois o método.

### Login (`index.php`)

```php
require 'classes/Usuario.class.php';
session_start();

$u = new Usuario();
$u->conecta();
$usuario = $u->logar($_POST['email'], $_POST['senha']);

if ($usuario) {
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    header("Location: principal.php"); //vai para a pagina principal com a NavBar
    exit;
}
```

### Inserir (`usuario_inserir.php`)

```php
$u = new Usuario();
$u->conecta();
if (!$u->emailCadastrado($email)) {
    $u->inserirUsuario($nome, $email, $senha);
}
```

### Alterar (`usuario_atualizar.php`)

```php
$u = new Usuario();
$u->conecta();
$usuario = $u->buscarUsuarioPorId($id);          //preenche o formulario
if (!$u->emailCadastrado($email, $id)) {         //ignora o proprio usuario
    $u->alterarUsuario($id, $nome, $email, $senha); //senha vazia = mantem a atual
}
```

### Apagar (`usuario_deletar.php`)

```php
$u = new Usuario();
$u->conecta();
$u->apagarUsuario($id);
```

### Listar (`usuarios.php`)

```php
$u = new Usuario();
$u->conecta();
foreach ($u->listarUsuarios() as $usuario) {
    echo $usuario['nome'] . " - " . $usuario['email'];
}
```

## Observações

- **Senha com hash:** `password_hash()` ao gravar e `password_verify()` no login. Nem quem acessa o banco vê a senha real.
- **SQL injection:** todos os valores passam por `bindValue`, nunca são colados direto no SQL.
- **E-mail único:** a coluna é `UNIQUE` no banco e o método `emailCadastrado()` avisa antes de tentar gravar.
- **Usuário inicial:** o `banco.sql` cria o login `admin@etim.com` com a senha `admin123`, para o primeiro acesso.
