<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
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

    public function getRandomPlayerId()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $id = '';
        do {
            for ($i = 0; $i < (strlen($characters) + 4); $i++) {
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

}
?>