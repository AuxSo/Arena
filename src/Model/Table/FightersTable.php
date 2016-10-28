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
        $fighterById=null;
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
        $fightersByPlayer=null;
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
        $bestFighters=null;
        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['level'] == $lvlMax) {
                $bestFighters[] = $myFighter;
            }
        }
        return $bestFighters;
    }


    //FAIRE FONCTION GET_INDEX pour récupérer l'index du fighter en fonction de son id dans le tableau
    public function moveFighter($id, $coord_x, $coord_y)
    {
        $events = TableRegistry::get('Events');

        $fighters = $this->get($id);
        $fighterName= $fighters->name;
        $fighter_x=$fighters->coordinate_x;
        $fighter_y=$fighters->coordinate_y;
        $fighters->coordinate_x = $coord_x;
        $fighters->coordinate_y = $coord_y;

        $this->save($fighters);

        $eventName="Déplacement de $fighterName";

        $events->create_event($eventName,$fighter_x,$fighter_y);

    }

    public function FighterTakeObject($idFighter, $idTool)
    {
        $fighter = $this->get($idFighter);
        $tools = TableRegistry::get('Tools');
        $events = TableRegistry::get('Events');

        $fighterName= $fighter->name;
        $fighter_x=$fighter->coordinate_x;
        $fighter_y=$fighter->coordinate_y;

        $tool = $tools->get($idTool);

        $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
        $ToolType = $tool->type;
        $ToolBonus = $tool->bonus;

        $events->create_event("$fighterName prend l'objet $ToolType", $fighter_x, $fighter_y);


        switch ($ToolType) {
            case 'Health':
                $fighter->skill_health += $ToolBonus;
                $events->create_event("$fighterName augmente Skill_Health de $ToolBonus", $fighter_x, $fighter_y);
                break;
            case 'Strength':
                $fighter->skill_strength += $ToolBonus;
                $events->create_event("$fighterName augmente Skill_Strength de $ToolBonus", $fighter_x, $fighter_y);

                break;
            case 'Sight':
                $fighter->skill_sight += $ToolBonus;
                $events->create_event("$fighterName augmente Skill_Sight de $ToolBonus", $fighter_x, $fighter_y);
                break;
        }

        $tools->save($tool);
        $this->save($fighter);

    }



    public function attack($myFighterId, $fighterAttackedId)
    {
        $myfighter = $this->get($myFighterId);
        $fighterattacked = $this->get($fighterAttackedId);
        $events = TableRegistry::get('Events');

        $myfighterLevel= $myfighter->level;
        $myfighterStrength= $myfighter->skill_strength;
        $myfighterName=$myfighter->name;
        $myfighter_x=$myfighter->coordinate_x;
        $myfighter_y=$myfighter->coordinate_y;

        $fighterattackedLevel= $fighterattacked->level;
        $fighterattackedName=$fighterattacked->name;
        $fighterattacked_x=$fighterattacked->coordinate_x;
        $fighterattacked_y=$fighterattacked->coordinate_y;
        $fighterattackedHealth= $fighterattacked->current_health;

        $randomNumber = rand(1,20);

        //Si l'attaque réussit
        if($randomNumber> (10 + $fighterattackedLevel - $myfighterLevel))
        {

            $myfighter->xp+=1; //L'attaquant gagne 1 point d'xp
            $fighterattacked->current_health-=$myfighterStrength; //L'attaqué perd de la vie


            $eventName="Attaque réussie de $myfighterName sur $fighterattackedName";
            $events->create_event($eventName,$myfighter_x,$myfighter_y);

            //Si l'attaqué meurt
            if($fighterattackedHealth-$myfighterStrength=0)
            {
                $myfighter->xp+=$fighterattackedLevel;
                $this->fighterDead($fighterAttackedId);

            }
        }
        else{


            $eventName="Attaque raté de $myfighterName sur $fighterattackedName";
            $events->create_event($eventName,$myfighter_x,$myfighter_y);
        }

        $this->save($myfighter);
        $this->save($fighterattacked);


    }

    public function fighterDead($idFighter)
    {
        $myfighter = $this->get($idFighter);
        $events = TableRegistry::get('Events');

        $myFighterHealth= $myfighter->current_health;
        $myfighterName=$myfighter->name;
        $myfighter_x=$myfighter->coordinate_x;
        $myfighter_y=$myfighter->coordinate_y;

        if($myFighterHealth==0)
        {
            $this->delete($myfighter);
            $eventName="Mort de $myfighterName";
            $events->create_event($eventName,$myfighter_x,$myfighter_y);
        }
    }

    public function fighterProgression($idFighter, $choice)
    {
        $myfighter = $this->get($idFighter);
        $myFighterXP= $myfighter->xp;

        if($myFighterXP== $myFighterXP - ($myFighterXP % 4))
        {
            $myfighter->level+=1;
            switch ($choice) {
                case 1: //Vue
                    $myfighter->skill_sight+=1;
                    break;
                case 2://Force
                    $myfighter->skill_strength+=1;
                    break;
                case 3://Vie
                    $myfighter->skill_health+=3;
                    break;
                default;
            }

        }
        $this->save($myfighter);
    }

    public function getArenaFighters(){
        $fighters = $this->find('all',['conditions'=>'current_health > 0']);
        $tabFighters = $fighters->toArray();

        return $tabFighters;
    }

    public function getArenaTools(){
        $tools = TableRegistry::get('Tools');
        $tools = $tools->find('all',['conditions'=>'fighter_id is null']);
        $tabTools = $tools->toArray();

        return $tabTools;
    }

    public function getElementByCoord($x, $y)
    {
        $tabElements = $this->getArenaElements();
        foreach($tabElements as $element){
            if(($element->coordonate_x == $x) && ($element->coordonate_y == $y))
                return $element;
        }
        return false;
    }

    public function getElementTypeByCoord($x, $y)
    {
        $answer = [];
        $tabArenaFighters = $this->getArenaFighters();
        foreach($tabArenaFighters as $arenaFighter){
            if(($arenaFighter->coordinate_x == $x) && ($arenaFighter->coordinate_y == $y))
                $answer[] = 'Fighter';
        }
        $tabArenaTools = $this->getArenaTools();
        foreach($tabArenaTools as $arenaTool){
            if(($arenaTool->coordinate_x == $x) && ($arenaTool->coordinate_y == $y))
                $answer[] = 'Tool';
        }
        return $answer;
    }

    public function getMatrice(){
        for($i=0; $i<$this->ARENA_HEIGHT; $i++){
            for($j=0; $j<$this->ARENA_WIDTH; $j++){
                $matrice[$i][$j] = $this->getElementTypeByCoord($i, $j);
            }
        }
        return $matrice;
    }

    public function getArenaElements()
    {
        $tabFighters = $this->getArenaFighters();
        $tabTools = $this->getArenaTools();
        $tabElements = array_merge($tabFighters, $tabTools);

        return $tabElements;
    }

    public function getMatriceVisible($x, $y, $view){
        $fullMatrice = $this->getMatrice();

        for($i=0; $i<$this->ARENA_HEIGHT; $i++){
            for($j=0; $j<$this->ARENA_WIDTH; $j++){
                if( (abs($i-$x)+abs($j- $y)) > $view)
                    $fullMatrice[$i][$j] = ['Hidden'];
            }
        }
        return $fullMatrice;
    }
}