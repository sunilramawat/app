<?php
namespace App\Model\Table;

use App\Model\Entity\Review;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reviews Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Rs
 * @property \Cake\ORM\Association\BelongsTo $MyCuisines
 */
class ReviewsTable extends Table
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

        $this->table('reviews');
        $this->displayField('r_id');
        $this->primaryKey('r_id');

        $this->belongsTo('Rs', [
            'foreignKey' => 'r_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MyCuisines', [
            'foreignKey' => 'r_mc_id',
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
            ->integer('r_by')
            ->requirePresence('r_by', 'create')
            ->notEmpty('r_by');

        $validator
            ->integer('r_to')
            ->requirePresence('r_to', 'create')
            ->notEmpty('r_to');

        $validator
            ->requirePresence('r_ratting', 'create')
            ->notEmpty('r_ratting');

        $validator
            ->requirePresence('r_comment', 'create')
            ->notEmpty('r_comment');

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
        $rules->add($rules->existsIn(['r_id'], 'Rs'));
        $rules->add($rules->existsIn(['r_mc_id'], 'MyCuisines'));
        return $rules;
    }
}
