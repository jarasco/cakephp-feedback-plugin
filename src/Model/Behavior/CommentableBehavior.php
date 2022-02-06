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

namespace Feedback\Model\Behavior;

// App::uses('ModelBehavior', 'Model');
use Cake\ORM\Behavior;

class CommentableBehavior extends Behavior
{
	public function initialize(array $config)
{
    // Some initialization code here
}
    public function setup(Model $Model, $config = [])
    {
        $Model->bindModel(
            ['hasMany' => ['Comment' =>
                ['className' => 'Feedback.Comment',
                    'conditions' => sprintf('Comment.foreign_model = \'%s\'', $Model->name),
                    'foreignKey' => 'foreign_id',
                    ],
                ],
            ],
            false
        );
    }

    public function cleanup(Model $Model)
    {
        $Model->unbindModel(['hasMany' => ['Comment']], false);
    }
}
