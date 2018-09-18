<?php
namespace App\Model\Table;

use App\Model\Entity\PhotoGallery;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PhotoGalleries Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Pgs
 * @property \Cake\ORM\Association\BelongsTo $MyCuisines
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class PhotoGalleriesTable extends Table
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

        $this->table('photo_galleries');
        $this->displayField('pg_id');
        $this->primaryKey('pg_id');

        $this->belongsTo('Pgs', [
            'foreignKey' => 'pg_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MyCuisines', [
            'foreignKey' => 'pg_mc_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'pg_u_id',
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
            ->requirePresence('ph_photo', 'create')
            ->notEmpty('ph_photo');

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
        $rules->add($rules->existsIn(['pg_id'], 'Pgs'));
        $rules->add($rules->existsIn(['pg_mc_id'], 'MyCuisines'));
        $rules->add($rules->existsIn(['pg_u_id'], 'Users'));
        return $rules;
    }
}
