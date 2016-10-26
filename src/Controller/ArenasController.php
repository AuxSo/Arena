<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Model\Table\FightersTable;

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController  extends AppController
{
    public function index()
    {
        $this->set('myname', "Romain Baticle");





    }
    public function login()
    {

    }
    public function fighter()
    {
        $this->loadModel('Fighters');
        $fighterlist=$this->Fighters->find('all');


        $this->loadModel('Tools');
        $this->set('tools',$this->Tools->getTools());

        $this->set('bestFighter',$this->Fighters->getBestFighter());
        $this->set('myFighterById',$this->Fighters->getFighterById(2));
        $this->set('myFightersByPlayer',$this->Fighters->getFightersByPlayer('545f827c-576c-4dc5-ab6d-27c33186dc3e'));

        $this->Fighters->moveFighter(2, 3, 5);

    }
    public function sight()
    {
        $this->set('arenaWidth',15);
        $this->set('arenaHeight',10);


        $this->loadModel('Fighters');
        //$this->set('fighters',$this->Fighters->getFighters());


        $this->loadModel('Tools');
        $this->set('tools',$this->Tools->getTools());
    }
    public function diary()
    {
        $this->loadModel('Events');

        $this->set('Event',$this->Events->getRecentEvents());

    }
}