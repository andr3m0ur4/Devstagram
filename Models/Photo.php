<?php

    namespace Models;

    use \Core\Model;

    class Photo extends Model
    {
        public function getPhotosCount($id_user)
        {
            $sql = "SELECT COUNT(*) AS counter FROM photos WHERE id_user = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id_user);
            $sql->execute();
            $result = $sql->fetch(\PDO::FETCH_OBJ);

            return $result->counter;
        }

        public function deleteAll($id_user)
        {
            $sql = "DELETE FROM comments WHERE id_user = :id_user";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_user', $id_user);
            $sql->execute();

            $sql = "DELETE FROM likes WHERE id_user = :id_user";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_user', $id_user);
            $sql->execute();

            $sql = "DELETE FROM photos WHERE id_user = :id_user";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_user', $id_user);
            $sql->execute();
        }
    }
    