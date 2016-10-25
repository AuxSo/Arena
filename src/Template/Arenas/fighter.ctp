<header> Combattant actuel : <?php echo ($myFighterById[0]['name']) ;?> </header>
<h1> Joueur actuel : <?php echo ($myFighterById[0]['player_id']) ;?> </h1>
<p> Niveau du combattant : <?php echo ($myFighterById[0]['level']) ;?>      | XP : <?php echo ($myFighterById[0]['xp']) ;?> </p>
<ul>
<li>Vue :  <?php echo ($myFighterById[0]['skill_sight']) ;?> </li>
<li>Force :  <?php echo ($myFighterById[0]['skill_strength']) ;?> </li>
<li> Santé actuelle :  <?php echo ($myFighterById[0]['current_health']) ;?> | Santé Maxi :  <?php echo ($myFighterById[0]['skill_health']) ;?>   </li>
</ul>


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


