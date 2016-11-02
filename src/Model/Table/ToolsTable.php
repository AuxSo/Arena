<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Created by PhpStorm.
 * User: Aux
 * Date: 19/10/2016
 * Time: 13:33
 */
class ToolsTable extends Table
{
    public $ARENA_WIDTH = 15;
    public $ARENA_HEIGHT = 10;
    /**
     *
     */
    public function getTools()
    {
        $tools = $this->find('all');
        $tabTools = $tools->toArray();


        return $tabTools;
    }

    /**
     *
     */
    public function getToolByCoord($x, $y)
    {
        $tabTools = $this->getTools();
        foreach ($tabTools as $tool) {
            if (($x == $tool->coordinate_x) && ($y == $tool->coordinate_y)) {
                return $tool;
            }
        }
        return $tool;
    }


    /**
     * Returns the tool owned by the fighter that increases his/her sight
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getSightTool($idFighter)
    {
        $tab = $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "sight"'])->toArray();
        if ($tab != null)
            return $tab[0];
        else
            return null;
    }

    /**
     * Returns the tool owned by the fighter that increases his/her stregth
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getStrengthTool($idFighter)
    {
        $tab = $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "strength"'])->toArray();
        if ($tab != null)
            return $tab[0];
        else
            return null;
    }

    /**
     * Returns the tool owned by the fighter that increases his/her Health
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getHealthTool($idFighter)
    {
        $tab = $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "health"'])->toArray();
        if ($tab != null)
            return $tab[0];
        else
            return null;
    }

    public function getFighterTools($fighterId)
    {
        return $this->find('all', ['conditions' => 'fighter_id =' . $fighterId])->toArray();
    }

    public function createTool($type, $bonus)
    {
        $tool = $this->newEntity();
        $fighter = TableRegistry::get('Fighters');
        $tool->type = $type;
        $tool->bonus = $bonus;
        $tool->coordinate_x = rand(0, $this->ARENA_WIDTH - 1);
        $tool->coordinate_y = rand(0, $this->ARENA_HEIGHT - 1);
        $this->save($tool);


        //while ($fighter->getElementsByCoord(, ) != null) ;


       /* if (!empty($avatar['tmp_name']) && (in_array($extension, array('jpg', 'jpeg', 'png')))) {
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
        }*/

    }


}
