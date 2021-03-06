<?php


function my_autoload($class_name) {
    $lib_path = ROOT.DS.'lib'.DS.strtolower($class_name).'.class.php';
    $controllers_path = ROOT.DS.'controllers'.DS.str_replace('controller', '', strtolower($class_name)).'.controller.php';
    $model_path = ROOT.DS.'models'.DS.strtolower($class_name).'.php';

//    echo 'autoload '.$class_name.'<br>';
//    echo $lib_path.'<br>';
//    echo $controllers_path.'<br>';
//    echo $model_path.'<br>';

    if (file_exists($lib_path)) {
        require_once($lib_path);
    } elseif (file_exists($controllers_path)) {
        require_once($controllers_path);
    } elseif (file_exists($model_path)) {
        require_once($model_path);
    } else {
        throw new Exception('Failed to include class: '.$class_name);
    }
}

spl_autoload_register('my_autoload');

require_once(ROOT.DS.'config'.DS.'config.php');