<?php

/**
 * Класс Rate - модель для работы с рейтингом
 */
class Rate
{



// добавить урл в базу
    public static function addRate($id_comm, $rate, $userId)
    {
        $db = Db::getConnection();

        //получить рейтиги поста

            $sql='SELECT user, rate, rate_cnt
            FROM comm
            WHERE id = :id AND status="1" AND user <> :user';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id_comm, PDO::PARAM_INT);
            $result->bindParam(':user', $userId, PDO::PARAM_INT);
            
            $result->execute();
            $row = $result->fetch();
            if($row && $row['user']!=$userId)
            {
            //пересчитать
                $new_rate=round(($row['rate']*$row['rate_cnt']+$rate)/($row['rate_cnt']+1),2);
                
            //сохранить
                $sql = 'UPDATE comm
                    SET
                        rate = :new_rate,
                        rate_cnt = rate_cnt+1
                    WHERE id = :id AND status="1" ';
                $result = $db->prepare($sql);
                $result->bindParam(':new_rate', $new_rate, PDO::PARAM_STR);
                //$result->bindParam(':rate_cnt', $rate_cnt, PDO::PARAM_INT);
                $result->bindParam(':id', $id_comm, PDO::PARAM_INT);
                $result->execute();

                return true;
            }
            else return false;
    }


}
