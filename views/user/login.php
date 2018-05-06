
                <?php
                if(!$reginit) // при инициализации форм игнорим ошибки
                {
                    if (isset($errors) && is_array($errors)): ?>
                        <ul class='sgcerr'>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;
                }
                ?>


<div class='sgregf' <?php if($trylogin) echo "style='display:none;'"; ?> >
<span>Зарегистрируйтесь</span><br>
<form action="#" method="post" id='sgcommregform'>
    <input type="text" name="name" placeholder="Ваше имя" value="<?php echo $name; ?>"/>
    <input type="email" name="email" placeholder="Ваш E-mail" value="<?php echo $email; ?>"/>
    <input type="password" name="password" placeholder="Пароль" value="<?php echo $password; ?>"/>
    <input type="button" name="sgcommlogin" class="sgcommreg" value="Регистрация" />
</form>
</div>

<div class='sgloginf'  <?php if($trylogin) echo "style='display:block;'"; ?> >
<span>Авторизируйтесь</span><br>
<form action="#" method="post" id='sgcommlform'>
    <input type="email" name="email" placeholder="Ваш E-mail" value="<?php echo $email; ?>"/>
    <input type="password" name="password" placeholder="Пароль" value="<?php echo $password; ?>"/>
    <input type="button" name="sgcommlogin" class="sgcommlogin" value="Вход" />
</form>
</div>

<?php
if(!isset($_POST) || true){?>
<input type='checkbox' id='sgvalreadireg' name='alreadyreg' /> - <label for='sgvalreadireg'>Уже зарегистрирован(а)</label>
<?php } ?>