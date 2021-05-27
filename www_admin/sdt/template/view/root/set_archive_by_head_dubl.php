<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 26.01.15
 * Time: 18:54
 * To change this template use File | Settings | File Templates.
 */

$back_button='<a href="index.php?action=acts_to_archive_list_dubl&till='.$till.'"
   class="btn btn-info">Вернуться</a>';
?>


<?=$back_button;?>

<h2><?=$header;?></h2>
<ol>
    <?
    /*foreach ($list as $item):
    ?>
        <li><b><?=$item->id;?></b></li>

    <?
    endforeach;*/
    echo implode(', ',$list);
    ?>
</ol>

<br>
<?=$back_button;?>