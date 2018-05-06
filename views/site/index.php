<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SoftGroup Comments Plugin </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content=">SoftGroup Comments Plugin" />

</head>
<body>
<style>
p,b{font-size:17px;}
</style>

<div style='max-width:800px; margin:50px auto 0;text-align:center'>
<img src='images/sgcomm.png' alt='' title='' />
<h1>SoftGroup Comments Plugin</h1>
<br>
<b>Для работы плагина необходимо подключить Jquery</b> 
<p>(  например &#8249;script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" /&#8250;  )</p>

<br><br>
<b>На Вашем сайте нужно вставить код:</b><br>
<p>
&#8249;script src="http://comm.vdoske.in.ua/js.js" /&#8250;<br>
&#8249;div  id="sgcomm" data-param="" &#8250;&#8249;/div&#8250;<br>
, где data-param=URL страницы...
</p>


<br><br>
Архив <a href='http://comm.vdoske.in.ua/_zip.zip'>тут</a>, на <a href='https://github.com/LionKing77/SGComments.git' target='_blank'>GitHub</a> и  <a href='http://comm.vdoske.in.ua/_demo/post-leftsidebar.html' target='_blank'> ДЕМО</a>
<br><br>Ниже, пример работы плагина<br>


<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript" src="http://comm.vdoske.in.ua/js.js?<?php echo time();?>"></script>
<div style='margin:50px auto 30px; max-width:500px' id="sgcomm" data-key='444' data-param="<?php echo $_SERVER['REQUEST_URI'];?>" data-deep="2"></div>
</div>

</body>
</html>