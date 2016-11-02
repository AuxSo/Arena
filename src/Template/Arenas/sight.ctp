
<main id="sightMain">
    <h1 class="hidden">Sight</h1>
    <?php //Si l'utilisateur n'a pas de fighter
    if (($fighterExists) && ($fighterAlive)) { ?>
        <section id="myInfos">
            <h3><?= $myFighter->name ?></h3>
            <article>
                <h4>Infos</h4>
                <dl>
                    <dt>Level :</dt>
                    <dd><?= $myFighter->level ?></dd>
                    <dt>Experience :</dt>
                    <dd><?= $myFighter->xp ?> (+<strong><?= 4 - ($myFighter->xp % 4) ?></strong> to level
                        up)
                    </dd>
                </dl>
            </article>
            <article>
                <h4>Stats</h4>
                <dl>
                    <dt>Health :</dt>
                    <dd><?= $myFighter->current_health ?>/<?= $myFighter->skill_health ?>
                        <?php if (isset($healthTool)) { ?>
                            (including <strong>+<?= $healthTool->bonus ?></strong> tool bonus)
                            <?php
                        } ?>
                        <?php if($isReadyToLvlUp){ ?>
                        <form method="post">
                            <input  type="submit" id="health" name="health" value="+3 level up!">
                        </form>
                        <?php } ?>
                    </dd>
                    <dt> Strength :</dt>
                    <dd><?= $myFighter->skill_strength ?>
                        <?php if (isset($strengthTool)) { ?>
                            (including <strong>+<?= $strengthTool->bonus ?></strong> tool bonus)
                            <?php
                        } ?>
                        <?php if($isReadyToLvlUp){ ?>
                        <form method="post">
                            <input  type="submit" id="strength" name="strength" value="+1 level up!">
                        </form>
                        <?php } ?>
                    </dd>
                    <dt>Sight :</dt>
                    <dd><?= $myFighter->skill_sight ?>
                        <?php if (isset($sightTool)) { ?>
                            (<strong><?= $sightTool->bonus ?></strong> tool bonus)
                            <?php
                        } ?>
                        <?php if($isReadyToLvlUp){ ?>
                        <form method="post">
                            <input  type="submit" id="sight" name="sight" value="+1 level up!">
                        </form>
                        <?php } ?>
                    </dd>
                </dl>
            </article>
        </section>
        <section id="midColumn">
            <table id="arena">
                <tr>
                    <td class="case noBorder"></td>
                    <?php
                    for ($i = 0; $i < $arenaWidth; $i++) {
                        ?>
                        <td class="case noBorder"><?= $i ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                for ($j = 0; $j < $arenaHeight; $j++) {
                    ?>
                    <tr>
                        <td class="case noBorder"><?= $j ?></td> <?php
                        for ($i = 0; $i < $arenaWidth; $i++) {
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
                            if ((in_array("Tool", $outputMatrice[$i][$j])) && (in_array("Self", $outputMatrice[$i][$j]))) {
                                ?> takable<?php
                            } ?><?php
                            if ((in_array("Self", $outputMatrice[$i][$j]))) {
                                ?> center<?php
                            } ?>"
                                value="<?= $i . '-' . $j ?>"> <?php

                                if (in_array("Fighter", $outputMatrice[$i][$j]))
                                    echo("F");
                                if (in_array("Tool", $outputMatrice[$i][$j]))
                                    echo("T");

                                ?> </td> <?php
                        }

                        ?>
                        <td class="case noBorder"><?= $j ?></td>
                    </tr> <?php
                }
                ?>
                <tr>
                    <td class="case noBorder"></td>
                    <?php
                    for ($i = 0; $i < $arenaWidth; $i++) {
                        ?>
                        <td class="case noBorder"><?= $i ?></td>
                        <?php
                    }
                    ?>
                </tr>
            </table>

            <article id="actions">
                <form method="post">
                    <input type="hidden" name="xSelected" id="xSelected">
                    <input type="hidden" name="ySelected" id="ySelected">
                    <input type="hidden" name="enemy" id="enemy">
                    <input class="hidden actionButton" type="submit" id="attack" name="attack" value="Attack">
                    <input class="hidden actionButton" type="submit" id="move" name="move" value="Move">
                    <input class="hidden actionButton" type="submit" id="take" name="take" value="Take">
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
    <?php } else {
        ?>
        <p> You don't have any
            fighter... <?= $this->Html->link('Click here to create one right away !', ['controller' => 'Arenas', 'action' => 'fighter']) ?> </p>

    <?php } ?>
</main>

<?php $this->assign('title', 'Sight'); ?>





