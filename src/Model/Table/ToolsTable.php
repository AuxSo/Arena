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
        $tools=$this->find('all');
        $tabTools = $tools->toArray();


        return $tabTools;
    }
}