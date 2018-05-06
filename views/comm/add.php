<?php
//if($userId) echo "userId=$userId";
//else echo "юзер не авторизирован";
?>

<form action='#' method='post' id='addcommform' class='addcommform0'>
<textarea placeholder='Оставьте Ваш комментарий' name='newcomment' class='newcomment'></textarea>
<input type='hidden' name='id_parrent' class='id_parrent' value='0' />
<input type='hidden' name='url' class='addformurl' value='<?php echo $url;?>' />
<input type='button' name='addcomm' class='addcomm' <?php if(!$userId) echo "disabled='disabled' title='Оставлять комментарии могут только авторизированные пользователи. Успехов..' ";?> value='Добавить комментарий' />
</form>