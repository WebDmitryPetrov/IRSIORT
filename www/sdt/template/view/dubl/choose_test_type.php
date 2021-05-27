<?php /*foreach ($Types as $type): ?>
    <a href="index.php?action=act_add&type=<?=$type->id?>" class="btn btn-primary btn-large"><?= $type->caption ?></a>
<? endforeach; */ ?>

<?php


$right_side_is_blocked = 0; // заблокирована ли правая ссылка (0-нет/1-да)


$hrefs = array();
foreach ($Types as $type):
    $hrefs[] = 'dubl.php?action=dubl&type=' . $type->id;
endforeach; ?>
<?
$href1 = $hrefs[0];
$href2 = $hrefs[1];
$addr = "content/img/";
ob_start();
?>


<!--[if lt IE 9]>
<style>
    .href_table {
        background: url('<?=$addr?>notebook.png');
        background-position-x: 50%;
    }

    #td21 {
        background: url('<?=$addr?>blank.png')
    }

    #td21:hover {
        background: url('<?=$addr?>left2.png')
    }

    <
    ?
    if

    (
    $
    right_side_is_blocked

    =
    =
    1
    )
    {
    ?
    >
    #td22 {
        background: url('<?=$addr?>right2_d_b.png')
    }

    #td22:hover {
        background: url('<?=$addr?>right2_d.png');
        background-position-x: 97%;
    }

    <
    ?
    }
    else {

    ?
    >

    #td22 {
        background: url('<?=$addr?>blank.png')
    }

    #td22:hover {
        background: url('<?=$addr?>right2.png');
        background-position-x: 97%;
    }

    <
    ?
    }
    ?
    >


</style>
<![endif]-->
<link href="http://fonts.googleapis.com/css?family=Reenie+Beanie:regular" rel="stylesheet" type="text/css">
<style>


    .href_table #td21, .href_table #td22 {
        height: 436px;
    }

    .href_table td {
        cursor: default;
    }

    .href_table td:hover {
        cursor: pointer;
    }

    hr {
        border: 0;
        border-top: 1pt dashed red;

    }

    #td21 {
        background: url('<?=$addr?>blank.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0)
    }

    #td21:hover {
        background: url('<?=$addr?>left2.png') no-repeat scroll 2% 0 rgba(0, 0, 0, 0)
    }

    <? if ($right_side_is_blocked==1){ ?>
    #td22 {
        background: url('<?=$addr?>right2_d_b.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
    }

    #td22:hover {
        background: url('<?=$addr?>right2_d.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
    }

    <? } else { ?>

    #td22 {
        background: url('<?=$addr?>blank.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0)
    }

    #td22:hover {
        background: url('<?=$addr?>right2.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
    }

    <? } ?>

    #note a {
        text-decoration: none;
        color: white;
        background: #c73a3a;
        display: block;
        height: 60px;
        width: 120px;
        padding: 1em;
        -moz-box-shadow: 5px 5px 7px rgba(33, 33, 33, 1);
        -webkit-box-shadow: 5px 5px 7px rgba(33, 33, 33, .7);
        box-shadow: 5px 5px 7px rgba(33, 33, 33, .7);
        -moz-transition: -moz-transform .15s linear;
        -o-transition: -o-transform .15s linear;
        -webkit-transition: -webkit-transform .15s linear;
    }

    #note a {
        -webkit-transform: rotate(-6deg);
        -o-transform: rotate(-6deg);
        -moz-transform: rotate(-6deg);

        font-family: "Reenie Beanie";
        font-size: 18px;
        text-align: center;
        font-style: italic;
        font-weight: bold;
    }

    #note a:hover, #note a:focus {
        box-shadow: 10px 10px 7px rgba(0, 0, 0, .7);
        -moz-box-shadow: 10px 10px 7px rgba(0, 0, 0, .7);
        -webkit-box-shadow: 10px 10px 7px rgba(0, 0, 0, .7);
        -webkit-transform: scale(1.25);
        -moz-transform: scale(1.25);
        -o-transform: scale(1.25);
        position: relative;
        z-index: 5;
    }

    #zakladka a {
        position: relative;
        to p: -217px;
        top: -130px;
    !important;
        left: 340px;

    }

    #zakladka a:hover {

        left: 320px !important;
        to p: -217px;
    !important;
        top: -130px;
    !important;
        le ft: 200px !important;
        to p: -310px;
    !important;
    }

    #zak_img:hover {
        -we bkit-transform: scale(1.1) !important;
        -moz-t ransform: scale(1.1) !important;
        -o-tran sform: scale(1.1) !important;
        -webkit-transform: rotate(60deg) !important;
        -o-transform: rotate(60deg) !important;
        -moz-transform: rotate(60deg) !important;

    }

    #zakladka2 a {
        position: relative;

        top: -562px;
        left: 480px;

    }

    #zak_img2 {

        -webkit-transform: rotate(-120deg) !important;
        -o-transform: rotate(-120deg) !important;
        -moz-transform: rotate(-120deg) !important;
    }

    #zakladka2 a:hover {

        left: 485px !important;
        top: -522px;
    !important;

    }

    #zak_img2:hover {

        -webkit-transform: rotate(-90deg) !important;
        -o-transform: rotate(-90deg) !important;
        -moz-transform: rotate(-90deg) !important;

    }

    #note, #zakladka, #zakladka2 {
        height: 1px;
    }

	#zakladka2, #empty {
        d isplay:none;
    }

</style>


<div style="height:70px" id="empty"></div>
<h1>Заказать дубликат</h1>
<table align="center" width="636" height="460"
       style="background:url('<?= $addr ?>notebook.png') no-repeat scroll 50% 0 rgba(0, 0, 0, 0)" class="href_table">
    <tr style="text-align:center">
        <td id="td21" onclick="window.location.href='<?= $href1 ?>'"></td>
        <? if ($right_side_is_blocked == 1) { ?>
            <td id="td22" onclick="alert('Недоступно')" style="cursor:not-allowed;"></td>
        <? } else { ?>
            <td id="td22" onclick="window.location.href='<?= $href2 ?>'"></td>
        <? } ?>
    </tr>


    <!--<tr>
        <td colspan="2">
            <div id="note" style="position: relative; width: 10px; top: -117px; left: 450px;"><a
                    href="index.php?action=act_add&type=2&sp=1">Экзамен для ДНР и ЛНР</a></div>
            <div id="zakladka"><a href="index.php?action=act_add&type=2&sp=2"><img id="zak_img"
                                                                                   src="<?/*= $addr */?>zakladka.png"></a>
            </div>

            <div id="zakladka2"><a href="index.php?action=act_add&type=2&g=2"><img id="zak_img2"
                                                                                    src="<?/*= $addr */?>zakladka2.png"></a>
            </div>

        </td>
    </tr>-->

</table>

