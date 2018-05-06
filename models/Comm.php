<?php

/**
 * Класс Comm - модель для работы с комментариями
 */
class Comm
{

    public static function getCommByUrl($url)
    {
        //обрезаем якоря
        $urltmp=explode("#",$url);
        $url=$urltmp[0];
        $db = Db::getConnection();
        $id_url=Comm::getCommUrlId($url); //ид на ссылку страницы комментариев

        
        if($id_url)
        {
            $sql='SELECT comm.*, user.name, user.id as id_user
            FROM comm
            LEFT JOIN user ON user.id=comm.user
            WHERE comm.id_url = :id_url AND comm.status="1"
            ORDER BY comm.id ASC';
            
            $result = $db->prepare($sql);
            $result->bindParam(':id_url', $id_url, PDO::PARAM_STR);
            $result->execute();

            $commList = array();
            while ($row = $result->fetch()) {
                $commList[ $row['id_parrent'] ][]=$row;
                $commList['conter']++;
            }
        }
        Comm::addCommUrl($url);
        
        return $commList;
    }

// добавить урл в базу
    public static function addCommUrl($url)
    {

        $db = Db::getConnection();
        if(!Comm::getCommUrlId($url))
        {

            $sql = 'INSERT INTO urls (id, url) VALUES ("null", :url)';

            $result = $db->prepare($sql);
            $result->bindParam(':url', $url, PDO::PARAM_STR);
            return $result->execute();
        }
        else return true;
    }

//удалить комментарий (скрыть от показа ,  статус = 0)
    public static function delComment($id_comm, $userId)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = "UPDATE comm
            SET
                status = 0
            WHERE id = :id AND user = :user";
        $result = $db->prepare($sql);
        $result->bindParam(':user', $userId, PDO::PARAM_INT);
        $result->bindParam(':id', $id_comm, PDO::PARAM_INT);
        return $result->execute();
    }
    
//получить ИД страниы по ее урлу
    public static function getCommUrlId($url)
    {
        // Соединение с БД
        $db = Db::getConnection();
        $sql = 'SELECT id from urls  WHERE url = :url';
        $result = $db->prepare($sql);
        $result->bindParam(':url', $url, PDO::PARAM_STR);

        $result->execute();
        $idurl=$result->fetch();

        return $idurl['id'];
    }




    public static function addComment($id_parrent,   $url, $comment)
    {
        // Соединение с БД
        $db = Db::getConnection();

        $rate=$rate_cnt=0;//рейтинг по нолям у нового комментария
        $tmp_arr=array();
        $tmp_arr['id']=0;
        $tmp_arr['id_parrent']=$id_parrent;
        $tmp_arr['id_url']=Comm::getCommUrlId($url);         //получить ID ссылки на коментируемую страницу
        $tmp_arr['date']=time();
        $tmp_arr['userId']=User::checkLogged();
        $tmp_arr['rate']=$rate;
        $tmp_arr['rate_cnt']=$rate_cnt;
        $tmp_arr['comment']=$comment;
        $tmp_arr['status']=1; // активность комментария после добавления
        

        $sql = 'INSERT INTO comm (id_parrent, id_url, date, user, rate, rate_cnt, comment,status)'
                . 'VALUES (:id_parrent, :id_url, :date, :user, :rate, :rate_cnt, :comment,:status)';
        
        $result = $db->prepare($sql);
        $result->bindParam(':id_parrent', $tmp_arr['id_parrent'], PDO::PARAM_INT);
        $result->bindParam(':id_url', $tmp_arr['id_url'], PDO::PARAM_INT);
        $result->bindParam(':date', $tmp_arr['date'], PDO::PARAM_INT);
        $result->bindParam(':user', $tmp_arr['userId'], PDO::PARAM_INT);
        $result->bindParam(':rate', $tmp_arr['rate'], PDO::PARAM_INT);
        $result->bindParam(':rate_cnt', $tmp_arr['rate_cnt'], PDO::PARAM_INT);
        $result->bindParam(':comment', $tmp_arr['comment'], PDO::PARAM_STR);
        $result->bindParam(':status', $tmp_arr['status'], PDO::PARAM_INT);
        
        if($result->execute())
        {
            $tmp_arr['id']=$db->lastInsertId();
            $user=User::getUserById($tmp_arr['userId']);
            $tmp_arr['name']=$user['name'];
            return $tmp_arr; //возвращаем ответ

        }
        return false;

    }



    public static function saveComment($id_comm, $comment, $userId)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = "UPDATE comm
            SET
                comment = :comment
            WHERE id = :id AND user = :user";
            
        $result = $db->prepare($sql);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        $result->bindParam(':user', $userId, PDO::PARAM_INT);
        $result->bindParam(':id', $id_comm, PDO::PARAM_INT);
        return $result->execute();
    }

}
