<table>
    <?php
    for ($i = 0; $i < $arenaHeight; $i++) {
        ?>
        <tr> <?php
            for ($j = 0; $j < $arenaWidth; $j++) {
                ?>
                <td> <?php

                    if (in_array("Fighter", $outputMatrice[$i][$j]))
                        echo("F");
                    if (in_array("Tool", $outputMatrice[$i][$j]))
                        echo("T");
                    if (in_array("Hidden", $outputMatrice[$i][$j]))
                        echo("X");

                    ?> </td> <?php
            }
            ?> </tr> <?php
    }

    //pr($tabArenaElements);

    ?>
</table>
<section id="infos">
    <?php
    foreach ($matrice as $i => $row) {
        foreach ($row as $j => $cell) {
            if (isset($cell) && !empty($cell)) {
                foreach ($cell as $element) {
                    if (isset($element->xp)) { ?>

                        <article>
                            <h4> <?= $element->name ?> </h4>
                            <dl>
                                <dt>Level :</dt>
                                <dd><?= $element->level ?></dd>
                                <dt>Health :</dt>
                                <dd><?= $element->current_health ?>/<?= $element->skill_health ?></dd>
                            </dl>
                        </article>

                    <?php } else { ?>
                        <article>
                            <h4> Tool </h4>
                            <dl>
                                <dt>Type :</dt>
                                <dd><?= $element->type ?></dd>
                                <dt>Bonus :</dt>
                                <dd><?= $element->bonus ?></dd>
                            </dl>
                        </article>
                    <?php
                    }
                }
            }
        }
    }
    ?>
</section>
