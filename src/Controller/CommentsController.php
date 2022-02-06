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

namespace Feedback\Controller;

use Cake\ORM\TableRegistry;
use Feedback\Controller\AppController;
use Feedback\Model\Table\CommentsTable;

/**
 * Comments Controller
 *
 * @property CommentsComponent $Comments
 * @property AuthComponent $Auth
 */
// class CommentsController extends FeedbackAppController
class CommentsController extends AppController
{
    public $components = ['Feedback.Comments'];

    public function add($foreign_model = null, $foreign_id = null)
    {
        if (empty($foreign_model) ||
            empty($foreign_id) ||
            !$this->request->is('post')
            )
        {
            return $this->redirect('/');
        }

        // App::uses($foreign_model, 'Model');
        // $Model = ClassRegistry::init($foreign_model);
        $Model = TableRegistry::getTableLocator()->get($foreign_model);

        if (!($Model instanceof \App\Model\AppModel))
        {
            return $this->redirect('/');
        }
        // debug($this->request->getData());
// debug($Model);
// debug($foreign_id);
        if ($Model->exists([$Model->getPrimaryKey() => $foreign_id]) == false)
        {
            return $this->redirect('/');
        }

        if (!isset($this->request->data['foreign_model']) ||
            !isset($this->request->data['foreign_id']) ||
            $this->request->data['foreign_model'] != $foreign_model ||
            $this->request->data['foreign_id'] != $foreign_id)
        {
            return $this->redirect('/');
        }

        $user_id = null;

        if ($this->Authentication->getIdentity()->getIdentifier() !== null)
        {
            $user_id = $this->Authentication->getIdentity()->getIdentifier();
        }

        $this->request->data['foreign_model'] = $Model->getAlias();
        $this->request->data['foreign_id'] = $foreign_id;
        $this->request->data['user_id'] = $user_id;
        $this->request->data['author_ip'] = $this->request->clientIp();

        $Comments = TableRegistry::getTableLocator()->get('Feedback.Comments');
        $comment = $Comments->newEntity($this->request->getData());
//         debug($comment);
// debug($Comments->save($comment));
        if (!$Comments->save($comment))
        {
            // $this->set('validation_errors', $Comments->validationErrors);
            $this->set('validation_errors', $comment->getErrors());

            return;
        }

        if ($this->request->data['remember_info'])
        {
            $this->Comments->saveInfo();
        }
        else
        {
            $this->Comments->forgetInfo();
        }

        $this->redirect($this->request->referer() . '#comment-' . $comment->id);
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $redirect = $this->request->referer() . '#comment';
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        // $this->Comment->id = $id;
        $Comments = TableRegistry::getTableLocator()->get('Feedback.Comments');
        $comment = $Comments->get($id);
        if (empty($comment)) {
            throw new NotFoundException(__d('feedback', 'Invalid request.'));
        }
        if ($Comments->delete($comment)) {
            $this->Flash->success(__d('feedback', 'Record deleted.'));
            return $this->redirect($redirect);
        }
        $this->Flash->error(__d('feedback', 'Failed, record was not deleted.'));

        $this->redirect($redirect);
    }
}
