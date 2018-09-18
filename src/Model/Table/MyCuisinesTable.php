<?php
namespace App\Model\Table;

use App\Model\Entity\MyCuisine;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MyCuisines Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Mcs
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class MyCuisinesTable extends Table
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

        $this->table('my_cuisines');
        $this->displayField('mc_id');
        $this->primaryKey('mc_id');

        $this->belongsTo('Mcs', [
            'foreignKey' => 'mc_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'u_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('mc_name', 'create')
            ->notEmpty('mc_name');

        $validator
            ->requirePresence('mc_photo', 'create')
            ->notEmpty('mc_photo');

        $validator
            ->requirePresence('timming', 'create')
            ->notEmpty('timming');

        $validator
            ->requirePresence('calories', 'create')
            ->notEmpty('calories');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('ingredients', 'create')
            ->notEmpty('ingredients');

        $validator
            ->dateTime('added_date')
            ->requirePresence('added_date', 'create')
            ->notEmpty('added_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['mc_id'], 'Mcs'));
        $rules->add($rules->existsIn(['u_id'], 'Users'));
        return $rules;
    }
}
