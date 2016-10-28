<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\FightersTable;

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController
{
    public function index()
    {
        $this->set('myname', "Romain Baticle");
    }

    public function login()
    {
        $this->request->session()->write('myFighterId', 1);
        $this->request->session()->write('myPlayerId', '545f827c-576c-4dc5-ab6d-27c33186dc3e');
    }

    public function fighter()
    {
        $this->loadModel('Fighters');
        $fighterlist = $this->Fighters->find('all');


        $this->loadModel('Tools');
        $this->set('tools', $this->Tools->getTools());

        $this->set('bestFighter', $this->Fighters->getBestFighter());
        $this->set('myFighterById', $this->Fighters->getFighterById(2));
        $this->set('myFightersByPlayer', $this->Fighters->getFightersByPlayer('545f827c-576c-4dc5-ab6d-27c33186dc3e'));

        //$this->Fighters->moveFighter(2, 3, 5);
        //$this->Fighters->FighterTakeObject(1,1);
        //$this->Fighters->attack(1,2);
        //$this->Fighters->fighterDead(2);
        //$this->Fighters->fighterProgression(1,1);


    }

    public function sight()
    {
        $this->loadModel('Fighters');

        //récupère les constantes de taille du terrain$this->Fighters->ARENA_HEIGHT
        $this->set('arenaWidth', 15);
        $this->set('arenaHeight', 10);

        //stock tous les elements à afficher dans la variable tabArenaElements (DEBUG)
        $this->set('tabArenaElements', $this->Fighters->getArenaElements());
        //stock dans une matrice les elements à afficher dans la vue
        //$this->set('matrice', $this->Fighters->getMatrice());
        if($this->request->session()->check('myFighterId')){
            $myFighter = $this->Fighters->getFighterById($this->request->session()->read('myFighterId'));
            $this->set('matrice', $this->Fighters->getMatriceVisible($myFighter->coordinate_x,$myFighter->coordinate_y,$myFighter->skill_sight));
        }
        else
            $this->set('matrice', $this->Fighters->getMatriceVisible(5,5,2));
    }

    public function diary()
    {
        $this->loadModel('Events');

        $this->set('Event', $this->Events->getRecentEvents());

    }
}