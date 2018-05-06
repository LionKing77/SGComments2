<?php

/**
 * Контроллер CommController
 */
 
class CommController
{

    //добавить комментарий
    public function actionAdd(   $comment ,$id_parrent, $url)
    {
        if($comment && $url)
        {
            if($newcomm=Comm::addComment($id_parrent, $url, $comment))
            {
                require_once(ROOT . '/views/comm/onecomm.php'); //вывод последнего коментария (для одного коммента)
            }
        }
        return true;
    }
    
    //удалить комментарий
    public function actionDel(  $id_comm)
    {
        $userId=User::checkLogged();
        if($userId && $id_comm)
        {
            if(Comm::delComment($id_comm, $userId))
            {
             echo "Удалено";
            }
        }
        return true;
    }
    
    //сохранить комментарий
    public function actionSave(  $id_comm, $comment)
    {
        $userId=User::checkLogged();
        if($userId && $id_comm && $comment)
        {
            if(Comm::saveComment($id_comm, $comment, $userId))
            {
             echo "Сохранено";
            }
        }
        return true;
    }
    

    // инициализация формы, возвращает юзера, форму регистрации/авторизации, посты
    public function actionInit($url=false)
    {
        $resultarr=array();
        global $userId;
        $userId=User::checkLogged();
        //проверка авторизации/иначе форма регстрации/аторизации
        if(!$userId )
        {
            $name=false;
            $email = false;
            $password = false;
            $trylogin=false; // скрыть/показать нужную форму
            ob_start();
            require_once(ROOT . '/views/user/login.php');
            $resultarr['loginform']= ob_get_clean();
        }
        else
        {
            $user = User::getUserById($userId);
            $userName = $user['name'];
            $resultarr['hello']= "Привет, $userName. <a href='#' class='sgclogout'>Выход >> </a>";
        }

        ob_start();
        // форма добавления комментария
        require_once(ROOT . '/views/comm/add.php');
        $resultarr['addcomm']= ob_get_clean();
        // Подключаем список комментов
        $commList=Comm::getCommByUrl($url);

            $_SESSION['sgccurr_page']=0; // в сесии храним текущую страницу
            $_SESSION['sgccurr_deep']=0; // ..и глубину постов

        if($commList['conter'] > COMM_PER_PAGE)
        {
            $resultarr['getmore']='<a href="#" class="sgcshowmore" >Показать еще</a>';
        }

        ob_start();
        require_once(ROOT . '/views/comm/list.php');
        $resultarr['commlist']= ob_get_clean();
        echo json_encode($resultarr);
        return true;
    }
    
    // список комментариев (для пагинации)
    public function actionList($url=false)
    {
        global $userId;
        $userId=User::checkLogged();

        ob_start();
        $commList=Comm::getCommByUrl($url);
            // в сесии храним текущую страницу
            $_SESSION['sgccurr_page']++;
            
        if( $commList['conter']  >    COMM_PER_PAGE * ($_SESSION['sgccurr_page']+1))
        {
            $resultarr['getmore']='<a href="#" class="sgcshowmore" >Показать еще</a>' .$commList['conter']. " - ". COMM_PER_PAGE * $_SESSION['sgccurr_page'];
        }
        else $resultarr['getmore']='';
        
        require_once(ROOT . '/views/comm/list.php');
        $comm_add_form_list= ob_get_clean();
        
        $resultarr['commlist']=$comm_add_form_list;

        echo json_encode($resultarr);
        return true;
    }



}
