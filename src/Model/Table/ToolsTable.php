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
     * Returns the tool owned by the fighter that increases his/her sight
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getSightTool($idFighter)
    {
        return $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "sight"'])->toArray()[0];
    }

    /**
     * Returns the tool owned by the fighter that increases his/her stregth
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getStrengthTool($idFighter)
    {
        return $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "strength"'])->toArray()[0];
    }

    /**
     * Returns the tool owned by the fighter that increases his/her Health
     * @param $idFighter
     * @return \Cake\ORM\Query
     */
    public function getHealthTool($idFighter)
    {
        return $this->find('all', ['conditions' => 'fighter_id =' . $idFighter . ' and type = "health"'])->toArray()[0];
    }
}