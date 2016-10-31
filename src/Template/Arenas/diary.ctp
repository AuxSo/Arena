<h1> Diary</h1>


<?php //Si l'utilisateur n'a pas de fighter
if ($fighterExists) {
    foreach ($Event as $key => $event) {
        ?>
        <ul>
            <li>
                <?php
                echo $date = $event['date']->i18nFormat();
                echo " - ";
                echo($event['name']);
                echo " - ";
                echo " Coordonnées : ";
                echo $event['coordinate_x'];
                echo " ";
                echo($event['coordinate_y']);
                ?> </li>
        </ul>
        <?php
    }
} else { ?>
    <p> You don't have any fighter... <?= $this->Html->link('Click here to create one right away !', ['controller' => 'Arenas', 'action' => 'fighter']) ?> </p>
<?php } ?>
