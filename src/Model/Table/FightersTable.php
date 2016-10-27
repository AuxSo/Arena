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
                $fighterById[] = $myFighter;
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

    public function get_index($id)
    {
        $fighter = $this->find('all')->order('id desc');
        $tabFighters = $fighter->toArray();

        foreach ($tabFighters as $key => $myFighter) {
            if ($myFighter['id'] == $id) {
                $i++;
            }
        }
        return $i;
    }

    //FAIRE FONCTION GET_INDEX pour récupérer l'index du fighter en fonction de son id dans le tableau
    public function moveFighter($id, $dep_x, $dep_y)
    {

        $fighters = $this->get($id);
        $fighters->coordinate_x = $dep_x;
        $fighters->coordinate_y = $dep_y;
        $this->save($fighters);

    }

    public function FighterTakeObject($idFighter, $idTool)
    {
        $fighter = $this->get($idFighter);
        $tools = TableRegistry::get('Tools');
        $tool = $tools->get($idTool);

        $tool->fighter_id = $idFighter; // On assigne à l'objet l'id du fighter qui le prend.
        $ToolType = $tool->type;
        $ToolBonus = $tool->bonus;


        switch ($ToolType) {
            case 'Health':
                $fighter->skill_health += $ToolBonus;
                break;
            case 'Strength':
                $fighter->skill_strength += $ToolBonus;
                break;
            case 'Sight':
                $fighter->skill_sight += $ToolBonus;
                break;
        }

        $tools->save($tool);
        $this->save($fighter);
        $index=$this->get_index(2);
        $this->Fighters->read(null, $index);
        $this->Fighters->set('coordinate_x', 2);
        $this->Fighters->set('coordinate_y', 2);
        $this->Fighters->save();
    }



    public function attack($myFighterId, $fighterAttackedId)
    {
        $myfighter = $this->get($myFighterId);
        $fighterattacked = $this->get($fighterAttackedId);

        $myfighterLevel= $myfighter->level;
        $fighterattackedLevel= $fighterattacked->level;
        $myfighterStrength= $myfighter->skill_strength;
        $randomNumber = rand(1,20);
        $fighterattackedHealth= $fighterattacked->current_health;
        pr($myfighterLevel);
        pr($fighterattackedLevel);
        pr($randomNumber);
        $attackReussie=null;

        //Si l'attaque réussit
        if($randomNumber> (10 + $fighterattackedLevel - $myfighterLevel))
        {
            $attackReussie=true;
            $myfighter->xp+=1; //L'attaquant gagne 1 point d'xp
            $fighterattacked->current_health-=$myfighterStrength; //L'attaqué perd de la vie


            //Si l'attaqué meurt
            if($fighterattackedHealth-$myfighterStrength=0)
            {
                $myfighter->xp+=$fighterattackedLevel;
            }
        }

        else
        {
            $attackReussie=false;
        }

        $this->save($myfighter);
        $this->save($fighterattacked);
        pr ($attackReussie);
        return $attackReussie;

    }

    public function fighterDead($idFighter)
    {
        $myfighter = $this->get($idFighter);
        $myFighterHealth= $myfighter->current_health;

        if($myFighterHealth==0)
        {
            $this->delete($myfighter);
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

    public function getArenaElements()
    {
        $tabFighters = $this->getArenaFighters();
        $tabTools = $this->getArenaTools();
        $tabElements = array_merge($tabFighters, $tabTools);

        return $tabElements;
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
}