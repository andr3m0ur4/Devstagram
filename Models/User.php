<?php

    namespace Models;

    use \Core\Model;

    class User extends Model
    {
        private $id_user;

        public function checkCredentials($email, $password)
        {
            $sql = "SELECT id, password FROM users WHERE email = :email";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':email', $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $user = $sql->fetch(\PDO::FETCH_OBJ);

                if (password_verify($password, $user->password)) {
                    $this->id_user = $user->id;

                    return true;
                }
            }

            return false;
        }
    }
