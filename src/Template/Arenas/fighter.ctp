
<header> Your fighters</header>
<nav id="fightersNav">
    <ul>
        <?php
        $addedClass = 'selected';
        foreach ($myFightersByPlayer as $index => $fighter) {
            ?>
            <li class="tab <?= $addedClass ?>" id="tab<?= $index ?>">
                <?= $fighter->name ?>
            </li>
            <?php
            $addedClass = '';
        }
        ?>
        <li class="tab <?= $addedClass ?>" id="tabnew">
            +
        </li>

    </ul>
</nav>
<?php
foreach ($myFightersByPlayer as $index => $fighter) {
    ?>
    <section class="fighterCard <?= $addedClass ?>" id="<?= $index ?>">
        <h3><?= $fighter->name ?></h3>
        <?php if(file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.png')){
            $extension = ".png";
        } else if (file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.jpg')){
            $extension = ".jpg";
        } else if (file_exists(WWW_ROOT . 'img' . DS . 'avatars' . DS . $fighter->id . '.jpeg')){
            $extension = ".jpeg";
        } else
            $extension="";?>
        <?=$this->Html->image("avatars/$fighter->id$extension", ['alt' => 'avatar', 'class' => 'avatar'])?>
        <article>
            <h4>Infos :</h4>
            <dl>
                <dt>Level :</dt>
                <dd><?= $fighter->level ?></dd>
                <dt>Experience :</dt>
                <dd><?= $fighter->xp ?> (you need <strong><?= 4 - ($fighter->xp % 4) ?></strong> more to level up)</dd>
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
    </section>
    <?php
    $addedClass = "hidden";
}
?>
<section class=" <?= $addedClass ?>" id="new">
    <h3>Create a new fighter</h3>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name">
        <label for="avatar">Avatar (.jpg or .png)</label>
        <input type="file" name="avatar">
        <input  type="submit" name="new_fighter" value="create">
    </form>
</section>
<!--<h3> Liste des objets :
</h3>
<dl>
    <?php
/*    foreach ($tools as $tool) {
        foreach ($tool->toArray() as $key => $info) {
            */ ?>
            <dt><?php /*echo $key */ ?></dt>
            <dd><?php /*echo $info */ ?></dd>
            <?php
/*        }
    } */ ?>
</dl>-->
