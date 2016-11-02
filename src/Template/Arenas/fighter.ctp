<main id="fightersMain">
    <h3 id="fightersTitle"> Your fighters</h3>

    <nav id="fightersNav">
        <ul>
            <?php
            if (isset($myFighter)) {

                $addedClass = '';
            } else {
                $addedClass = 'selected';
            }
            if (!empty($myFightersByPlayer)) {

                foreach ($myFightersByPlayer as $index => $fighter) {
                    if (isset($myFighter)) {
                        if ($fighter->id == $myFighter->id) {
                            ?>
                            <li class="tab <?= "selected chosen" ?>" id="tab<?= $index ?>">
                                <?= $fighter->name ?>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li class="tab" id="tab<?= $index ?>">
                                <?= $fighter->name ?>
                            </li>
                            <?php
                        }
                    } else {
                        ?>
                        <li class="tab <?= "$addedClass" ?>" id="tab<?= $index ?>">
                            <?= $fighter->name ?>
                        </li>
                        <?php
                        $addedClass = '';
                    }
                }
            }
            ?>
            <li class="tab <?= $addedClass ?>" id="tabnew">
                +
            </li>

        </ul>
    </nav>
    <?php

    $addedClass = '';
    if (!empty($myFightersByPlayer)) {
        foreach ($myFightersByPlayer as $index => $fighter) {
            ?>
            <section class="fighterCard <?= $addedClass ?>" id="<?= $index ?>">
                <h3><?= $fighter->name ?></h3>
                <form id="fighterForm" method="post">
                    <input type="hidden" name="fighterId" id="fighterID" value="<?= $fighter->id ?>">
                    <input class="chooseButton" type="submit" name="select" value="Use <?= $fighter->name ?> to fight">
                </form>
                <?php if (file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.png')) {
                    $extension = ".png";
                } else if (file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.jpg')) {
                    $extension = ".jpg";
                } else if (file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.jpeg')) {
                    $extension = ".jpeg";
                } else
                    $extension = ""; ?>
                <article class="infoWrapper">
                    <h4 class="hidden">fighter info</h4>
                    <?= $this->Html->image("avatars/$fighter->id$extension", ['alt' => 'avatar', 'class' => 'avatar']) ?>
                    <article>
                        <h4>Infos :</h4>
                        <dl>
                            <dt>Level :</dt>
                            <dd><?= $fighter->level ?></dd>
                            <dt>Experience :</dt>
                            <dd><?= $fighter->xp ?> (<strong>+<?= 4 - ($fighter->xp % 4) ?></strong> to
                                level
                                up)
                            </dd>
                        </dl>
                    </article>
                    <article>
                        <h4>Stats :</h4>
                        <dl>
                            <dt>Health :</dt>
                            <dd><?= $fighter->current_health ?>/<?= $fighter->skill_health ?>
                                <?php if (isset($healthTool)) { ?>
                                    (including <strong>+<?= $healthTool->bonus ?></strong> tool bonus)
                                    <?php
                                } ?>
                            </dd>
                            <dt> Strength :</dt>
                            <dd><?= $fighter->skill_strength ?>
                                <?php if (isset($strengthTool)) { ?>
                                    (including <strong>+<?= $strengthTool->bonus ?></strong> tool bonus)
                                    <?php
                                } ?></dd>
                            <dt>Sight :</dt>
                            <dd><?= $fighter->skill_sight ?>
                                <?php if (isset($sightTool)) { ?>
                                    (including <strong>+<?= $sightTool->bonus ?></strong> tool bonus)
                                    <?php
                                } ?></dd>
                        </dl>
                    </article>
                </article>
            </section>
            <?php
            $addedClass = "hidden";
        }
    }
    ?>
    <section class="fighterCard <?= $addedClass ?>" id="new">
        <h3>Create a new fighter</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" id="name" placeholder="Your fighter's name">
            <label for="avatar">Pick an avatar (.jpg or .png) :</label>
            <input type="file" name="avatar">
            <input class="chooseButton" type="submit" name="newFighter" value="Create a new fighter">
        </form>
    </section>
</main>
<?php $this->assign('title', 'My fighters'); ?>
