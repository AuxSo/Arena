<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use PhpParser\Node\Expr\Cast\Array_;

/**
 * Created by PhpStorm.
 * User: Aux
 * Date: 19/10/2016
 * Time: 13:33
 */
class FightersTable extends Table
{

    public $ARENA_WIDTH = 15;
    public $ARENA_HEIGHT = 10;

    /**
     * @return string
     */
    public function test()
    {
        return "ok";
    }

    public function createFighter($name, $avatar, $player)
    {
        $fighter = $this->newEntity();
        $fighter->name = $name;
        $fighter->player_id = $player;
        $this->save($fighter);

        $extension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));

        if (!empty($avatar['tmp_name']) && (in_array($extension, array('jpg', 'jpeg', 'png')))) {
            move_uploaded_file($avatar['tmp_name'], 'img/avatars/' . $fighter->id . '.' . $extension);
            $fighter->level = 1;
            $fighter->xp = 0;
            $fighter->skill_sight = 2;
            $fighter->skill_strength = 1;
            $fighter->skill_health = 3;
            $fighter->current_health = 3;

            while ($this->getElementsByCoord($fighter->coordinate_x = rand(0, $this->ARENA_WIDTH - 1), $fighter->coordinate_y = rand(0, $this->ARENA_HEIGHT - 1)) != null) ;

            $this->save($fighter);
            return $fighter->id;
        } else {
            $this->delete($fighter);
            return -1;
        }

    }

    /**
     * Fonction qui retourne le fighter qui a pour ID la variable récupérée en paramètre
     * @param $id
     * @return array
     *
     */
    public function getFighterById($id)
    {
        $fighter = $this->find('all')->order('id desc');
        $tabFighters = $fighter->toArray();
        $fighterById = null;
        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['id'] == $id) {
                $fighterById = $myFighter;
            }
        }
        return $fighterById;
    }

    /**
     * Fonction qui retourne les fighters du player qui a pour Id la variable récupérée en paramètre
     * @param $playerId
     * @return array
     */
    public function getFightersByPlayer($playerId)
    {
        $fighter = $this->find('all')->order('player_id desc');
        $tabFighters = $fighter->toArray();
        $fightersByPlayer = null;
        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['player_id'] == $playerId) {
                $fightersByPlayer[] = $myFighter;
            }
        }
        return $fightersByPlayer;
    }

    /**
     *
     */
    public function getBestFighter()
    {
        $fighter = $this->find('all')->order('level desc');
        $tabFighters = $fighter->toArray();
        $lvlMax = $tabFighters[0]['level'];
        $bestFighters = null;
        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['level'] == $lvlMax) {
                $bestFighters[] = $myFighter;
            }
        }
        return $bestFighters;
    }

    public function getBestFighterbyPlayer($playerId)
    {
        $fighter = $this->find('all')->order('level desc');
        $tabFighters = $fighter->toArray();
        $fightersByPlayer = null;
        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['player_id'] == $playerId) {
                $fightersByPlayer[] = $myFighter;
            }
        }
        return $fightersByPlayer;
    }


    //FAIRE FONCTION GET_INDEX pour récupérer l'index du fighter en fonction de son id dans le tableau
    public function moveFighter($id, $coord_x, $coord_y)
    {
        $events = TableRegistry::get('Events');

        $fighters = $this->get($id);
        $fighterName = $fighters->name;
        $fighter_x = $fighters->coordinate_x;
        $fighter_y = $fighters->coordinate_y;

        if ($this->getFighterByCoord($coord_x, $coord_y) == null) {
            $fighters->coordinate_x = $coord_x;
            $fighters->coordinate_y = $coord_y;

            $this->save($fighters);

            $eventName = "Move of $fighterName";

            $events->create_event($eventName, $fighter_x, $fighter_y);
        }


    }

    // TODO Retirer l'id du fighter quand un objet est "écrasé"
    public function takeTool($idFighter, $idTool)
    {
        $fighter = $this->get($idFighter);
        $tools = TableRegistry::get('Tools');
        $events = TableRegistry::get('Events');

        $fighterName = $fighter->name;
        $fighter_x = $fighter->coordinate_x;
        $fighter_y = $fighter->coordinate_y;

        $tool = $tools->get($idTool);

        $toolType = $tool->type;
        $toolBonus = $tool->bonus;

        switch ($toolType) {
            case 'health':
                if ($oldTool = $tools->getHealthTool($idFighter)) {
                    if ($oldTool->bonus <= $toolBonus) {

                        $oldTool->fighter_id = null;
                        $fighter->skill_health -= $oldTool->bonus;
                        $tools->save($oldTool);
                        $events->create_event("$fighterName releases a $toolType tool", $fighter_x, $fighter_y);

                        $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                        $fighter->skill_health += $toolBonus;
                        $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                        $diff = $toolBonus - $oldTool->bonus;
                        $events->create_event("$fighterName increases $toolType skill by $diff", $fighter_x, $fighter_y);
                    } else {
                        return false;
                    }
                } else {
                    $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                    $fighter->skill_health += $toolBonus;
                    $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                    $events->create_event("$fighterName increases $toolType skill by $toolBonus", $fighter_x, $fighter_y);
                }

                break;
            case 'strength':
                if ($oldTool = $tools->getStrengthTool($idFighter)) {
                    if ($oldTool->bonus <= $toolBonus) {

                        $oldTool->fighter_id = null;
                        $fighter->skill_strength -= $oldTool->bonus;
                        $tools->save($oldTool);
                        $events->create_event("$fighterName releases a $toolType tool", $fighter_x, $fighter_y);

                        $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                        $fighter->skill_strength += $toolBonus;
                        $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                        $diff = $toolBonus - $oldTool->bonus;
                        $events->create_event("$fighterName increases $toolType skill by $diff", $fighter_x, $fighter_y);
                    } else {
                        return false;
                    }
                } else {
                    $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                    $fighter->skill_strength += $toolBonus;
                    $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                    $events->create_event("$fighterName increases $toolType skill by $toolBonus", $fighter_x, $fighter_y);
                }
                break;
            case 'sight':
                if ($oldTool = $tools->getSightTool($idFighter)) {
                    if ($oldTool->bonus <= $toolBonus) {

                        $oldTool->fighter_id = null;
                        $fighter->skill_sight -= $oldTool->bonus;
                        $tools->save($oldTool);
                        $events->create_event("$fighterName releases a $toolType tool", $fighter_x, $fighter_y);

                        $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                        $fighter->skill_sight += $toolBonus;
                        $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                        $events->create_event("$fighterName increases $toolType skill by $toolBonus", $fighter_x, $fighter_y);
                    } else {
                        return false;
                    }
                } else {
                    $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
                    $fighter->skill_sight += $toolBonus;
                    $events->create_event("$fighterName takes a $toolType tool", $fighter_x, $fighter_y);
                    $events->create_event("$fighterName increases $toolType skill by $toolBonus", $fighter_x, $fighter_y);
                }
                break;
        }

        $tools->save($tool);
        $this->save($fighter);
        return true;
    }


    public function attack($myFighterId, $fighterAttackedId)
    {
        $myfighter = $this->get($myFighterId);
        $fighterattacked = $this->get($fighterAttackedId);
        $events = TableRegistry::get('Events');

        $myfighterLevel = $myfighter->level;
        $myfighterStrength = $myfighter->skill_strength;
        $myfighterName = $myfighter->name;
        $myfighter_x = $myfighter->coordinate_x;
        $myfighter_y = $myfighter->coordinate_y;

        $fighterattackedLevel = $fighterattacked->level;
        $fighterattackedName = $fighterattacked->name;
        $fighterattackedHealth = $fighterattacked->current_health;
        $myfighterAttack_X = $fighterattacked->coordinate_x;
        $myfighterAttack_Y = $fighterattacked->coordinate_y;

        $randomNumber = rand(1, 20);

        //Si l'attaque réussit
        if ($randomNumber > (10 + $fighterattackedLevel - $myfighterLevel)) {

            $myfighter->xp += 1; //L'attaquant gagne 1 point d'xp
            $fighterattacked->current_health -= $myfighterStrength; //L'attaqué perd de la vie


            $eventName = "$myfighterName attacks $fighterattackedName";
            $events->create_event($eventName, $myfighter_x, $myfighter_y);
            $result = 1;
            //Si l'attaqué meurt
            if ($fighterattackedHealth - $myfighterStrength <= 0) {
                $myfighter->xp += $fighterattackedLevel;

                $eventName = "$myfighterName kills $fighterattackedName";
                $events->create_event($eventName, $myfighter_x, $myfighter_y);
                $this->fighterAttackDead($fighterAttackedId);
                $result = 2;
            }
        } else {
            $eventName = "$myfighterName fails to attack $fighterattackedName";
            $events->create_event($eventName, $myfighter_x, $myfighter_y);
            $result = 0;
        }

        $this->save($myfighter);
        $this->save($fighterattacked);


        return $result;

    }

    public function fighterAttackDead($idFighter)
    {
        $myfighter = $this->get($idFighter);
        $events = TableRegistry::get('Events');


        $myfighterName = $myfighter->name;
        $myfighter_x = $myfighter->coordinate_x;
        $myfighter_y = $myfighter->coordinate_y;

        $eventName = "Death of $myfighterName";
        $events->create_event($eventName, $myfighter_x, $myfighter_y);
        $this->reset($idFighter);
        $this->delete($myfighter);
        $this->save($myfighter);

    }

    public function fighterDead($idFighter)
    {

        $myfighter = $this->get($idFighter);
        $events = TableRegistry::get('Events');

        $myFighterHealth = $myfighter->current_health;
        $myfighterName = $myfighter->name;
        $myfighter_x = $myfighter->coordinate_x;
        $myfighter_y = $myfighter->coordinate_y;


        if ($myFighterHealth <= 0) {
            $eventName = "Death of $myfighterName";
            $events->create_event($eventName, $myfighter_x, $myfighter_y);
            $this->reset($idFighter);
            $this->delete($myfighter);
            $this->save($myfighter);


            return true;
        } else
            return false;


    }

    public function isFighterDead($myfighterId)
    {
        $fighter = $this->find()->extract('id');
        foreach ($fighter as $thisFighter) {
            if ($thisFighter == $myfighterId)
                return false;
        }
        return true;

    }


    public function reset($idFighter)
    {
        $Tools = TableRegistry::get('Tools');

        $fighterTools = $Tools->getFighterTools($idFighter);
        foreach ($fighterTools as $tool) {
            $tool->fighter_id = null;
            $Tools->save($tool);
        }

    }

    public function fighterProgression($idFighter, $choice)
    {
        $myfighter = $this->get($idFighter);
        $events = TableRegistry::get('Events');


        $myfighterName = $myfighter->name;
        $myfighter_x = $myfighter->coordinate_x;
        $myfighter_y = $myfighter->coordinate_y;


        switch ($choice) {
            case 1: //Vue
                $myfighter->skill_sight += 1;
                $events->create_event("$myfighterName increase skill sight", $myfighter_x, $myfighter_y);
                break;
            case 2://Force
                $myfighter->skill_strength += 1;
                $events->create_event("$myfighterName increase skill strength", $myfighter_x, $myfighter_y);
                break;
            case 3://Vie
                $myfighter->skill_health += 3;
                $myfighter->current_health += 3;
                $events->create_event("$myfighterName increase skill health", $myfighter_x, $myfighter_y);
                break;
            default;
        }

        $myfighter->level += 1;
        $events->create_event("$myfighterName level up", $myfighter_x, $myfighter_y);

        $this->save($myfighter);
    }

    public function isFighterReadyToLvlUp($fighterId)
    {
        $myfighter = $this->get($fighterId);
        $myFighterXP = $myfighter->xp;
        $myFighterLvl = $myfighter->level;

        if ($myFighterXP - (4 * $myFighterLvl) >= 4) {
            return true;
        }
    }

    /**
     * Retourne les éléments présents aux coordonnées passées en parametres
     * @param $x
     * @param $y
     * @return array|bool
     */
    public function getElementsByCoord($x, $y)
    {
        $tabElements = $this->getArenaElements();
        foreach ($tabElements as $element) {
            if (($element->coordinate_x == $x) && ($element->coordinate_y == $y))
                $result[] = $element;
        }
        if (isset($result))
            return $result;
        else
            return false;
    }

    /**
     * Retourne les types des éléments présents aux coordonnées passées en parametres
     * @param $x
     * @param $y
     * @return array
     */
    public function getElementTypeByCoord($x, $y)
    {
        $answer = [];
        $tabArenaFighters = $this->getArenaFighters();
        foreach ($tabArenaFighters as $arenaFighter) {
            if (($arenaFighter->coordinate_x == $x) && ($arenaFighter->coordinate_y == $y))
                $answer[] = 'Fighter';
        }
        $tabArenaTools = $this->getArenaTools();
        foreach ($tabArenaTools as $arenaTool) {
            if (($arenaTool->coordinate_x == $x) && ($arenaTool->coordinate_y == $y))
                $answer[] = 'Tool';
        }
        return $answer;
    }

    /**
     * Retourne un tableau contenant la liste de tous les Figjters du terrain
     * @return array
     */
    public function getArenaFighters()
    {
        $fighters = $this->find('all', ['conditions' => 'current_health > 0']);
        $tabFighters = $fighters->toArray();

        return $tabFighters;
    }

    /**
     * Retourne un tableau contenant la liste de tous les Tools du terrain
     * @return array
     */
    public function getArenaTools()
    {
        $tools = TableRegistry::get('Tools');
        $tools = $tools->find('all', ['conditions' => 'fighter_id is null']);
        $tabTools = $tools->toArray();

        return $tabTools;
    }

    /**
     * Retourne une matrice contenant les éléments Fighters et tools du terrain
     * @return array
     */
    public function getArenaElements()
    {
        $tabFighters = $this->getArenaFighters();
        $tabTools = $this->getArenaTools();
        $tabElements = array_merge($tabFighters, $tabTools);

        return $tabElements;
    }

    /**
     * Retourne une matrice contenant les elements du terrains
     * @return mixed
     */
    public function getMatrice()
    {
        for ($j = 0; $j < $this->ARENA_HEIGHT; $j++) {
            for ($i = 0; $i < $this->ARENA_WIDTH; $i++) {
                $matrice[$i][$j] = $this->getElementsByCoord($i, $j);
            }
        }
        return $matrice;
    }

    /**
     * Retourne une matrice contenant, pour chaque case, un tableau des types des éléments présents (String)
     * @return mixed
     */
    public function getOutputMatrice()
    {
        $matrice = $this->getMatrice();
        for ($j = 0; $j < $this->ARENA_HEIGHT; $j++) {
            for ($i = 0; $i < $this->ARENA_WIDTH; $i++) {
                if ($matrice[$i][$j] != false) {
                    foreach ($matrice[$i][$j] as $caseElement) {
                        if (isset($caseElement->xp)) {
                            $outputMatrice[$i][$j][] = 'Fighter';
                        } else
                            $outputMatrice[$i][$j][] = 'Tool';
                    }
                } else
                    $outputMatrice[$i][$j][] = 'Empty';
            }
        }
        return $outputMatrice;
    }

    /**
     * Retourne une matrice contenant, pour chaque case, un tableau des types des éléments présents visibles (String)
     * Les cases non visibles contiennent un String 'Hidden'
     * Les cases adjacentes contiennent également un String 'Adjacent'
     * @return mixed
     */
    public function getOutputMatriceVisible($x, $y, $view)
    {
        $fullMatrice = $this->getOutputMatrice();

        for ($j = 0; $j < $this->ARENA_HEIGHT; $j++) {
            for ($i = 0; $i < $this->ARENA_WIDTH; $i++) {
                if ((abs($i - $x) + abs($j - $y)) > $view) {
                    $fullMatrice[$i][$j] = ['Hidden'];
                }
                if ((abs($i - $x) + abs($j - $y)) == 1) {
                    $fullMatrice[$i][$j][] = 'Adjacent';
                }
                if (($i == $x) && ($j == $y)) {
                    $fullMatrice[$i][$j][] = 'Self';
                }
            }
        }
        return $fullMatrice;
    }

    /**
     * Retourne une matrice contenant, pour chaque case, un tableau contenant les éléments présents visibles(String)
     * Les cases non visibles sont des tableaux vides
     * @return mixed
     */
    public function getMatriceVisible($x, $y, $view)
    {
        $fullMatrice = $this->getMatrice();

        for ($j = 0; $j < $this->ARENA_HEIGHT; $j++) {
            for ($i = 0; $i < $this->ARENA_WIDTH; $i++) {
                if ((abs($i - $x) + abs($j - $y)) > $view)
                    $fullMatrice[$i][$j] = 'toFarAway';
            }
        }
        return $fullMatrice;
    }

    public function getFighterByCoord($x, $y)
    {
        $temp = $this->getElementsByCoord($x, $y);
        if ($temp == false)
            return null;
        foreach ($temp as $element) {
            if ($element->player_id) {
                $fighter = $element;
            }
        }

        return $fighter;
    }
}