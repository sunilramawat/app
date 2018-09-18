<?php
namespace App\Model\Table;

use App\Model\Entity\ExtraItem;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExtraItems Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Eis
 * @property \Cake\ORM\Association\BelongsTo $MyCuisines
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class ExtraItemsTable extends Table
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

        $this->table('extra_items');
        $this->displayField('ei_id');
        $this->primaryKey('ei_id');

        $this->belongsTo('Eis', [
            'foreignKey' => 'ei_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MyCuisines', [
            'foreignKey' => 'ei_mc_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'ei_u_id',
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
            ->requirePresence('ei_name', 'create')
            ->notEmpty('ei_name');

        $validator
            ->integer('ei_status')
            ->requirePresence('ei_status', 'create')
            ->notEmpty('ei_status');

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
        $rules->add($rules->existsIn(['ei_id'], 'Eis'));
        $rules->add($rules->existsIn(['ei_mc_id'], 'MyCuisines'));
        $rules->add($rules->existsIn(['ei_u_id'], 'Users'));
        return $rules;
    }
}
