<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\FightersTable;
use App\Model\Table\PlayersTable;
use \Cake\Network\Exception;
use Cake\Event\Event;
use Cake\Utility\Text;
use Google_Client;
use Google_Service_Oauth2;


define('GOOGLE_OAUTH_CLIENT_ID', '454279630539-sg4a02pjdu33noimuamaaa61fst83t7m.apps.googleusercontent.com');
define('GOOGLE_OAUTH_CLIENT_SECRET', 'Lyfrmfu9XPtkrNSkSRy4RCHQ');
define('GOOGLE_OAUTH_REDIRECT_URI', 'http://localhost:8888/arena/arenas/googlecallback');

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController
{
    public function index()
    {
        $this->loadModel('Players');

        //Si l'utilisateur est connecté, afficher le changement de mot de passe
        if ($this->request->session()->check('myPlayerId')) {
            $this->set('isConnected', true);

            if($this->request->data('changePassword')){

                $oldPassword = $this->request->data['oldPassword'];
                $newPassword1 = $this->request->data['newPassword1'];
                $newPassword2 = $this->request->data['newPassword2'];
                if($oldPassword == $this->Players->getPasswordById($this->request->session()->read('myPlayerId'))) {
                    if($newPassword1 == $newPassword2){
                        $this->Players->setPasswordById($this->request->session()->read('myPlayerId'), $newPassword1);
                        $this->Flash->success('Your password has been changed');
                    }
                    else{
                        $this->Flash->error('You can\'t enter two different new passwords!');
                    }
                }
                else{
                    $this->Flash->error('This is not your old password!');
                }
            }
        } //Si l'utilisateur est connecté...
        else {
            $this->set('isConnected', false);
        }
    }


    public function googlelogin()
    {

        $this->loadModel('Players');
        $client = new Google_Client();
        $client->setClientId(GOOGLE_OAUTH_CLIENT_ID);
        $client->setClientSecret(GOOGLE_OAUTH_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);

        $client->setScopes(array(
            "https://www.googleapis.com/auth/userinfo.profile",
            'https://www.googleapis.com/auth/userinfo.email'
        ));
        $url = $client->createAuthUrl();
        $this->redirect($url);

    }

    public function googlecallback()
    {

        $this->loadModel('Players');
        $this->loadModel('Fighters');
        $client = new Google_Client();
        /* Création de notre client Google */
        $client->setClientId(GOOGLE_OAUTH_CLIENT_ID);
        $client->setClientSecret(GOOGLE_OAUTH_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);

        $client->setScopes(array(
            "https://www.googleapis.com/auth/userinfo.profile",
            'https://www.googleapis.com/auth/userinfo.email'
        ));
        $client->setApprovalPrompt('auto');

        /* si dans l'url le paramètre de retour Google contient 'code' */
        if (isset($this->request->query['code'])) {
            // Alors nous authentifions le client Google avec le code reçu
            $client->authenticate($this->request->query['code']);
            // et nous plaçons le jeton généré en session
            $this->request->Session()->write('access_token', $client->getAccessToken());
        }

        /* si un jeton est en session, alors nous le plaçons dans notre client Google */
        if ($this->request->Session()->check('access_token') && ($this->request->Session()->read('access_token'))) {
            $client->setAccessToken($this->request->Session()->read('access_token'));
        }

        /* Si le client Google a bien un jeton d'accès valide */
        if ($client->getAccessToken()) {
        // alors nous écrivons le jeton d'accès valide en session
            $this->request->Session()->write('access_token', $client->getAccessToken());
        // nous créons une requête OAuth2 avec le client Google paramétré
            $oauth2 = new Google_Service_Oauth2($client);
        // et nous récupérons les informations de l'utilisateur connecté
            $user = $oauth2->userinfo->get();
            try {

                if (!empty($user)) {
                    // si l'utilisateur est bien déclaré, nous vérifions si dans notre table Users il existe l'email de
                    // l'utilisateur déclaré ou pas
                    $result = $this->Players->find('all')
                        ->where(['email' => $user['email']])
                        ->first();
                    if ($result) {
                    // si l'email existe alors nous déclarons l'utilisateur comme authentifié sur CakePHP
                        $this->request->session()->write('myPlayerId', $this->Players->getPlayerByEmail($result->toArray()['email'])->id);
                        $this->Flash->success('CONNECTED');
                        $this->redirect(['action' => 'index']);


                    } else {
                    // si l'utilisateur n'est pas dans notre utilisateur, alors nous le créons avec les informations
                        // récupérées par Google+
                        $data = array();
                        $data['email'] = $user['email'];
                        $data['password'] = $user['id'];
                        //$data['uuid'] = Text::uuid();
                        $entity = $this->Players->newEntity($data);

                        if ($this->Players->save($entity)) {
                    // et ensuite nous déclarons l'utilisateur comme authentifié sur CakePHP
                            $this->request->session()->write('myPlayerId', $this->Players->getPlayerByEmail($result->toArray()->email)->id);
                            $this->Flash->success('NEW PLAYER');
                            $this->redirect(['action' => 'index']);
                        } else {
                            $this->Flash->error('ERROR connection');
                    // et nous redirigeons vers la page de succès de connexion
                            $this->redirect(['action' => 'index']);
                        }
                    }
                } else {
                // si l'utilisateur n'est pas valide alors nous affichons une erreur
                    $this->Flash->error('Erreur les informations Google n\'ont pas été trouvée');
                    $this->redirect(['action' => 'login']);
                }
            } catch (\Exception $e) {
                $this->Flash->error('error FROM Google');
                return $this->redirect(['action' => 'login']);
            }
        }
    }

    public function login()
    {
        $this->set('emailContent', false);

        //Si on est connecté, on se déconnecte
        if ($this->request->session()->check('myPlayerId')) {
            $this->request->session()->destroy();
        }

        $this->loadModel('Players');
        $this->loadModel('Fighters');
        $this->loadModel('Tools');

        $data_post = $this->request->is('post');
        if ($this->request->data('inscription')) {
            $data_inscription = $this->request->data;
            $this->set('mail', $this->request->data('email'));

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
        } else if ($this->request->data('connexion')) {

            $data_connexion = $this->request;
            $this->set('mailCo', $this->request->data('email'));

            if ($data_connexion->data('email') && $data_connexion->data('password')) {

                if ($this->Players->checkConnexion($this->request->data['email'], $this->request->data['password'])) {
                    $this->Flash->success('You are now logged in.');

                    //enregistrement des variables des variables de session

                    $this->request->session()->write('myPlayerId', $this->Players->getPlayerByEmail($this->request->data['email'])->id);
                    if ($this->Fighters->getFightersByPlayer($this->Players->getPlayerByEmail($this->request->data['email'])->id)) {
                        $this->request->session()->write('myFighterId', $this->Fighters->getBestFighterbyPlayer($this->Players->getPlayerByEmail($this->request->data['email'])->id)[0]->id);
                    }

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error('Wrong email - password combination.');
                }
            }
        } else if ($this->request->data('lostMdp')) {
            if($this->Players->emailExists($this->request->data['email'])){
                $this->set('emailContent', $this->Players->getPasswordMail($this->Players->getPlayerByEmail($this->request->data['email'])->id));
                $this->Flash->success('Your new password has been sent to '.$this->request->data['email']);
            }
            else{
                $this->Flash->error('This email doesn\'t match any account !');
            }
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

            //si on a envoyé un formulaire...
            if ($this->request->is('post')) {
                if ($this->request->data('select')) {
                    $this->request->session()->write('myFighterId',$this->request->data('fighterId'));

                }
                if ($this->request->data('newFighter')) {
                    $testCreation = $this->Fighters->createFighter($this->request->data('name'), $this->request->data('avatar'), $this->request->session()->read('myPlayerId'));
                    if ($testCreation==-1) {
                        $this->Flash->error('Could not upload your avatar. Check the extension.');
                    } else {
                        $this->Flash->success('Your fighter has been created.');
                        $this->request->session()->write('myFighterId', $testCreation);
                    }
                }
            }


            }

            //Si le joueur possède au moins un fighter...
            if ($this->request->session()->check('myFighterId')) {
                if (!$this->Fighters->isFighterDead($this->request->session()->read('myFighterId'))) {
                    $this->set('fighterAlive', true);

                    // The tools owned by the fighter whose id is given in param (here 1 as test)
                    // The chosen fighter will be stored in a session variable
                    $this->set('sightTool', $this->Tools->getSightTool($this->request->session()->read('myFighterId')));
                    $this->set('strengthTool', $this->Tools->getStrengthTool($this->request->session()->read('myFighterId')));
                    $this->set('healthTool', $this->Tools->getHealthTool($this->request->session()->read('myFighterId')));
                    $this->set('myFighter', $this->Fighters->get($this->request->session()->read('myFighterId')));

                    $this->set('myFightersByPlayer', $this->Fighters->getFightersByPlayer($this->request->session()->read('myPlayerId')));

                }
                else {
                    $this->set('fighterAlive', false);
                    $this->Flash->error('Your fighter is dead!');
                }
            }
            else{
                $this->set('myFightersByPlayer', []);
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

            //Création des objets
            $this->Tools->createTool('health', 2);
            $this->Tools->createTool('strength', 2);
            $this->Tools->createTool('sight', 2);
            $this->Tools->createTool('health', 4);
            $this->Tools->createTool('sight', 6);

            // Traitement des actions
            if ($this->request->is('post')) {
                if(!$this->Fighters->isFighterDead($this->request->session()->read('myFighterId'))) {
                    $this->set('fighterAlive', true);
                    // En cas de mouvement du combattant
                    if ($this->request->data('move')) {
                        $this->Fighters->moveFighter($this->request->session()->read('myFighterId'), $this->request->data('xSelected'), $this->request->data('ySelected'));
                    }
                    // En cas d'attaque du combattant
                    if($this->Fighters->getFighterByCoord($this->request->data('xSelected'), $this->request->data('ySelected'))!=null){

                        if ($this->request->data('attack')) {
                            switch ($this->Fighters->attack($this->request->session()->read('myFighterId'),
                                $this->Fighters->getFighterByCoord($this->request->data('xSelected'), $this->request->data('ySelected'))->id)) {
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
                    //Si fighter montre d'un niveau
                    if ($this->request->data('health')) {
                        $choice = 3;
                        $this->Fighters->fighterProgression($this->request->session()->read('myFighterId'), $choice);
                    }
                    if ($this->request->data('strength')) {
                        $choice = 2;
                        $this->Fighters->fighterProgression($this->request->session()->read('myFighterId'), $choice);
                    }
                    if ($this->request->data('sight')) {
                        $choice = 1;
                        $this->Fighters->fighterProgression($this->request->session()->read('myFighterId'), $choice);
                    }

                }
                else{
                    $this->set('fighterAlive', false);
                    return $this->redirect(['action' => 'fighter']);
                }

            }

            //Si le joueur possède au moins un fighter et vivant...
            if($this->request->session()->check('myFighterId')) {
                if(!$this->Fighters->isFighterDead($this->request->session()->read('myFighterId'))){

                    $this->set('fighterExists', true);
                    $this->set('fighterAlive', true);

                    // Le combattant actuellement sélectionné
                    $this->set('myFighter', $this->Fighters->get($this->request->session()->read('myFighterId')));

                    //récupère les constantes de taille du terrain$this->Fighters->ARENA_HEIGHT
                    $this->set('arenaWidth', $this->Fighters->ARENA_WIDTH);
                    $this->set('arenaHeight', $this->Fighters->ARENA_HEIGHT);

                    //check si le combattant est pret a monter de niveau
                    $this->set('isReadyToLvlUp', $this->Fighters->isFighterReadyToLvlUp($this->request->session()->read('myFighterId')));

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

                }
                else {
                    $this->set('fighterAlive', false);
                    return $this->redirect(['action' => 'fighter']);
                }
            }
            else {
                $this->set('fighterExists', false);
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
                $this->set('Event', $this->Events->getRecentEventsVisible($myFigter->coordinate_x, $myFigter->coordinate_y, $myFigter->skill_sight));

            } else {
                $this->set('fighterExists', false);
            }
        }
    }
}