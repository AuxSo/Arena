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

        $this->loadModel('Fighters');
        $fighterlist=$this->Fighters->find('all');

        $this->set('bestFighter',$this->Fighters->getBestFighter());

    }
    public function login()
    {

    }
    public function fighter()
    {

    }
    public function sight()
    {

    }
    public function diary()
    {

    }
}