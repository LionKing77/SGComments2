<?php
//������� ������ �����
$id_post=$key['id'];
$rate=$key['rate'];
$rate_cnt=$key['rate_cnt'];
//echo "$id_post, $rate , $rate_cnt";

$title=$key['rate']. '('.$key['rate_cnt'].')';


//echo $userId."**<br />";


    $rate_class='';
    //�������� �� ������������
    if($userId && $userId!=$key['id_user']) $rate_class='active';

    //�������� ����, ��� � 14 ���� ����� ��������� ����
    if($_COOKIE['sgcomm_rate_'.$id_post]) $rate_class='';

    //������� ������� // 17 -������ ��������� � ��������
    $width_rate=$key['rate']*17;
    if( !$width_rate ) $width_rate='0';
    if($width_rate>85) $width_rate=85; //���������� ������ ����� �����


?>

<div class="raiting_star <?php echo $rate_class;?>"  title="<?php echo $title;?>" >
    <div class="raiting" data-id="<?php echo $id_post;?>" >
        <div class="raiting_blank" ></div>
        <div class="raiting_hover"></div>
        <div class="raiting_votes"  style='width:<?php echo $width_rate;?>px;'></div>
    </div>
</div>
