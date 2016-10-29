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
->notEmpty('password', 'Un mot de passe est nécessaire')
;
}

}
?>