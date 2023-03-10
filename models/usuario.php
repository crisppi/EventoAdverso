<?php
class Usuario
{

    public $id_usuario;
    public $usuario_user;
    public $email_user;
    public $ativo_user;
    public $nivel_user;
    public $senha_user;
    public $hash_senha;
    public $token;

    public function getFullName($user)
    {
        return $user->name . " " . $user->lastname;
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(50));
    }

    public function generatePassword($senha_user)
    {
        return password_hash($senha_user, PASSWORD_DEFAULT);
    }

    public function imageGenerateName()
    {
        return bin2hex(random_bytes(60)) . ".jpg";
    }
}

interface UserDAOInterface
{

    public function buildUser($data);
    public function create(Usuario $usuario);
    public function update(Usuario $usuario);
    public function verifyToken($protected = false);
    public function setTokenToSession($token, $redirect = true);
    public function findByEmail($email);
    public function findById_user($id_usuario);
    public function findByToken($token);
    public function destroy($id_usuario);
    public function changePassword(Usuario $user);
    public function findGeral();
    public function findById_Login($username, $password);

    public function selectAllUsuario($where = null, $order = null, $limit = null);
    public function QtdUsuario();
}
