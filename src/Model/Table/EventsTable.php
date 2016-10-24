<?php
namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Created by PhpStorm.
 * User: Aux
 * Date: 19/10/2016
 * Time: 13:33
 */
class EventsTable extends Table
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
    public function getEvent()
    {
        $event=$this->find('all');
        $tabEvents = $event->toArray();


        return $tabEvents;
    }
}