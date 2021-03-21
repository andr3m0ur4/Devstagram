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
    }
    