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
namespace Feedback\View\Helper;

use Cake\Core\Exception\Exception as CakeException;
use Cake\ORM\TableRegistry;
use Cake\View\Helper;
use Cake\View\View;

// App::uses('AppHelper', 'View/Helper');
// App::uses('Sanitize', 'Utility');

// class CommentsHelper extends AppHelper
class CommentsHelper extends Helper
{
    public $helpers = ['Html', 'Form', 'Time', 'Goodies.Gravatar'];

    private $_defaultOptions = [
            'model' => null,
            'showForm' => true,
    ];

    function __construct(View $view, $settings = [])
    {
        parent::__construct($view, $settings);

        if (!empty($this->getView()->getRequest()->getParam('models'))) {
            $this->_defaultOptions['model'] = key($this->request->params['models']);
        }
    }

    /**
     * Data must contain the row which hasMany comments and an array of comments (optional obviously).
     * 
     * Options available:
     * 
     *  - model: In case the detection doesn't work, this will override the model.
     *  - showForm: Whether to show the "add new comment" form or not, defaults to true.
     *
     * @param array $data Data to use for comments and comment form
     * @param array $options Options which override those detected by the helper.
     * @return string HTML output
     */
    function display_for($data, array $options = [])
    {
        $options = array_merge($this->_defaultOptions, $options);
// debug($data); return; //JAS model = $data->getSource()
        if (empty($options['model'])) {
            throw new CakeException(__('Missing model for {0}::{1}() call', __CLASS__, __FUNCTION__));
        }

        $output = '';

        if (isset($data->comment) && !empty($data->comment)) {
            $output .= $this->_View->element('Feedback.comment_index', ['comments' => $data->comment]);
        }

        if ($options['showForm']) {
            // App::uses($options['model'], 'Model');
            $Model = TableRegistry::getTableLocator()->get($options['model']);
// debug($data);
            if (empty($Model)) {
                throw new CakeException(__('Missing model for {0}::{1}() call', __CLASS__, __FUNCTION__));
            }

		$output .= $this->form($options['model'], $data->get($Model->getPrimaryKey()));
        }

        return $output;
    }

    public function form($foreign_model, $foreign_id)
    { 
        // App::uses($foreign_model, 'Model');
        // $Model = ClassRegistry::init($foreign_model);
        $Model = TableRegistry::getTableLocator()->get($foreign_model);

        if (empty($Model)) {
            throw new CakeException(__('Missing model for {0}::{1}() call', __CLASS__, __FUNCTION__));
        }

        if (!$Model->exists([$Model->getPrimaryKey() => $foreign_id])) {
            throw new CakeException(__('Missing item with id {0} for {1}::{2}() call', $foreign_id, __CLASS__, __FUNCTION__));
        }

        $options = compact('foreign_model', 'foreign_id');
        return $this->_View->element('Feedback.comment_add', $options);
    }
}