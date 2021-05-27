<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 26.01.15
 * Time: 18:54
 * To change this template use File | Settings | File Templates.
 */
?>
<h1 style="color:darkorange">־עקועû</h1>
<ol>
    <?
    foreach ($list as $item):
    ?>
        <li><a href="./index.php?action=<?=$item->action_name;?>"><?=$item->caption;?> </a></li>

    <?
    endforeach;
    ?>
</ol>