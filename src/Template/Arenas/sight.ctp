<table>
<?php
    for($i=0; $i<$arenaHeight; $i++)
    {
        ?> <tr> <?php
        for($j=0; $j<$arenaWidth; $j++)
        {
            ?> <td> <?php

            if(in_array("Fighter", $matrice[$i][$j]))
                echo("F");
            if(in_array("Tool", $matrice[$i][$j]))
                echo("T");

            ?> </td> <?php
        }
        ?> </tr> <?php
    }

    //pr($tabArenaElements);

?>
</table>