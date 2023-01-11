<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\View\View;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null) {
        } else {
            $this->redirect(['action' => 'login']);
        }

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null) {
        } else {
            $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $productImage = $this->request->getData("image");
            $fileName = $productImage->getClientFilename();
            $data["image"] = $fileName;
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $hasFileError = $productImage->getError();

                if ($hasFileError > 0) {
                    $data["image"] = "";
                } else {
                    $fileType = $productImage->getClientMediaType();

                    if ($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                        $imagePath = WWW_ROOT . "img/" . $fileName;
                        $productImage->moveTo($imagePath);
                        $data["image"] = $fileName;
                    }
                }
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null) {
        } else {
            $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $fileName2 = $user['image'];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $productImage = $this->request->getData("image");
            $fileName = $productImage->getClientFilename();
            if ($fileName == '') {
                $fileName = $fileName2;
            }

            $data["image"] = $fileName;
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $hasFileError = $productImage->getError();
                if ($hasFileError > 0) {
                    $data["image"] = "";
                } else {
                    $fileType = $productImage->getClientMediaType();

                    if ($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                        $imagePath = WWW_ROOT . "img/" . $fileName;
                        $productImage->moveTo($imagePath);
                        $data["image"] = $fileName;
                    }
                }
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $password =  $this->request->getData('password');

            $result = $this->Users->login($email, $password);
            if ($result) {
                $session = $this->getRequest()->getSession();
                $session->write('email', $email);
                $this->Flash->success(__('The user has been logged in successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Please enter valid credential..'));
        }
        $this->set(compact('user'));
    }

    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();
        return $this->redirect(['action' => 'login']);
    }

    public function forgot()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $result = $this->Users->checkEmail($email);
            if ($result) {
                $token = rand(10000, 100000);
                $result2 = $this->Users->insertToken($email, $token);
                if ($result2) {
                    $user->email = $email;
                    $mailer = new Mailer('default');
                    $mailer->setTransport('gmail');
                    $mailer->setFrom(['sachinsingh10101997@gmail.com' => 'Sachin']);
                    $mailer->setTo($email);
                    $mailer->setEmailFormat('html');
                    $mailer->setSubject('Verify New Account');
                    $mailer->deliver('<a href="http://localhost:8765/users/reset?token=' . $token . '">Click here</a>');

                    $this->Flash->success(__('Reset email send successfully.'));

                    return $this->redirect(['action' => 'login']);
                }
            }
            $this->Flash->error(__('Please enter valid credential..'));
        }
        $this->set(compact('user'));
    }

    public function reset()
    {
        $user = $this->Users->newEmptyEntity();
        $token = $_REQUEST['token'];
        $result = $this->Users->checkToken($token);
        if ($result) {
            if ($this->request->is('post')) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                $password = $this->request->getData('password');
                $res1 = preg_match('(^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]*).{8,}$)', $password);
                $confirm_password = $this->request->getData('confirm_password');
                $res2 = preg_match('(^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]*).{8,}$)', $confirm_password);
                if($res1 ==1 && $res2 == 1){
                    $result2 = $this->Users->resetPassword($token, $password);
                    if ($result2) {
                        $this->Flash->success(__('Password updated successfully.'));
                        return $this->redirect(['action' => 'login']);
                    }
                }
                $this->Flash->error(__('Please enter valid password'));
            }
        } else {
            return $this->redirect(['action' => 'login']);
        }

        $this->set(compact('user'));
    }
}
