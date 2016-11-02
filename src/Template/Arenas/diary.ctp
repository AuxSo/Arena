<h3 id="diaryTitle"> Diary</h3>


<?php //Si l'utilisateur n'a pas de fighter
if ($fighterExists) {
    if ($Event) { //Si il y a des events
        $color = "light";
        ?>
        <ul><?php
        foreach ($Event as $key => $event) {
            ?>
            <li class="<?= $color ?>">
                <?php
                if ($color == "light")
                    $color = "dark";
                else
                    $color = "light";
                echo $date = $event['date']->i18nFormat();
                echo " - ";
                echo($event['name']);
                echo " - ";
                echo " Coordinates : ";
                echo $event['coordinate_x'];
                echo " ";
                echo($event['coordinate_y']);
                ?> </li>

            <?php
        }
        ?></ul><?php
    } else {
        echo "No recent event to display.";
    }
} else { ?>
    <p> You don't have any
        fighter... <?= $this->Html->link('Click here to create one right away !', ['controller' => 'Arenas', 'action' => 'fighter']) ?> </p>
<?php } ?>
<?php $this->assign('title', 'Diary'); ?>
