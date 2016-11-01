<?php

// src/Model/Table/UsersTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use PhpParser\Node\Expr\Cast\Array_;
use Cake\Validation\Validator;

class PlayersTable extends Table
{

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('email', "Un email est nécessaire")
            ->notEmpty('password', 'Un mot de passe est nécessaire');
    }

    public function inscription($data_post, $request_data)
    {
        $player = $this->newEntity();
        if ($data_post) {
            $player = $this->patchEntity($player, $request_data);
            $player->id = $this->getRandomPlayerId(); //Mettre l'id random

            if ($this->save($player)) {
                return true;
            }

        }

    }

    public function isEmailUnique($email)
    {
        $players = $this->find('all');
        $tabPlayers = $players->toArray();
        foreach ($tabPlayers as $thisPlayer) {
            if ($email == $thisPlayer->email) {
                return false;
            }
        }

        return true;
    }

    public function connexion($data_post, $request_data)
    {

        $player = $this->newEntity();
        if ($data_post) {

            return $player['email'];

        }
        else return "test";


    }


    /**
     * Fonction qui retourne le player qui a pour Id la variable récupérée en paramètre
     * @param $email
     * @return array
     */
    public function getPlayerByEmail($email)
    {
        $player= $this->find('all')->order('email desc');
        $tabPlayer = $player->toArray();
        $playerByEmail=null;
        foreach ($tabPlayer as $key => $myPlayer) {
            if ($myPlayer['email'] == $email) {
                $playerByEmail = $myPlayer;
            }
        }
        return $playerByEmail;
    }



    public function checkConnexion($email, $password) {
        $myplayer = $this->getPlayerByEmail($email);

        if (!empty($myplayer) && (!strcmp($myplayer->password, $password))) {
            return true;
        }
        else return false;
    }


    public function getRandomPlayerId()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $id = '';
        do {
            for ($i = 0; $i < 36; $i++) {
                $id .= $characters[rand(0, strlen($characters) - 1)];
            }
            $id[8] = '-';
            $id[13] = '-';
            $id[18] = '-';
            $id[23] = '-';
        } while (!$this->isIdUnique($id));
        return $id;
    }

    public function isIdUnique($myId)
    {
        $ids = $this->find()->extract('id');

        foreach ($ids as $thisId) {
            if ($thisId == $myId)
                return false;
        }
        return true;

    }


    /**
     * Fonction qui retourne le mot de passe correspondant a lemail récupérée en paramètre
     * @param $email
     * @return null
     */
    public function getPasswordByEmail($email)
    {
        $player= $this->find('all')->order('email desc');
        $tabPlayer = $player->toArray();
        $passwordByEmail=null;
        foreach ($tabPlayer as $key => $myPlayer) {
            if ($myPlayer['email'] == $email) {

                $passwordByEmail= $myPlayer->password;
            }
        }
        return $passwordByEmail;
    }

    public function getPlayerIdbyEmail($email) {

        $player= $this->find('all')->order('email desc');
        $tabPlayer = $player->toArray();
        $playerByEmail=null;
        foreach ($tabPlayer as $key => $myPlayer) {
            if ($myPlayer['email'] == $email) {

                $idByEmail= $myPlayer->id;
            }
        }
        return $idByEmail;
    }


    public function sendPasswordByMail($email){
        $subject = 'Web arena password';
        $message = 'Here is your Web Arena password : '.$this->getPasswordByEmail($email);

        mail($email, $subject, $message);
    }

}
?>