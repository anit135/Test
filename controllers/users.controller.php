<?php

class UsersController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['user_name']) && isset($_POST['user_password'])) {

            $user = $this->model->getByUser($_POST['user_name']);

            $password = md5($_POST['user_password']);
            if ($user && $password == $user[0]['user_password']) {
                Session::set('login', $user[0]['user_name']);
                Session::set('role', $user[0]['user_role']);
            }
            Router::redirect('/admin/');
        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin');
    }
}