<?php

class TasksController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Task;
    }

    public function index()
    {
        $task_in_page = 3;
        $task_arm = 3;

        if (empty($_GET)) {
            $page_now = 1;
            $sort_now = null;
        } else {
            $page_now = !empty($_GET['page_now']) ? $_GET['page_now'] : 1;
            $sort_now = !empty($_GET['sort_now']) ? $_GET['sort_now'] : null;
            if (strcmp($sort_now, 'task_status') === 0) {
                $sort_now .= ' DESC';
            }
        }
        $page_start = $page_now != 1 ? ($page_now - 1) * $task_in_page : 0;
        $this->data['tasks'] = $this->model->getSortList($page_start, $task_arm, $sort_now);

        $sql_tasks_count = $this->model->getCount();
        $tasks_count = $sql_tasks_count[0]['rows'];

        $this->data['count'] = ceil($tasks_count / $task_in_page);
        $this->data['pag_now'] = $page_now;

        if (!empty($_GET) && !empty($_GET['page_now'])) {
            return $this->data;
        }
    }

    public function create()
    {
        if (isset($_POST['save'])) {
            $data['user_name'] = $_POST['user_name'];
            $data['user_email'] = $_POST['user_email'];
            $data['task_text'] = $_POST['task_text'];
            if (!empty($_FILES['task_img']['name'])) {
                $task_img_path = IMG_PATH . basename($_FILES['task_img']['name']);
                if (move_uploaded_file($_FILES['task_img']['tmp_name'], $task_img_path)) {
                    $image = new SimpleImage();
                    $image->load($task_img_path);
                    $image->resize(320, 240);
                    $image->save($task_img_path);
                    $data['task_img'] = basename($_FILES['task_img']['name']);
                    $result = true;
                } else $result = false;
            } else {
                $data['task_img'] = null;
                $result = true;
            }
            if ($result) {
                if ($this->model->create($data)) {
                    Session::setFlash('Task saved!');
                }
//                    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/tasks/index';
//                    header("Location: $redirect");
            } else {
                throw new Exception('No correct image file');
            }
        }
    }

    public function admin_index()
    {
        $this->data['tasks'] = $this->model->getList();
    }

    public function admin_edit()
    {
        if (isset($this->params[0])) {
            $this->data['task'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/tasks/');
        }
    }

    public function admin_save()
    {
        if (isset($_POST['save'])) {
            $id = $_POST['id'];
            $data['task_text'] = $_POST['task_text'];
            $data['task_status'] = isset($_POST['task_status']) ? 1 : 0;
            $result = $this->model->admin_save($data, $id);
            if ($result) {
                Session::setFlash('Tasks edited.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/tasks/');
        }
    }

    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getById($alias);
        }
    }
}