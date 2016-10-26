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
        $fighter=$this->find('all')->order('id desc');
        $tabFighters = $fighter->toArray();
        foreach($tabFighters as $key => $myFighter)
        {
            if($myFighter['id'] == $id)
            {
                $fighterById[]=$myFighter;
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
        $fighter=$this->find('all')->order('player_id desc');
        $tabFighters = $fighter->toArray();
        foreach($tabFighters as $key => $myFighter)
        {
            if($myFighter['player_id'] == $playerId)
            {
                $fightersByPlayer[]=$myFighter;
            }
        }
        return $fightersByPlayer;
    }

    /**
     *
     */
    public function getBestFighter()
    {
        $fighter=$this->find('all')->order('level desc');
        $tabFighters = $fighter->toArray();
        $lvlMax = $tabFighters[0]['level'];
        foreach($tabFighters as $key => $myFighter)
        {
            if($myFighter['level'] == $lvlMax)
            {
                $bestFighters[]=$myFighter;
            }
        }
        return $bestFighters;
    }
    public function get_index($id)
    {
        $fighter=$this->find('all')->order('id desc');
        $tabFighters = $fighter->toArray();
        $i=0;
        foreach ($tabFighters as $key => $myFighter)
        {
            if($myFighter['id'] == $id)
            {
                $i++;
            }
        }
        return $i;
    }
    //FAIRE FONCTION GET_INDEX pour récupérer l'index du fighter en fonction de son id dans le tableau
    public function moveFighter()
    {
        $index=$this->get_index(2);
        $this->Fighters->read(null, $index);
        $this->Fighters->set('coordinate_x', 2);
        $this->Fighters->set('coordinate_y', 2);
        $this->Fighters->save();



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