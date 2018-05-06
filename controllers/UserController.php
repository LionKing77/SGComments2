<?php

/**
 * Контроллер UserController
 */
class UserController
{
    /**
     * Action для страницы "Регистрация"
     */
    public function actionRegister()
    {
        // Переменные для формы
        $trylogin=false; // скрыть/показать нужную форму
        $userId = false; //ИД юзера

        $name = false;
        $email = false;
        $password = false;

            // Если форма отправлена
            // Получаем данные из формы
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
                // Флаг ошибок
                $errors = false;

                if (!User::checkName($name)) {
                    $errors[] = 'Имя не может быть короче 2 символов';
                }
                if (!User::checkEmail($email)) {
                    $errors[] = 'Неверный email';
                }
                if (!User::checkPassword($password)) {
                    $errors[] = 'Пароль не может быть короче 5-ти символов';
                }

                if (User::checkEmailExists($email)) {
                    $errors[] = 'Email занят';
                }

                //var_dump($errors);
                //exit;

                if ($errors == false) {
                    // Если ошибок нет
                    // Регистрируем пользователя
                    $userId = User::register($name, $email, $password);
                    //если зарегистрировали нужно авторизорвать сразу
                    if($userId) User::auth($userId);

                }



        // Подключаем вид
        if(!$userId)
        {
            ob_start();
            require_once(ROOT . '/views/user/login.php');
            $resultarr['loginform']= ob_get_clean();
        }
        else
        {
            $user=User::getUserById($userId);
            $resultarr['hello']= "Привет, ".$user['name'].". <a href='#' class='sgclogout'>Выход >> </a>";
        }
        echo json_encode($resultarr);
        return true;
        
        
        
    }
    
    /**
     * Action для страницы "Вход на сайт"
     */
    public function actionLogin()
    {
        // Переменные для формы
        //var_dump($_POST);
        $trylogin=true; // скрыть/показать нужную форму

        $email = false;
        $password = false;

            // Если форма отправлена 
            // Получаем данные из формы
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;
            // Валидация полей

            if (!User::checkEmail($email)) {
                $errors[] = 'Неверный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не может быть короче 5-ти символов';
            }

            // Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);
            //echo "**$userId**";
            

            if ($userId == false) {
                //проверитьт такой
                $isemail=User::checkEmailExists($email);
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Заполните все поля без ошибок';
            } else {
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($userId);
                // Перенаправляем пользователя в закрытую часть - кабинет 
                //header("Location: /cabinet");
             }


        // Подключаем вид
        if(!$userId)
        {
            ob_start();
            require_once(ROOT . '/views/user/login.php');
            $resultarr['loginform']= ob_get_clean();
        }
        else
        {
            $user=User::getUserById($userId);
            $resultarr['hello']= "Привет, ".$user['name'].". <a href='#' class='sgclogout'>Выход >> </a>";
        }
        echo json_encode($resultarr);
        return true;
    }

    /**
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout()
    {
        // Стартуем сессию
        //session_start();
        
        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);

            ob_start();
            require_once(ROOT . '/views/user/login.php');
            $resultarr['loginform']= ob_get_clean();
            echo $resultarr['loginform'];
            return true;
            
        // Перенаправляем пользователя на главную страницу
        //header("Location: /");
        
        // показать формы рег/авто
        // сменить привет гость
        // скрыть форму комментариев
    }

}
