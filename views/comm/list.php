<?php
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
    global $userId;
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
                    //$_SESSION['sgclast_comm']=$key['id_parrent']; // родитель последнего поста, сюда вывод подгружаемых постов
                    //echo "$k. . $counter_comments. ($showfrom-$showto) - ".$_SESSION['sgccurr_page']. " id-post =". $key['id']."<br />";

                    //подправим даты
                    if($key['date']>time()-86400) $key['date']=date("d-m-Y H:i:s",$key['date']);
                    else $key['date']=date("d-m-Y",$key['date']);

                    ?>
                    <div class='sgcom deep<?php echo $_SESSION['sgccurr_deep']-1; ?>' >
                        <span class='sgcomauth' ><?php echo $key['name']; ?></span>
                        <span class='sgcomdate' ><?php echo $key['date']; ?></span>

                        <?php if($key['id_user']==$userId)
                        echo "<span class='sgcctrl'>
                        <a href='#' title='Править свой пост' class='editmycom'><img src='".COMM_SERVER."images/edit_comm_32.png' /></a>
                        <a href='#' title='Сохранить изменения' class='savemycom' rel='".$key['id']."'><img src='".COMM_SERVER."images/save_32.png' /></a>
                        <a href='#' title='Удалить пост' class='delmycom' rel='".$key['id']."'><img src='".COMM_SERVER."images/delete.png' /></a>
                        <img class='spin' src='".COMM_SERVER."images/spinner.gif' title='Обработка' />
                        </span>
                        "; ?>
                        
                        <span class='sgcomrate' >
<?php
require (ROOT . '/views/rate/post.php');
?>
                        </span>
                        
                        <div class='sgconecomm'><?php echo trim(nl2br($key['comment'])); ?></div>
                        <?php
                        //запрещаем кнопку ОТВЕТИТЬ если глубоко)
                        if($_SESSION['sgccurr_deep'] < DEEP_COMM) {
                        ?>
                        <a class='sgcanswer' data-par="<?php echo $key['id']; ?>" href='#'>Ответить</a>
                        <?php
                        }
                        ?>
                        <div class='sgcsubans sgcsubans<?php echo $key['id']; ?>'" ></div>
                    </div>
                    
                    <?php
                    
                }
                        //подуровни постов
                         if($_SESSION['sgccurr_deep'] <= DEEP_COMM) commdraw($commList,$key['id']);


                

            }
        }
        $_SESSION['sgccurr_deep']--;
}
?>