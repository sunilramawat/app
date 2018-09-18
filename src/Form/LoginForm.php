<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class LoginForm extends Form {

    protected function _buildSchema(Schema $schema) {
        return $schema->addField('email', ['type' => 'string'])
                ->addField('password', ['type' => 'password'])
                ->addField('g_recaptcha_response', ['type' => 'string']);
    }

    protected function _buildValidator(Validator $validator) {
        return $validator->requirePresence('email')
            ->add('email', 'validFormat', [
                'rule' => 'email',
                'message' => 'E-mail must be valid.'
            ])->requirePresence('password')
            ->notEmpty('password')
            ->requirePresence('g-recaptcha-response')
            ->notEmpty('g-recaptcha-response');
    }
}
?>