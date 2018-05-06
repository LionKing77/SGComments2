<?php
return array(
    'comm/init'  => 'comm/init', // возвращает json список форм автор/рег, добаления комментария, список комм
    'comm/list'  => 'comm/list', //список комметнариев
    'comm/add'   => 'comm/add',  // добавляем комментарий
    'comm/save'  => 'comm/save',  // сохраняем комментарий
    'comm/del'  => 'comm/del',  // удаляем комментарий (ставим статус 0 - невидимый)

    // Пользователь:
    'user/register' => 'user/register', //регистарция
    'user/login'    => 'user/login', //авторизация
    'user/logout'   => 'user/logout', // выход
    
    //рейтинг
    'rate/add'   => 'rate/add', // добавить рейтинг комментария (звездочки)

    // Главная страница
    'index.php' => 'site/index', 
    '' => 'site/index', 
);
