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
