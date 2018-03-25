<?php

class Task extends Model
{

    public function getList()
    {
        $sql = "SELECT * FROM `Tasks`";
        return $this->db->query($sql);
    }

    public function getSortList($task_start, $task_end, $sort)
    {
        $task_start = $this->db->escape($task_start);
        $task_end = $this->db->escape($task_end);

        if (!empty($sort)) {
            $sort = "ORDER BY " . $this->db->escape($sort);
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `Tasks` {$sort} LIMIT $task_start, $task_end";
        return $this->db->query($sql);
    }

    public function getCount()
    {
        $sql = "SELECT FOUND_ROWS() as rows";
        return $this->db->query($sql);
    }

    public function create($data)
    {
        if (!isset($data['user_name']) || !isset($data['user_email']) || !isset($data['task_text'])) {
            return false;
        }
        $name = $this->db->escape($data['user_name']);
        $email = $this->db->escape($data['user_email']);
        $text = $this->db->escape($data['task_text']);
        $img = $this->db->escape($data['task_img']);

        $sql = "INSERT INTO `Tasks` (`user_name`, `user_email`, `task_text`, `task_img`) 
                  VALUES ('$name', '$email', '$text', '$img')";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM tasks WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function admin_save($data, $id)
    {
        if (!isset($data)) {
            return false;
        }
        $id = (int)$id;
        $text = $this->db->escape($data['task_text']);
        $status = (int)$data['task_status'];

        if ($id) {
            $sql = "UPDATE `Tasks` SET `task_text`='$text', `task_status`='$status' WHERE `id`=$id";
            return $this->db->query($sql);
        }
    }

}