$(document).ready(function($) {

    var server_url='http://comm.vdoske.in.ua/';
    //var server_url='http://localhost/__softgroup_comm/';
    var sg=$('#sgcomm');
    var csstime=Date.now()


//строим каркас плагина
sg.html('<link type="text/css" rel="stylesheet" href="'+server_url+'css/style.css?'+csstime+'" />'+
'<div class="sgframe">'+ //start sgframe
'<div class="sghead"><img src="'+server_url+'images/sgcomm.png" title="SoftGroup Comments Plugin" />'+
'<span class="sgchello">Привет, Гость</span></div>'+
'<div class="sgcauth"></div>'+
'<div class="sgcaddcom"></div>'+
'<div class="sgcomments"> <div class="sgpreload"><img src="'+server_url+'images/spinner.gif" /></div></div>'+
'</div>'); // end sgframe



var siteurl=String(window.location); //комментируемая страница сайта
var deep=sg.attr('data-deep'); //не используется


//инит формы комментариев (форма аторизации, добавления комментов, список комментов....)
$.ajax({
      method:"POST",
      type: "POST",
      url: server_url+"comm/init",
        data:{url:siteurl},
     	success: function (data) {
     	  //alert(data);
     	  //$('body').html(data);
     	  var arrresult= $.parseJSON(data);
     	  $("#sgcomm .sgchello").html(arrresult['hello']);
          $("#sgcomm .sgcauth").html(arrresult['loginform']);
          $("#sgcomm .sgcaddcom").html(arrresult['addcomm']);
          $("#sgcomm .sgcomments").html(arrresult['commlist']);

          if(arrresult['getmore']) //кнопка "показать еще"
          {
            $("#sgcomm").append(arrresult['getmore']); //
          }



      }
  });



//checkbox уже зарегистриирован
$('body').on("click",'#sgvalreadireg', function(e) {
    if ($(this).is(':checked'))
    {
        $("#sgcomm .sgregf").hide();
        $("#sgcomm .sgloginf").show();
    }
    else
    {
        $("#sgcomm .sgregf").show();
        $("#sgcomm .sgloginf").hide();
    }
});

//обработка регистрации
$('body').on("click",'.sgcommreg', function(e) {
    var dform=String($('#sgcommregform').serialize());
    $.ajax({
            method:"POST",
            type: "POST",
            url: server_url+"user/register",
            data:dform,
            success: function (data) {
         	      var arrresult= $.parseJSON(data);
             	  if(arrresult['hello']) //приветствие
                        $("#sgcomm .sgchello").html(arrresult['hello']);

                  if(arrresult['loginform']) // форма, если ошибки
                        $("#sgcomm .sgcauth").html(arrresult['loginform']);
                  else //успех
                  {
                    $("#sgcomm .sgcauth").html("<span class='sgcempty sgcauthok'>Вы зарегистрированы. И авторизированы</span>");
                    timerId=setTimeout(function() { $("#sgcomm .sgcauthok").fadeOut(1000); clearTimeout(timerId);}, 3000);

                    $("#sgcomm .addcomm").removeAttr('disabled');
                    //можна обновить страницу
                    
                  }

          }
      });
});

//обработка авторизации
$('body').on("click",'.sgcommlogin', function(e) {

    var dform=String($('#sgcommlform').serialize());
    $.ajax({
          method:"POST",
          type: "POST",
          url: server_url+"user/login",
            data:dform,
         	success: function (data) {
         	      var arrresult= $.parseJSON(data);
             	  if(arrresult['hello']) //приветствие
                        $("#sgcomm .sgchello").html(arrresult['hello']);

                  if(arrresult['loginform']) // форма, если ошибки
                        $("#sgcomm .sgcauth").html(arrresult['loginform']);
                  else //успех
                  {
                    $("#sgcomm .sgcauth").html("<span class='sgcempty sgcauthok'>Успешная авторизация</span>");
                    timerId=setTimeout(function() { $("#sgcomm .sgcauthok").fadeOut(1000); clearTimeout(timerId);}, 3000);

                    $("#sgcomm .addcomm").removeAttr('disabled');
                    //можно обновить страницу
                  }
          }
      });
});

//выход из аккаунта
$('body').on("click",'.sgclogout', function(e) {

    $.post( server_url+"user/logout",  function( data ) {
        // сменить привет гость
        $("#sgcomm .sgchello").html("Привет, Гость");
        // скрыть форму комментариев
        $("#sgcomm .addcomm").attr('disabled','disabled');
        // показать формы рег/авто
        $("#sgcomm .sgcauth").html(data);
    });
    return false;
});


$('body').on("focus",'#sgcomm textarea.newcomment', function(e) {
    $(this).css('height' , '160px');
});


//+ комментарий
$('body').on("click",'.addcomm', function(e) {


    // определяем форму отправки коментария
    var parent = $(this).parent().children('.id_parrent').val();
    var addform='';
    if(parent!='0') addform=String($('.addcommform'+parent).serialize());
    else addform=String($('#addcommform').serialize());

    $.ajax({
            method:"POST",
            type: "POST",
            url: server_url+"comm/add",
            data:addform,
            success: function (data) {

                if(data) //успешно
                {
                        $("#sgcomm .newcomment").val('');
                        $("#sgcomm .sgcauth").html("<span class='sgcempty sgcauthok'>Комментарий успешно добавлен!</span>");
                        $("#sgcomm .sgcauth").fadeIn(0);
                        // перерисовать список

                         if(parent!='0')
                         {
                            $("#sgcomm .sgcomnew").html(data);
                            $("#sgcomm .sgcsubans"+parent).html('');//прячем форму
                         }
                         else  $("#sgcomm .sgcomments").append(data); // корневой пост добаляем в конец




                    timerId=setTimeout(function() {
                        $("#sgcomm .sgcauth").fadeOut(1000); //прячем уведомление
                        $("#sgcomm .sgcempty").fadeOut(1000); //прячем уведомление
                        
                        $("#sgcomm .sgcomnew").removeClass('sgcomnew'); //удаляем класс нового поста
                    clearTimeout(timerId);  }, 3000);
                }
                else alert('Ошибка. Комментарий пустой');



            }
        });
        return false;
});

//назначаем родителя (ответ на пост)
$('body').on("click",'.sgcanswer', function(e) {
    var parent=$(this).attr('data-par');
    //if(parent) $("#sgcomm .id_parrent").val(parent);

    //вставим блок для размещения нового комента после текущего/коментируемого
    $("#sgcomm div.sgcomnew").remove();
    $(this).parent().append("<div class='sgcom sgcomnew'>Добавимся тут ;) ...</div>");

    //клонируем форму
    $(".sgcsubans").html(''); //очистим все формы ответов на посты
    if(parent)
    {
        $(".sgcaddcom").clone().appendTo(" .sgcsubans"+parent ); //вставить форму коммента под коментируемым
        $("#sgcomm  .sgcsubans"+parent+ " .id_parrent").val(parent);//добавим парент

        //добавить класс актуальной формы
        $("#sgcomm  .sgcsubans"+parent+ " #addcommform").removeClass('addcommform');
        $("#sgcomm  .sgcsubans"+parent+ " #addcommform").addClass('addcommform'+parent);
    }

    return false;
});


//подгрузка постов
$('body').on("click",'.sgcshowmore', function(e) {
$.ajax({
      method:"POST",
      type: "POST",
      url: server_url+"comm/list",
        data:{url:siteurl},
     	success: function (data) {
     	  var arrresult= $.parseJSON(data);
          $("#sgcomm .sgcomments").append(arrresult['commlist']); // вконец спика добавить
          if(!arrresult['getmore']) // скрыть кнопку показать еще
                $("#sgcomm .sgcshowmore").hide();
      }
  });

    return false;
});


//готовим форму для редактирования комментария
$('body').on("click",'.editmycom', function(e) {
    //закрываем предыдущие попытки редактировать
    $('#sgcomm .savemycom').hide();
    $('#sgcomm .sgconecomm').attr('contenteditable','false').removeClass('editable');

    var edited=$(this).parent().parent().children('.sgconecomm');
    var comm=edited.html();
    edited.attr('contenteditable','true');
    edited.addClass('editable').focus();
    //edited.focus();
    $(this).parent().children('.savemycom').show();
    return false;
});

//сохранить комментарий
$('body').on("click",'.savemycom', function(e) {

    var id_comm=$(this).attr('rel');
    var comm=$(this).parent().parent().children('.sgconecomm').html();

    var spin=$(this).parent().children('.spin');
    spin.show();
$.ajax({
      method:"POST",
      type: "POST",
      url: server_url+"comm/save",
        data:{id_comm:id_comm,comm:comm },
     	success: function (data) {
            ///сохранено
            $('#sgcomm .sgconecomm').attr('contenteditable','false').removeClass('editable');
            $('#sgcomm .savemycom').hide();
            $(this).hide(); //прячем кнопку с дискеткой
            spin.fadeOut(200); //прячем спинер
      }
    });

    return false;
});

//удалить пост
$('body').on("click",'a.delmycom', function(e) {
	var block=$(this).parent().parent(); //потом скроем пост..
    var id_comm=$(this).attr('rel');
    $.ajax({
        method:"POST",
        type: "POST",
        url: server_url+"comm/del",
        data:{id_comm:id_comm},

         success: function (data) {
     	  if(data)  block.fadeOut(300);
        }
    });
    return false;
});





/*----------rate-----------*/
//выбор оценки
$('body').on("mouseenter" ,'.active > .raiting', function(e) {
          $(this).children('.raiting_votes').toggle();
          $(this).children('.raiting_hover').toggle();
          //alert('on');
        }).on("mouseleave" ,'.active > .raiting', function(e){
          $(this).children(' .raiting_votes').toggle();
          $(this).children(' .raiting_hover').toggle();
          //alert('off');
        }).on("mousemove" ,'.active > .raiting', function(e){
            var margin_doc = $(".raiting").offset();
            var widht_votes = e.pageX - margin_doc.left;
            user_votes = Math.ceil(widht_votes)  ;

            $(this).children(' .raiting_hover').css('width' , user_votes+'px');
          
            $(this).parent().children(' .raiting_info').html("<br />"+margin_doc.left+" / "+widht_votes+" / "+user_votes);
          
    });


//оставить оценку
$('body').on("click" ,'.raiting', function(e) {
    var id_comm=$(this).attr('data-id');

            var texxxt=$(this).parent().parent().children('.raiting_star').removeClass('active');
            user_votes= parseInt(user_votes/17)+1;
            
            $.ajax({
                  method:"POST",
                  type: "POST",
                  url: server_url+"rate/add",
                    data:{id_comm:id_comm, vote:user_votes },
                 	success: function (data) {
                        var arrresult= $.parseJSON(data);
                        alert(arrresult['info']);
                        if(arrresult['cuca']) //пишем кУку
                        {
                            var date = new Date(new Date().getTime() + 3600*24*14 * 1000); //на 14 дней
                            document.cookie = "sgcomm_rate_"+id_comm+"=true; path=/; expires=" + date.toUTCString();
                        }
                  }
              });
              
    return false;
});
    

});