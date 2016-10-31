<?php
namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Created by PhpStorm.
 * User: Aux
 * Date: 19/10/2016
 * Time: 13:33
 */
class ToolsTable extends Table
{

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
}