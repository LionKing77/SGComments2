<?php
if($newcomm)
{
            //подправим даты
            if($newcomm['date']>time()-86400) $newcomm['date']=date("d-m-Y H:i:s",$newcomm['date']);
            else $newcomm['date']=date("d-m-Y",$newcomm['date']);
        
?>
            <div class='sgcom sgcom<?php echo $newcomm['id']; ?>' >
                <span class='sgcomauth' ><?php echo $newcomm['name']; ?></span>
                <span class='sgcomdate' ><?php echo $newcomm['date']; ?></span>

                        <?php if($newcomm['id_user']==$userId)
                        echo "<span class='sgcctrl'>
                        <a href='#' title='Править свой пост' class='editmycom'><img src='".COMM_SERVER."images/edit_comm_32.png' /></a>
                        <a href='#' title='Сохранить изменения' class='savemycom' rel='".$newcomm['id']."'><img src='".COMM_SERVER."images/save_32.png' /></a>
                        <a href='#' title='Удалить пост' class='delmycom' rel='".$newcomm['id']."'><img src='".COMM_SERVER."images/delete.png' /></a>
                        <img class='spin' src='".COMM_SERVER."images/spinner.gif' title='Обработка' />
                        </span>
                        "; ?>
                        
                <div class='sgconecomm'><?php echo trim(nl2br($newcomm['comment'])); ?></div>
                <a class='sgcanswer' data-par='<?php echo $newcomm['id']; ?>' href='#'>Ответить</a>
                <div class='sgcsubans sgcsubans<?php echo $newcomm['id']; ?>'></div>
            </div>
<?php
}
?>