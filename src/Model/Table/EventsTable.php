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
        $event = $this->find('all', ['order' => ['date' => 'DESC']]);
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

    public function getRecentEventsVisible($x, $y, $view)
    {
        $recentEvents = $this->getRecentEvents();
        $recentEventsVisible = null;
        if($recentEvents){
            foreach($recentEvents as $myRecentEvent){
                if( (abs($myRecentEvent['coordinate_x']-$x) + abs($myRecentEvent['coordinate_y']-$y)) <= $view){
                    $recentEventsVisible[] = $myRecentEvent;
                }
            }
        }
        return $recentEventsVisible;
    }

    public function create_event($name, $coordinate_x, $coordinate_y)
    {

        $event = $this->newEntity();
        $date = Time::now();
        $event->name = $name;
        $event->date=$date;
        $event->coordinate_x= $coordinate_x;
        $event->coordinate_y= $coordinate_y;

        $this->save($event);


    }

}