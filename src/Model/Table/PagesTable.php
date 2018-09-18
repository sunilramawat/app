<?php
namespace App\Model\Table;

use App\Model\Entity\Page;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Ps
 */
class PagesTable extends Table
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

        $this->table('pages');
        $this->displayField('p_id');
        $this->primaryKey('p_id');

        $this->belongsTo('Ps', [
            'foreignKey' => 'p_id',
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
            ->requirePresence('p_title', 'create')
            ->notEmpty('p_title');

        $validator
            ->requirePresence('p_description', 'create')
            ->notEmpty('p_description');

        /*$validator
            ->integer('p_showing_order')
            ->requirePresence('p_showing_order', 'create')
            ->notEmpty('p_showing_order');
*/
        $validator
            ->integer('p_status')
            ->requirePresence('p_status', 'create')
            ->notEmpty('p_status');

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
        //$rules->add($rules->existsIn(['p_id'], 'Ps'));
        return $rules;
    }
}
