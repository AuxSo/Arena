journal

<h1> Evenements</h1>

<?php
foreach ($Event as $key => $event)
{?>
  <ul>
    <li>
        <?php
        echo $date=$event['date']->i18nFormat();
        echo " - ";
        echo($event['name']);
        echo " - ";
        echo " Coordonnées : ";
        echo $event['coordinate_x'] ;
        echo " ";
        echo($event['coordinate_y']);
    ?> </li>
    </ul>
<?php
}
?>
