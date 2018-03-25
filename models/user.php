<?php

class User extends Model
{

    public function getByUser($user_name)
    {
        $user_name = $this->db->escape($user_name);

        $sql = "SELECT * from `Users` WHERE `user_name`='$user_name' limit 1";
        return $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }
}