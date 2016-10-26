<header> Your fighters </header>
<nav id="fightersNav">
    <ul>
        <?php
        $addedClass = 'selected';
        foreach ($myFightersByPlayer as $index => $fighter) {
            ?>
            <li class="tab <?= $addedClass ?>" name="<?= $index ?>">
                <?= $index + 1?>
            </li>
            <?php
            $addedClass = '';
        }
        ?>

    </ul>
</nav>
<?php
foreach ($myFightersByPlayer as $index => $fighter) {
    ?>
    <section class="fighterCard <?= $addedClass ?>" id="<?= $index ?>">
        <h3>Fighter : <?= $fighter->name ?></h3>
        <article>
            <h4>Infos :</h4>
            <dl>
                <dt>Level :</dt>
                <dd><?= $fighter->level ?></dd>
                <dt>Experience :</dt>
                <dd><?= $fighter->xp ?></dd>
            </dl>
        </article>
        <article>
            <h4>Stats :</h4>
            <dl>
                <dt>Health :</dt>
                <dd><?= $fighter->current_health ?>/<?= $fighter->skill_health ?></dd>
                <dt>Strength :</dt>
                <dd><?= $fighter->skill_strength ?></dd>
                <dt>Sight :</dt>
                <dd><?= $fighter->skill_sight ?></dd>
            </dl>
        </article>
    </section>
    <?php
    $addedClass = "hidden";
}
?>


<h1> Joueur actuel : <?php echo($myFighterById[0]['player_id']); ?> </h1>
<p> Niveau du combattant : <?php echo($myFighterById[0]['level']); ?> | XP
    : <?php echo($myFighterById[0]['xp']); ?> </p>
<ul>
    <li>Vue : <?php echo($myFighterById[0]['skill_sight']); ?> </li>
    <li>Force : <?php echo($myFighterById[0]['skill_strength']); ?> </li>
    <li> Santé actuelle : <?php echo($myFighterById[0]['current_health']); ?> | Santé Maxi
        : <?php echo($myFighterById[0]['skill_health']); ?>   </li>
</ul>


<p> Meilleur combattant : <?php pr($bestFighter); ?></p>
<h3> Liste des objets :
</h3>
<dl>
    <?php
    foreach ($tools as $tool) {
        foreach ($tool->toArray() as $key => $info) {
            ?>
            <dt><?php echo $key ?></dt>
            <dd><?php echo $info ?></dd>
            <?php
        }
    } ?>
</dl>
