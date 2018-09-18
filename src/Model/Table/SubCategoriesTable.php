<?php
namespace App\Model\Table;

use App\Model\Entity\SubCategory;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SubCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Scs
 * @property \Cake\ORM\Association\BelongsTo $Categories
 */
class SubCategoriesTable extends Table
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

        $this->table('sub_categories');
        $this->displayField('sc_id');
        $this->primaryKey('sc_id');

        $this->belongsTo('Scs', [
            'foreignKey' => 'sc_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'sc_c_id',
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
            ->requirePresence('sc_name', 'create')
            ->notEmpty('sc_name');

        $validator
            ->requirePresence('sc_description', 'create')
            ->notEmpty('sc_description');

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
       /* $rules->add($rules->existsIn(['sc_id'], 'Scs'));
        $rules->add($rules->existsIn(['sc_c_id'], 'Categories'));*/
        return $rules;
    }
}
