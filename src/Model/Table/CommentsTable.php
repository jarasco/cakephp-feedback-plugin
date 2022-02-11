<?php
/**
    CakePHP Feedback Plugin

    Copyright (C) 2012-3827 dr. Hannibal Lecter / lecterror
    <http://lecterror.com/>

    Multi-licensed under:
        MPL <http://www.mozilla.org/MPL/MPL-1.1.html>
        LGPL <http://www.gnu.org/licenses/lgpl.html>
        GPL <http://www.gnu.org/licenses/gpl.html>
 */

// App::uses('FeedbackAppModel', 'Feedback.Model');
namespace Feedback\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

// use Feedback\Model\FeedbackAppModel;

/**
 * Comment Model
 *
 * @property Foreign $Foreign
 * @property User $User
 */
class CommentsTable extends Table
{
    /**
     * Validation rules
     *
     * @var array
     */
    public function validationDefault(Validator $validator)
    {
        $validator->add('foreign_model', 'notBlank', ['rule' => 'notBlank'])
        ->add('foreign_id', 'notBlank', ['rule' => 'notBlank'])
        ->add('author_name', 'notBlank', ['rule' => 'notBlank', 'message' => 'Name cannot be empty'])
        ->add('author_email', 'notBlank', ['rule' => 'notBlank', 'message' => 'E-mail address cannot be empty'])
        ->add('author_ip', 'ip', ['rule' => 'ip', 'message' => 'Invalid IP address'])
        ->add('content', 'notBlank', ['rule' => 'notBlank', 'message' => 'Name cannot be empty']);

        return $validator;
    }

        // debug($this);
        // public $validate = array
        //     (
        //         'foreign_model' => array
        //         (
        //             '' => array
        //             (
        //                 'rule' => array('notBlank'),
        //                 'allowEmpty' => false,
        //                 'required' => true,
        //             ),
        //         ),
        //         'foreign_id' => array
        //         (
        //             'notBlank' => array
        //             (
        //                 'rule' => array('notBlank'),
        //                 'allowEmpty' => false,
        //                 'required' => true,
        //             ),
        //         ),
        //         'author_name' => array
        //         (
        //             'notBlank' => array
        //             (
        //                 'rule' => array('notBlank'),
        //                 'message' => 'Name cannot be empty',
        //                 'allowEmpty' => false,
        //                 //'required' => false,
        //                 //'last' => false, // Stop validation after this rule
        //                 //'on' => 'create', // Limit validation to 'create' or 'update' operations
        //             ),
        //         ),
        //         'author_email' => array
        //         (
        //             'notBlank' => array
        //             (
        //                 'rule' => array('notBlank'),
        //                 'message' => 'E-mail address cannot be empty',
        //                 'allowEmpty' => false,
        //                 //'required' => false,
        //                 //'last' => false, // Stop validation after this rule
        //                 //'on' => 'create', // Limit validation to 'create' or 'update' operations
        //             ),
        //             'email' => array
        //             (
        //                 'rule' => array('email'),
        //                 'message' => 'E-mail address is not valid',
        //             )
        //         ),
        //         'author_ip' => array
        //         (
        //             'ip' => array
        //             (
        //                 'rule' => array('ip'),
        //                 'message' => 'Invalid IP address',
        //                 'allowEmpty' => true,
        //             )
        //         ),
        //         'content' => array
        //         (
        //             'notBlank' => array
        //             (
        //                 'rule' => array('notBlank'),
        //                 'message' => 'Comment cannot be empty',
        //                 'allowEmpty' => false,
        //                 //'required' => false,
        //                 //'last' => false, // Stop validation after this rule
        //                 //'on' => 'create', // Limit validation to 'create' or 'update' operations
        //             ),
        //         ),
        //     );

        //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Feedback.Commentable');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            ]);
    }

        // public $belongsTo = array
        //     (
        //         'User' => array
        //         (
        //             'className' => 'User',
        //             'foreignKey' => 'user_id',
        //             'conditions' => '',
        //             'fields' => '',
        //             'order' => ''
        //         )
        //     );

        // public $actsAs = array
        //     (
        //         'Feedback.Polymorphic' => array
        //         (
        //             'modelField'    => 'foreign_model',
        //             'foreignKey'    => 'foreign_id'
        //         ),
        //         'Feedback.Honeypot' => array
        //         (
        //             'field' => 'hairy_pot',
        //             'message' => 'E-mail address is not valid',
        //             'errorField' => 'author_email',
        //         ),
        //     );

        public $order = 'Comment.created ASC';
}
