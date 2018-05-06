<?php

/**
 * Контроллер RateController
 */
 
class RateController
{

    //добавить комментарий
    public function actionAdd(  $id_comm, $rate)
    {
        $userId=User::checkLogged();
        if(!$userId)
        {
            echo "Оценивать комментарии могут только авторизированные пользователи";
            return false;

        }
        if($id_comm && $rate && $userId) //только авторизированным
        {
            if($rrr=Rate::addRate($id_comm, $rate, $userId))
            {
                //require_once(ROOT . '/views/comm/onecomm.php'); //вывод последнего коментария (для одного коммента)
                $rate_res= "Благодарим! Ваша оценка $rate учтена !";
                $rate_res_cuca='1';
            }
            else $rate_res= "Свои комментарии оценивать нельзя )";
        }
        //else echo ""
        $resultarr['info']=$rate_res; //текст, резульатат доабвбления ретйинга
        $resultarr['cuca']=$rate_res_cuca; // можно ли писать куку?
        echo json_encode($resultarr);
        
        return true;
    }

}
