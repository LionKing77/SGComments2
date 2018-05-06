<?php
/*
вывод комментов в рекурсии с уровнями вложенности.. не доработан ((
*/
global $counter_comments;

//показать дерево
if($commList)
{
    commdraw($commList,0); // начинаем с корневых постов
}
else echo "<span class='sgcempty'>Пусто. Вы можете быть первым!</span>";


function commdraw($commList,$id_parrent)
{
    global $counter_comments;
    $_SESSION['sgccurr_deep']++;
    
    //$cnt=count($commList[$id_parrent]);

                $showfrom=$_SESSION['sgccurr_page'] * COMM_PER_PAGE;
                $showto=$_SESSION['sgccurr_page'] * COMM_PER_PAGE  +  COMM_PER_PAGE;
                
        if(isset($commList[$id_parrent]) && is_array($commList[$id_parrent]))
        {
            foreach($commList[$id_parrent] as $k=>$key)
            {
                //выодим если подходящая страница
                $counter_comments++;

                
                $allow_show_comm=false;
                if($counter_comments>$showfrom && $counter_comments<=$showto)
                    $allow_show_comm=true;

                if($allow_show_comm )
                {
                    $_SESSION['sgclast_comm']=$key['id_parrent']; // родитель последнего поста, сюда вывод подгружаемых постов
                    //echo "$k. . $counter_comments. ($showfrom-$showto) - ".$_SESSION['sgccurr_page']. " id-post =". $key['id']."<br />";

                    //подправим даты
                    if($key['date']>time()-86400) $key['date']=date("d-m-Y H:i:s",$key['date']);
                    else $key['date']=date("d-m-Y",$key['date']);

                    ?>
                    <div class='sgcom sgcom<?php echo $key['id']; ?>' >
                        <span class='sgcomauth' ><?php echo $key['name']; ?></span>
                        <span class='sgcomdate' ><?php echo $key['date']; ?></span>
                        <div class='sgconecomm'><?php echo $key['comment']; ?></div>
                        <?php
                        //запрещаем кнопку ОТВЕТИТЬ если глубоко)
                        if($_SESSION['sgccurr_deep'] < DEEP_COMM) {
                        ?>
                        <a class='sgcanswer' data-par="<?php echo $key['id']; ?>" href='#'>Ответить</a>
                        <?php
                        }
                        ?>
                        <div class='sgcsubans sgcsubans<?php echo $key['id']; ?>'" ></div>
                    <?php
                }
                        //подуровни постов
                         if($_SESSION['sgccurr_deep'] <= DEEP_COMM) commdraw($commList,$key['id']);

                if($allow_show_comm)
                {
                        if(count($commList[$k])>0 && $counter_comments>$showto)
                        {
                        $_SESSION['sgclast_comm']=$commList[$k][0]['id'];
                        //echo "<div class='sgcom sgcom".$commList[$k][0]['id']."'></div>";
                        //  break;
                        }

                    //</div>
                    ?>
                    
                    <?php
                }

                // если все страницы исчерпаны заканчиваем цыкл
               /* if($counter_comments>$showto)
                {
                    //проверяем не последний ли подпост? если последний отображен закрываем див sgcom
                    if( !isset( $commList[$id_parrent][$k+1] ) )
                    {
                        echo "</div>";
                    }
                    else  $_SESSION['sgclast_comm']=$commList[$id_parrent][$k+1]['id_parrent'];
                    break;
                }*/
                

            }
        }
        $_SESSION['sgccurr_deep']--;
        echo "</div>";
        //else echo "постов с парентом ". $id_parrent. "нет";
}
?>