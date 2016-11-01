<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\FightersTable;
use App\Model\Table\PlayersTable;

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController
{
    public function index()
    {

    }

    public function login()
    {
        //Si on est connecté, on se déconnecte
        if ($this->request->session()->check('myPlayerId')) {
            $this->request->session()->destroy();
        }

        $this->loadModel('Players');
        $this->loadModel('Fighters');

        $data_post = $this->request->is('post');
        if ($this->request->data('inscription')) {
            $data_inscription = $this->request->data;

            if (!filter_var($data_inscription['email'], FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error('Veuillez entrer un email valide');
            } else {
                if (!$this->Players->isEmailUnique($data_inscription['email'])) {
                    $this->Flash->error('Cet email est déjà lié à un joueur');
                } else {
                    $inscrit = $this->Players->inscription($data_post, $data_inscription);
                    if ($inscrit) {
                        $this->request->session()->write('myPlayerId', $this->Players->getPlayerByEmail($this->request->data['email'])->id);
                        $this->request->session()->write('myFighterId', null);
                        $this->Flash->success('Votre compte a bien été créé');
                        return $this->redirect(['action' => 'index']);
                    }
                }
            }

            // A MODIFIER
            //$this->request->session()->write('myFighterId', 1);
            //$this->request->session()->write('myPlayerId', '8mm12z2j-3rqe-zil1-vz6r-i81gz4o8qa9t');
        } else if ($this->request->data('connexion')) {

            $data_connexion = $this->request;

            if ($data_connexion->data('email') && $data_connexion->data('password')) {

                if ($this->Players->checkConnexion($this->request->data['email'], $this->request->data['password'])) {
                    $this->Flash->success('Vous etes bien connectée');

                    //enregistrement des variables des variables de session
                    $this->request->session()->write('myPlayerId', $this->Players->getPlayerByEmail($this->request->data['email'])->id);
                    if ($this->Fighters->getFightersByPlayer($this->Players->getPlayerByEmail($this->request->data['email'])->id))
                        $this->request->session()->write('myFighterId', $this->Fighters->getBestFighterbyPlayer($this->Players->getPlayerByEmail($this->request->data['email'])->id)[0]->id);
                    else
                        $this->request->session()->write('myFighterId', null);

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error('Erreur demail ou password');
                    return $this->redirect(['action' => 'index']);
                }
            }
        } else if($this->request->data('lostMdp')){
            $this->Players->sendPasswordByMail($this->request->data['email']);
            $this->Flash->succes('Your password has been sent to '.$this->request->data['email']);
        }
    }

    public function fighter()
    {
        //Redirection vers la connexion si l'utilisateur n'est pas connecté (c'est qu'il a voulu accéder à la page via l'url)
        if (!$this->request->session()->check('myPlayerId')) {
            $this->redirect(['action' => 'login']);
        } //Si l'utilisateur est connecté...
        else {
            $this->loadModel('Fighters');

            $this->loadModel('Tools');
            $this->set('tools', $this->Tools->getTools());

            if ($this->request->is('post')) {
                if(!$this->Fighters->createFighter($this->request->data('name'),$this->request->data('avatar'),$this->request->session()->read('myPlayerId'))){
                    $this->Flash->error('Could not upload your avatar. Check the extension.');
                }
                else{
                    $this->Flash->success('Your fighter has been created.');
                }
            }

            //Si le joueur possède au moins un fighter...
            if ($this->request->session()->check('myFighterId')) {

                // The tools owned by the fighter whose id is given in param (here 1 as test)
                // The chosen fighter will be stored in a session variable
                $this->set('sightTool', $this->Tools->getSightTool($this->request->session()->read('myFighterId')));
                $this->set('strengthTool', $this->Tools->getStrengthTool($this->request->session()->read('myFighterId')));
                $this->set('healthTool', $this->Tools->getHealthTool($this->request->session()->read('myFighterId')));

                $this->set('myFightersByPlayer', $this->Fighters->getFightersByPlayer($this->request->session()->read('myPlayerId')));
            } else {
                $this->set('myFightersByPlayer', []);
            }


        }
    }

    public function sight()
    {
        //Redirection vers la connexion si l'utilisateur n'est pas connecté (c'est qu'il a voulu accéder à la page via l'url)
        if (!$this->request->session()->check('myPlayerId')) {
            $this->redirect(['action' => 'login']);
        } //Si l'utilisateur est connecté...
        else {
            $this->loadModel('Fighters');
            $this->loadModel('Tools');

            // Traitement des actions
            if ($this->request->is('post')) {
                // En cas de mouvement du combattant
                if ($this->request->data('move')) {
                    $this->Fighters->moveFighter($this->request->session()->read('myFighterId'), $this->request->data('xSelected'), $this->request->data('ySelected'));
                }
                // En cas d'attaque du combattant
                if ($this->request->data('attack')) {
                    switch ($this->Fighters->attack($this->request->session()->read('myFighterId'),
                        $this->Fighters->getFighterByCoord($this->request->data('xSelected'),
                            $this->request->data('ySelected'))->id)) {
                        case 0 :
                            $this->Flash->error('Your attack failed.');
                            break;
                        case 1 :
                            $this->Flash->success('Your attack succeeded.');
                            break;
                        case 2 :
                            $this->Flash->success('You killed the fighter.');
                            break;
                    }
                }
                // En cas de ramassage d'un objet
                if ($this->request->data('take')) {
                    if (!($this->Fighters->takeTool($this->request->session()->read('myFighterId'),
                        $this->Tools->getToolByCoord($this->request->data('xSelected'),
                            $this->request->data('ySelected'))->id))
                    ) {
                        $this->Flash->error('This tool will not improve your current skills.');
                    };
                }
            }

            //Si le joueur possède au moins un fighter et vivant...
            if (($this->request->session()->check('myFighterId')) && ($this->Fighters->fighterDead($this->request->session()->read('myFighterId'))==false)){

                $this->set('fighterExists', true);
                $this->set('fighterAlive', true);

                // Le combattant actuellement sélectionné
                $this->set('myFighter', $this->Fighters->get($this->request->session()->read('myFighterId')));

                //récupère les constantes de taille du terrain$this->Fighters->ARENA_HEIGHT
                $this->set('arenaWidth', $this->Fighters->ARENA_WIDTH);
                $this->set('arenaHeight', $this->Fighters->ARENA_HEIGHT);

                // The tools owned by the fighter whose id is given in param (here 1 as test)
                // The chosen fighter will be stored in a session variable
                $this->set('sightTool', $this->Tools->getSightTool($this->request->session()->read('myFighterId')));
                $this->set('strengthTool', $this->Tools->getStrengthTool($this->request->session()->read('myFighterId')));
                $this->set('healthTool', $this->Tools->getHealthTool($this->request->session()->read('myFighterId')));

                //stock tous les elements à afficher dans la variable tabArenaElements (DEBUG)
                $this->set('tabArenaElements', $this->Fighters->getArenaElements());
                //stock dans une matrice les elements à afficher dans la vue
                if ($this->request->session()->check('myFighterId')) {
                    $myFighter = $this->Fighters->getFighterById($this->request->session()->read('myFighterId'));
                    $this->set('outputMatrice', $this->Fighters->getOutputMatriceVisible($myFighter->coordinate_x, $myFighter->coordinate_y, $myFighter->skill_sight));
                    $this->set('matrice', $this->Fighters->getMatriceVisible($myFighter->coordinate_x, $myFighter->coordinate_y, $myFighter->skill_sight));
                } else
                    $this->set('outputMatrice', $this->Fighters->getOutputMatriceVisible(5, 5, 2));

            } else {
                $this->set('fighterExists', false);
                $this->set('fighterAlive', false);
            }

            //Si le fighter meurt
           if($this->Fighters->fighterDead($this->request->session()->read('myFighterId'))){

                $this->Flash->error('Your fighter is dead');
                return $this->redirect(['action' => 'fighter']);
            }
        }
    }

    public function diary()
    {

        //Redirection vers la connexion si l'utilisateur n'est pas connecté (c'est qu'il a voulu accéder à la page via l'url)
        if (!$this->request->session()->check('myPlayerId')) {
            $this->redirect(['action' => 'login']);
        } //Si l'utilisateur est connecté...
        else {

            //Si le joueur possède au moins un fighter...
            if ($this->request->session()->check('myFighterId')) {
                $this->set('fighterExists', true);

                $this->loadModel('Events');
                $this->loadModel('Fighters');
                $myFigter = $this->Fighters->getFighterById($this->request->session()->read('myFighterId'));
                $this->set('Event', $this->Events->getRecentEventsVisible($myFigter->coordinate_x,$myFigter->coordinate_y,$myFigter->skill_sight));

            } else {
                $this->set('fighterExists', false);
            }
        }
    }
}