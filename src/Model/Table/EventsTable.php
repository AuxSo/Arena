<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\I18n\Time;
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
    public function getRecentEvents()
    {
        $event = $this->find('all');
        $tabEvents = $event->toArray();
        $recentDate = Time::yesterday();
        $recentEvents=null;
        foreach ($tabEvents as $key => $myRecentEvents) {
            if ($myRecentEvents['date'] >= $recentDate)
            {
                $recentEvents[] = $myRecentEvents;
            }
        }

        return $recentEvents;
    }
}