<?php

    namespace Models;

    use \Core\Model;
    use \Models\JWT;

    class User extends Model
    {
        private $id_user;

        public function create($data)
        {
            if (!$this->emailExists($data['email'])) {
                $hash = password_hash($data['password'], PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
                $sql = $this->db->prepare($sql);
                $sql->bindValue(':name', $data['name']);
                $sql->bindValue(':email', $data['email']);
                $sql->bindValue(':password', $hash);
                $sql->execute();

                $this->id_user = $this->db->lastInsertId();

                return true;
            }

            return false;
        }

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

        public function createJWT()
        {
            $jwt = new JWT();
            return $jwt->create(['id_user' => $this->id_user]);
        }

        private function emailExists($email)
        {
            $sql = "SELECT id FROM users WHERE email = :email";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':email', $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                return true;
            }

            return false;
        }
    }
