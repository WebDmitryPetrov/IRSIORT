<?



$reportName = !empty($caption) ? $caption : 'Без названия';
?>
<!--    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>-->

<script>
    function check_all(prop)
    {
        //var prop ="#regs_ch";
        //alert(this);
        if ($("#"+prop).prop("checked"))
        {
            $("."+prop).prop("checked","checked");
        }
        else
        {
            $("."+prop).removeProp("checked");
        }
    }
</script>





    <!--<script>
        $(function() {
            $( "#accordion" ).accordion({
                active: 55,
                heightStyle: "content",
                collapsible: true

            });
        });

    </script>



    <div id="accordion">
        <h3>Регионы</h3>
        <div>
            <?/*
            $regions=Regions::getAll();
            echo '<br><input type="checkbox" onclick=\'check_all("regs")\' id="regs"><i>Выбрать все</i>';
            foreach ($regions as $region)
            {
                echo '<br><input type="checkbox" name="reg[]" value="'.$region->id.'" class="regs">'.$region->caption;
            }
            */?>
        </div>
        <h3>Головные центры</h3>
        <div>
            <?/*
            $head_centers=HeadCenters::getAll();
            echo '<br><input type="checkbox" onclick=\'check_all("hcs")\' id="hcs"><i>Выбрать все</i>';
            foreach ($head_centers as $hc)
            {
                if ($hc->id != 1 && $hc->id != 2 && $hc->id != 7 && $hc->id != 8) continue;
                echo '<br><input type="checkbox" name="hc[]" value="'.$hc->id.'" class="hcs">'.$hc->short_name;
            }
            */?>
        </div>

    </div>-->















    <h1><?=  $reportName ?></h1>

    <form action="" method="POST">

        <table >
            <tr>
                <td style="vertical-align: top">
                    <label>Регионы :
                        <?
                        $regions=Regions::getAll();
                        echo '<br><input type="checkbox" onclick=\'check_all("regs")\' id="regs"><i>Выбрать все</i>';
                        foreach ($regions as $region)
                        {
                            echo '<br><input type="checkbox" name="reg[]" value="'.$region->id.'" class="regs">'.$region->caption;
                        }
                        ?>
                    </label>
                </td>
                <td style="vertical-align: top">
                    <label>Головные центры :
                        <?
                        $head_centers=HeadCenters::getAll();
                        echo '<br><input type="checkbox" onclick=\'check_all("hcs")\' id="hcs"><i>Выбрать все</i>';
                        foreach ($head_centers as $hc)
                        {
                            if ($hc->id != 1 && $hc->id != 2 && $hc->id != 7 && $hc->id != 8) continue;
                            echo '<br><input type="checkbox" name="hc[]" value="'.$hc->id.'" class="hcs">'.$hc->short_name;
                        }
                        ?>
                    </label>
                </td>
            </tr>
        </table>





        <!--<label>от :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?/*= $from */?>">
            </div>
        </label> <label>До :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?/*= $to */?>">
            </div>
        </label>-->
        <input type="submit" value="Отфильтровать">
    </form>

<?
if (1==1):

echo '<ul>';
foreach ($result as $array)
{
    echo '<li>'.$array[0]['caption'].' ('.count($array).')<ul>';

    foreach ($array as $item)
    {
        echo '<li>'.$item['name'].' ('.$item['hc_sname'].')</li>';
    }
    echo '</ul></li>';
}
echo '</ul>';


endif; ?>