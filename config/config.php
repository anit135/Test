<?php

Config::set('site_name', 'Site Name');

Config::set('routes', array(
    'default'=>'',
    'admin'=>'admin_',
));

Config::set('default_route', 'default');
Config::set('default_controller', 'tasks');
Config::set('default_action', 'index');

//Config::set('default_layout', 'layout');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'test');