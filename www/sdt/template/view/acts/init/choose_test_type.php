<?php /*foreach ($Types as $type): ?>
    <a href="index.php?action=act_add&type=<?=$type->id?>" class="btn btn-primary btn-large"><?= $type->caption ?></a>
<? endforeach; */?>

<?php


$right_side_is_blocked=0; // заблокирована ли права€ ссылка (0-нет/1-да)




$hrefs=array();
foreach ($Types as $type):
   $hrefs[]='index.php?action=act_add&type='.$type->id;
endforeach; ?>
<?
$href1=$hrefs[0];
$href2=$hrefs[1];
$addr="content/img/";
ob_start();
?>


<!--[if lt IE 9]>
<style>
        .href_table
        {
            background:url('<?=$addr?>notebook.png');
            background-position-x: 50%;
        }





        #td21
        {
            background:url('<?=$addr?>blank.png')
        }
        #td21:hover
        {
            background:url('<?=$addr?>left2.png')
        }


        <? if ($right_side_is_blocked==1){ ?>
		#td22
           {
               background:url('<?=$addr?>right2_d_b.png')
           }
        #td22:hover
        {
            background:url('<?=$addr?>right2_d.png');
            background-position-x: 97%;
        }

        <? } else { ?>


        #td22
                    {
                        background:url('<?=$addr?>blank.png')
                    }
        #td22:hover
        {
            background:url('<?=$addr?>right2.png');
            background-position-x: 97%;
        }
        <? } ?>





        </style>
<![endif]-->

    <style>


        .href_table #td21, .href_table #td22{
            height: 436px;
        }

        .href_table td
        {
            cursor:default;
        }

        .href_table td:hover
        {
            cursor:pointer;
        }

        hr
        {
            border:0;
            border-top:1pt dashed red;

        }




        #td21
        {
            background:url('<?=$addr?>blank.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0)
        }
        #td21:hover
        {
            background:url('<?=$addr?>left2.png') no-repeat scroll 2% 0 rgba(0, 0, 0, 0)
        }


		<? if ($right_side_is_blocked==1){ ?>
		#td22
        {
            background:url('<?=$addr?>right2_d_b.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
        }
        #td22:hover
        {
            background:url('<?=$addr?>right2_d.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
        }

		<? } else { ?>


        #td22
        {
            background:url('<?=$addr?>blank.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0)
        }
        #td22:hover
        {
            background:url('<?=$addr?>right2.png') no-repeat scroll 97% 0 rgba(0, 0, 0, 0)
        }
<? } ?>
    </style>



    <table align="center" width="636" height="460" style="background:url('<?=$addr?>notebook.png') no-repeat scroll 50% 0 rgba(0, 0, 0, 0)" class="href_table">
        <tr style="text-align:center">
            <td id="td21" onclick="window.location.href='<?=$href1?>'"> </td>
            <? if ($right_side_is_blocked==1){?>
			<td id="td22" onclick="alert('Ќедоступно')" style="cursor:not-allowed;" > </td>
			<? } else { ?>
			<td id="td22" onclick="window.location.href='<?=$href2?>'" > </td>
			<? } ?>
        </tr>

    </table>

