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
}