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

    }
    public function sight()
    {

    }
    public function diary()
    {
        $this->loadModel('Events');
        $this->set('Event',$this->Events->getEvent());

    }
}