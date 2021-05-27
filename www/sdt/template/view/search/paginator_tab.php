<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 07.05.15
 * Time: 17:30
 * To change this template use File | Settings | File Templates.
 */

$paginator='';
$limiter=20;
$page=1;
$href='?action=search_act';
foreach ($_GET as $key=>$val)
{
    if ($key=='offset' || $key== 'action') continue;
    $href.='&'.$key.'='.$val;

}

$offset = (!empty($_GET['offset']))?(int)$_GET['offset'] : 0;
if ($offset < 0 ) $offset=0;

//echo ($href);
$paginator.= '<div class="pagination" style="height:auto">
    <ul>';
if (empty($offset)) $paginator.= '<li class="disabled"><a href="#">«</a></li>';
else $paginator.= '<li><a href="'.$href.'&offset='.($offset-$limiter).'">«</a></li>';


while($rows_count > 0)
{
    if ($offset==(($limiter*$page)-$limiter))
        $paginator.= '<li class="active"><a href="#">'.$page++.'</a></li>';

    else
        $paginator.= '<li><a href="'.$href.'&offset='.(($limiter*$page)-$limiter).'">'.$page++.'</a></li>';
    $rows_count=$rows_count-$limiter;
}

if ($offset==(($limiter*$page)-($limiter*2)))
    $paginator.= '<li class="disabled"><a href="#">»</a></li>';

else  $paginator.= '<li><a href="'.$href.'&offset='.($offset+($limiter)).'">»</a></li>';
$paginator.= '</ul>
</div>';