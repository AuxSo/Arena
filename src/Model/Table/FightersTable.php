<?php
namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Created by PhpStorm.
 * User: Aux
 * Date: 19/10/2016
 * Time: 13:33
 */
class FightersTable extends Table
{
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
}