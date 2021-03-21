<?php

    namespace Models;

    use \Core\Model;
    use \Models\JWT;
    use \Models\Photo;

    class User extends Model
    {
        private $id_user;

        public function getId()
        {
            return $this->id_user;
        }

        public function getUser($id)
        {
            $data = [];

            $sql = "SELECT * FROM users WHERE id = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(\PDO::FETCH_OBJ);

                $photo = new Photo();

                if (!empty($data->avatar)) {
                    $data->avatar = "/media/avatar/{$data->avatar}";
                } else {
                    $data->avatar = '/media/avatar/default.jpg';
                }

                $data->following = $this->getFollowingCount($id);
                $data->followers = $this->getFollowersCount($id);
                $data->photos_count = $photo->getPhotosCount($id);
            }

            return $data;
        }

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

        public function validateJWT($token)
        {
            $jwt = new JWT();
            $value = $jwt->validate($token);

            if (isset($value->id_user)) {
                $this->id_user = $value->id_user;
                return true;
            }

            return false;
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

        public function getFollowingCount($id_user)
        {
            $sql = "SELECT COUNT(*) AS counter FROM following WHERE id_user_following = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id_user);
            $sql->execute();
            $result = $sql->fetch(\PDO::FETCH_OBJ);

            return $result->counter;
        }

        public function getFollowersCount($id_user)
        {
            $sql = "SELECT COUNT(*) AS counter FROM following WHERE id_user_followed = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id_user);
            $sql->execute();
            $result = $sql->fetch(\PDO::FETCH_OBJ);

            return $result->counter;
        }
    }
