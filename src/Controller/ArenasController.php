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

        $this->loadModel('Players');

        $data_post = $this->request->is('post');
        if ($this->request->data('inscription')) {
            $data_inscription = $this->request->data;
            $inscrit = $this->Players->inscription($data_post, $data_inscription);
            if ($inscrit) {

                return $this->redirect(['action' => 'index']);
            }
        }

        if ($this->request->data('connexion')) {
            $data_connexion = $this->request->data;
            $this->Players->connexion($data_post, $data_connexion);
            // A MODIFIER
            $this->request->session()->write('myFighterId', 1);
            $this->request->session()->write('myPlayerId', '8mm12z2j-3rqe-zil1-vz6r-i81gz4o8qa9t');
        }



    }

    public function fighter()
    {
        $this->loadModel('Fighters');
        $fighterlist = $this->Fighters->find('all');


        $this->loadModel('Tools');
        $this->set('tools', $this->Tools->getTools());

        // The tools owned by the fighter whose id is given in param (here 1 as test)
        // The chosen fighter will be stored in a session variable
        $this->set('sightTool', $this->Tools->getSightTool($this->request->session()->read('myFighterId')));
        $this->set('strengthTool', $this->Tools->getStrengthTool($this->request->session()->read('myFighterId')));
        $this->set('healthTool', $this->Tools->getHealthTool($this->request->session()->read('myFighterId')));

        $this->set('bestFighter', $this->Fighters->getBestFighter());
        $this->set('myFighterById', $this->Fighters->getFighterById(2));
        $this->set('myFightersByPlayer', $this->Fighters->getFightersByPlayer('545f827c-576c-4dc5-ab6d-27c33186dc3e'));

//        $this->Fighters->moveFighter(2, 3, 5);
        $this->Fighters->FighterTakeObject(1, 1);
        $this->Fighters->attack(1, 2);
        //$this->Fighters->fighterDead(2);
        //$this->Fighters->fighterProgression(1,1);


    }

    public function sight()
    {

        $this->loadModel('Fighters');


        if ($this->request->is('post')) {
            if ($this->request->data('move')) {
                $this->Fighters->moveFighter($this->request->session()->read('myFighterId'), $this->request->data('xSelected'), $this->request->data('ySelected'));
            }
            if ($this->request->data('attack')) {
                $this->Fighters->attack($this->request->session()->read('myFighterId'),
                    $this->Fighters->getFighterByCoord($this->request->data('xSelected'),
                        $this->request->data('ySelected'))->id);
            }
        }


        //récupère les constantes de taille du terrain$this->Fighters->ARENA_HEIGHT
        $this->set('arenaWidth', 15);
        $this->set('arenaHeight', 10);

        //stock tous les elements à afficher dans la variable tabArenaElements (DEBUG)
        $this->set('tabArenaElements', $this->Fighters->getArenaElements());
        //stock dans une matrice les elements à afficher dans la vue
        //$this->set('matrice', $this->Fighters->getMatrice());
        if ($this->request->session()->check('myFighterId')) {
            $myFighter = $this->Fighters->getFighterById($this->request->session()->read('myFighterId'));
            $this->set('outputMatrice', $this->Fighters->getOutputMatriceVisible($myFighter->coordinate_x, $myFighter->coordinate_y, $myFighter->skill_sight));
            $this->set('matrice', $this->Fighters->getMatriceVisible($myFighter->coordinate_x, $myFighter->coordinate_y, $myFighter->skill_sight));
        } else
            $this->set('outputMatrice', $this->Fighters->getOutputMatriceVisible(5, 5, 2));
    }

    public function diary()
    {
        $this->loadModel('Events');

        $this->set('Event', $this->Events->getRecentEvents());

    }
}