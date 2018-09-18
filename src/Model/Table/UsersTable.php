<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Bookmarks
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

/*
        $this->HasMany('UserKeys', [
            'foreignKey' => 'uk_u_id',
            'joinType' => 'INNER'
        ]);

        
        $this->HasMany('Answers', [
            'foreignKey' => 'u_id',
            'joinType' => 'INNER'
        ]);
*/    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

       /* $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');
        */
        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');
           /*  ->add('password', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'Titles need to be at least 6 characters long',
                ]
                 ]);*/

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
   /* public function buildRules(RulesChecker $rules)
    {
        //$rules->add($rules->isUnique(['email']));
        //return $rules;
    }*/
}
