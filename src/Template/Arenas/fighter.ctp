perso

<p> Meilleur combattant : <?php pr($bestFighter); ?></p>
<h3> Liste des objets :
</h3>
<dl>
    <?php
    foreach ($tools as $key => $tool) {
        foreach ($tool->toArray() as $key => $info) {
            ?>
            <dt><?php echo $key ?></dt>
            <dd><?php echo $info ?></dd>
            <?php
        }
    } ?>
</dl>