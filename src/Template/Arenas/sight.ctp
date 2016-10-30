<main id="sightMain">
    <section id="myInfos">
        <h3>Fighter : <?= $myFighter->name ?></h3>
        <article>
            <h4>Infos :</h4>
            <dl>
                <dt>Level :</dt>
                <dd><?= $myFighter->level ?></dd>
                <dt>Experience :</dt>
                <dd><?= $myFighter->xp ?> (you need <strong><?= 4 - ($myFighter->xp % 4) ?></strong> more to level up)
                </dd>
            </dl>
        </article>
        <article>
            <h4>Stats :</h4>
            <dl>
                <dt>Health :</dt>
                <dd><?= $myFighter->current_health ?>/<?= $myFighter->skill_health ?>
                    <?php if (isset($healthTool)) { ?>
                        (including <strong>+<?= $healthTool->bonus ?></strong> tool bonus)
                        <?php
                    } ?>
                </dd>
                <dt> Strength :</dt>
                <dd><?= $myFighter->skill_strength ?>
                    <?php if (isset($strengthTool)) { ?>
                        (including <strong>+<?= $strengthTool->bonus ?></strong> tool bonus)
                        <?php
                    } ?></dd>
                <dt>Sight :</dt>
                <dd><?= $myFighter->skill_sight ?>
                    <?php if (isset($sightTool)) { ?>
                        (including <strong>+<?= $sightTool->bonus ?></strong> tool bonus)
                        <?php
                    } ?></dd>
            </dl>
        </article>
    </section>
    <section id="midColumn">
        <table id="arena">
            <?php
            for ($i = 0; $i < $arenaHeight; $i++) {
                ?>
                <tr> <?php
                    for ($j = 0; $j < $arenaWidth; $j++) {
                        ?>
                        <td class="case<?php
                        if (in_array("Fighter", $outputMatrice[$i][$j]) || in_array("Tool", $outputMatrice[$i][$j]) || in_array("Adjacent", $outputMatrice[$i][$j])) {
                            ?> clickable<?php
                        } ?><?php
                        if (in_array("Adjacent", $outputMatrice[$i][$j])) {
                            ?> adjacent<?php
                        } ?><?php
                        if (in_array("Fighter", $outputMatrice[$i][$j])) {
                            ?> occupied<?php
                        } ?><?php
                        if ((in_array("Tool", $outputMatrice[$i][$j]))&&(in_array("Self", $outputMatrice[$i][$j]))) {
                            ?> takable<?php
                        } ?>"
                            value="<?= $i . '-' . $j ?>"> <?php

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
            ?>
        </table>

        <article id="actions">
            <form method="post">
                <input type="hidden" name="xSelected" id="xSelected">
                <input type="hidden" name="ySelected" id="ySelected">
                <input type="hidden" name="enemy" id="enemy">
                <input class="hidden" type="submit" id="attack" name="attack" value="attack">
                <input class="hidden" type="submit" id="move" name="move" value="move">
                <input class="hidden" type="submit" id="take" name="take" value="take">
            </form>
        </article>

    </section>
    <section id="otherInfo">
        <h3>Information</h3>
        <?php
        foreach ($matrice as $i => $row) {
            foreach ($row as $j => $cell) {
                if ($cell != 'toFarAway') {
                    if (!empty($cell)) {
                        foreach ($cell as $element) {
                            if ($element == $myFighter) {
                                ?>
                                <article class="infoBlock hidden" value="<?= $i . '-' . $j ?>">
                                    <p>This is yourself!</p>
                                </article>
                                <?php
                            } else if (isset($element->xp)) { ?>
                                <article class="infoBlock hidden" value="<?= $i . '-' . $j ?>">
                                    <h4> <?= $element->name ?> </h4>
                                    <dl>
                                        <dt>Level :</dt>
                                        <dd><?= $element->level ?></dd>
                                        <dt>Health :</dt>
                                        <dd><?= $element->current_health ?>/<?= $element->skill_health ?></dd>
                                    </dl>
                                </article>
                                <?php
                            } else { ?>
                                <article class="infoBlock hidden" value="<?= $i . '-' . $j ?>">
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
                    } else {
                        ?>
                        <article class="infoBlock hidden" value="<?= $i . '-' . $j ?>">
                            <p>Nothing to display.</p>
                        </article>
                        <?php
                    }
                } else {
                    ?>
                    <article class="infoBlock hidden" value="<?= $i . '-' . $j ?>">
                        <p>This is too far away.</p>
                    </article>
                    <?php
                }
            }
        }
        ?>
    </section>
</main>






