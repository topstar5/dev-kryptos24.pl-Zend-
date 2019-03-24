<?php

class IndexController extends Muzyka_Action
{
    protected $debugLogin = false;
    private $mcrypt;
    private $key;
    private $iv;
    private $bit_check;
    
    public function init()
    {
        parent::init();
        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
        $this->mcrypt = $config->mcrypt->toArray();
        $this->key = $this->mcrypt ['key'];
        $this->iv = $this->mcrypt ['iv'];
        $this->bit_check = $this->mcrypt ['bit_check'];
        
        if ($registry->get('config')->production->dev->debug) {
            $this->debugLogin = true;
        }
    }
    
    public function indexAction()
    {
        $req = $this->getRequest();
        $reason = $req->getParam('r', null);
        
        $spoofPassword = Zend_Registry::getInstance()->get('config')->production->dev->spoof->login;
        if ($spoofPassword) {
            $this->view->data = ['login' => 'superadmin'];
        }
        
        if ($reason) {
            switch ($reason) {
                case 's4' :
                    $this->view->message = 'Przekroczony limit nieudanych logowań do systemu';
                    break;
                case 's3' :
                    $this->view->message = 'Twoja sesja wygasła, w celach bezpieczeństwa zaloguj się ponownie.';
                    break;
                case 's1':
                    case 's2':
                        $this->view->message = 'Niepoprawny login lub hasło.';
                        break;
                    case 'pwchange':
                        $this->view->message = 'Procedura zmiany hasła przebiegła pomyślnie. Zaloguj się ponownie.';
                        break;
                    case "403":
                        $this->view->message = 'Nie masz dostępu do tego zasobu';
                        break;
            }
        }
        
        $this->view->simpleLogin = Application_Service_Utilities::getModel('Settings')->pobierzUstawienie('SIMPLE LOGIN');
    }
    
    public function reloginWidgetAction()
    {
        $this->disableLayout();
        $login = $this->_getParam('login');
        
        if (!$login) {
            echo 'force_logout';
            exit;
        }
        
        $userModel = Application_Service_Utilities::getModel('Users');
        $user = $userModel->getUserByLogin($login);
        
        list ($length, $gwiazdki) = Application_Service_Authorization::getInstance()->getPasswordMask($user->password);
        
        if ($length < 8) {
            echo 'force_logout';
            exit;
        }
        
        $this->view->gwiazdki = $gwiazdki;
        $this->view->length = $length;
        $this->view->login = $login;
        
        $this->view->simpleLogin = Application_Service_Utilities::getModel('Settings')->pobierzUstawienie('SIMPLE LOGIN');
    }
    
    public function ajaxAuthorizeAction()
    {
        $loginResult = $this->loginAction(true);
        $response = ['status' => 'unauthorized', 'sessionExpiredAt' => $this->userSession->user->session_expired_at];
        
        if ($loginResult === true) {
            $response['status'] = 'authorized';
        } else {
            $response['status'] = 'unauthorized';
        }
        
        $this->outputJson($response);
    }
    
    public function preloginAction()
    {
        $req = $this->getRequest();
        $login = $req->getParam('login', 0);
        if (!$login) {
            $this->_redirect('/');
        }
        $userModel = Application_Service_Utilities::getModel('Users');
        $user = $userModel->getUserByLogin($login);
        
        list ($length, $gwiazdki) = Application_Service_Authorization::getInstance()->getPasswordMask($user->password);
        
        if ($this->debugLogin) {
            vd('Password', Application_Service_Authorization::getInstance()->decryptPasswordFull($user->password));
        }
        
        if ($length < 8) {
            $this->_redirect('/');
        }
        
        $this->view->gwiazdki = $gwiazdki;
        $this->view->length = $length;
        
        $this->view->login = $login;
    }
    
    private function decryptPassword($encrypted_text)
    {
        $authorizationService = Application_Service_Authorization::getInstance();
        return $authorizationService->decryptPassword($encrypted_text);
    }
    
    private function comparePasswords($enterPassword, $password)
    {
        if (is_array($enterPassword)) {
            if (count($enterPassword) < 5) {
                return false;
            }
            
            foreach ($enterPassword as $key => $item) {
                if ($key > mb_strlen($password) - 1) {
                    return false;
                }
                
                if (mb_substr($password, $key, 1) !== $item) {
                    return false;
                }
            }
            
            return true;
        } elseif (is_string($enterPassword)) {
            return $enterPassword === $password;
        }
        
        return false;
    }

public function ajaxPasswordPromptAction()
{
    $req = $this->getRequest();
    $enteredPassword = $req->getParam('password');
    
    if (Application_Service_Authorization::getInstance()->sessionCheckPassword($enteredPassword)) {
        echo 1;
        exit;
    }
    
    echo 0;
    exit;
}

public function registerAction(){
    
    $osobyModel = Application_Service_Utilities::getModel('Osoby');
    $usersModel =  Application_Service_Utilities::getModel('Users');
    $authorizationService = Application_Service_Authorization::getInstance();
    
    $req = $this->getRequest();
    $organisation = $req->getParam('organisation', '');
    $firstname = $req->getParam('firstname', '');
    $surname = $req->getParam('surname', '');
    $nip = $req->getParam('nip', '');
    
    $this->view->organisation = $organisation;
    $this->view->firstname = $firstname;
    $this->view->surname = $surname;
    $this->view->nip = $nip;
    
    if ($organisation && $firstname && $surname){
        
        $data = array();
        $data['organisation'] = $organisation;
        $data['surname'] = $surname;
        $data['name'] = $firstname;
        $data['nip'] = $nip;
        
        $dataOsoby['imie'] = 'DE';  
        $dataOsoby['nazwisko'] = 'MO';
        $dataOsoby['status'] = 1;
        $login = $osobyModel->generateUserLogin($dataOsoby);
        $data['login'] = $login;
        $password = $authorizationService->generateRandomPassword();
        $dataOsoby['login_do_systemu'] = $login;
        $id = $osobyModel->save($dataOsoby);
        $dataUsers = array();
        $dataUsers['login'] = $login;
        $dataUsers['spoof_id'] = $id;
        
        $dataUsers['password'] = $usersModel->encryptPassword($password) . '~' . strlen($password);
        $dataUsers['set_password_date'] = date('Y-m-d H:i:s');
        $usersModel->save($dataUsers);
        
        $row = $osobyModel->getOne($id);
        $row->rights = Application_Service_Register::getInstance()->getDefaultRights();
        $row->save();
        
        $registrationDataModel = Application_Service_Utilities::getModel('RegistrationData');
        $registrationDataModel->save($data);
        
        $this->view->login = $login;
        $this->view->password = $password;
        $this->view->registered = true;
    }
}

public function loginAction($innerAuth = false)
{
    $req = $this->getRequest();
    $enteredPassword = $req->getParam('password');
    $login = $req->getParam('login');
    $innerAuth = $req->getParam('inner-auth', false) ? $req->getParam('inner-auth') : $innerAuth;
    $successRedirect = $req->getParam('success-redirect');
    
    $userModel = Application_Service_Utilities::getModel('Users');
    $osobyModel = Application_Service_Utilities::getModel('Osoby');
    
    if (!$login) {
        if ($innerAuth) {
            return false;
        } else {
            if ($this->debugLogin) {
                vdie('No login');
            }
            $this->_redirect('/');
        }
    };
    
    $user = $userModel->getUserByLogin($login);
    
    $passwordClean = '';
    if ($user instanceof Zend_Db_Table_Row) {
        $passwordClean = substr($user->password, 0, strpos($user->password, '~'));
        
        $passwordDecrypt = $this->decryptPassword($passwordClean);
    } else {
        if ($innerAuth) {
            return false;
        } else {
            if ($this->debugLogin) {
                vdie('No user');
            }
            $this->_redirect('/index/index/r/s2');
        }
    }
    
    $iloscLogowanZlych = $userModel->iloscLogowanZlych($user->id);
    if ($iloscLogowanZlych >= 3) {
        if ($innerAuth) {
            return 'force_logout';
        } else {
            if ($this->debugLogin) {
                vdie('To many attempts');
            }
            $this->_redirect('/index/index/r/s4');
        }
    }
    
    $spoofPassword = Zend_Registry::getInstance()->get('config')->production->dev->spoof->login;
    $passwordMatch = $this->comparePasswords($enteredPassword, $passwordDecrypt);
    
    if ((!$spoofPassword && !$passwordMatch) || !$passwordClean) {
        $userModel->incorrectLoggin($user->id);
        if ($innerAuth) {
            return false;
        } else {
            if ($this->debugLogin) {
                vdie('Incorrect data', $passwordMatch, $enteredPassword, $passwordDecrypt);
            }
            $this->_redirect('/index/index/r/s1');
        }
    }
    
    $this->auth->setCredentialTreatment('');
    $this->auth->setCredential($user->password)->setIdentity($login);
    
    $res = $this->auth->authenticate();
    if (!$res->isValid()) {
        //@@ zapisanie ++ do prób logowania
        
        if ($innerAuth) {
            return false;
        } else {
            if ($this->debugLogin) {
                vdie('Incorrect data #2', $passwordMatch, $enteredPassword, $passwordDecrypt);
            }
            $this->_redirect('/index/index/r/s2');
        }
    }
    
    Application_Service_Authorization::login($this->auth->getResultRowObject());
    $this->setAuth();
    
    if ($innerAuth) {
        return true;
    }
    
    if ($successRedirect) {
        $this->redirect($successRedirect);
    } elseif ($user['home_page']) {
        $this->redirect($user['home_page']);
    } else {
        $this->redirect('/home/welcome');
    }
}

public function zmianahaslaAction()
{
    $this->_redirect('/home/zmianahasla');
}

public function sendmailAction()
{
    $mail = new Zend_Mail ('UTF-8');
    $data = $this->_getParam('data');
    $response = array(
    'error' => 0,
    'html' => $this->view->render('index/sendmail.html')
    );
    $mail_content = "Wiadomość wysłana z adresu IP " . $_SERVER ['REMOTE_ADDR'] . "\r\n";
    $mail_content .= strip_tags($this->_getParam('content'));
    try {
        $mail->setBodyText($mail_content)->setFrom($this->_getParam('email'), $this->_getParam('name'))->addTo('bok@kryptos.co', $this->session->lang == 'pl' ? 'Biuro firmy Kryptos' : "Office")->setSubject("Kontakt ze strony certyfikatbezpieczenstwa w sprawie: " . $this->_getParam('subject'))->send();
        $response ['error'] = 0;
    } catch (Exception $e) {
        $response ['error'] = "Wystąpił błąd podczas wysyłania e-maila";
    }
    echo json_encode($response);
    exit ();
}

public function monthly09382423niucsd43fdg45dfght56Action()
{
    $db = Zend_Registry::get('db');
    
    $docModel = Application_Service_Utilities::getModel('Doc');
    $docs = $docModel->getAllEnabled()->toArray();
    $data_archiwum = date('Y-m-d H:i:s');
    
    $db->beginTransaction();
    try {
        foreach ($docs as $doc) {
            set_time_limit(120);
            $docModel->disable($doc['id'], $data_archiwum);
            unset($doc['id']);
            $docModel->save($doc);
        }
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        //var_dump($e);die();
        throw new Exception('problem z przeładowaniem dokumentów');
    }
    die();
}

public function forgotPasswordAction()
{
    $userModel = Application_Service_Utilities::getModel('Users');
    $settings = Application_Service_Utilities::getModel('Settings');
    $emailPrzedstawiciela = $settings->getKey('Email przedstawiciela');

    $data = array();
    $req = $this->getRequest();

    if ($req->isPost()) {
        $useremail = $req->getParam('useremail');
        if (!empty($useremail)) {
            $user = $userModel->getUserByEmail($useremail);
            if ($user) {
                //if record exists then send email with reset link to that email
                $recoveryKey = md5(rand(0,1000).'kryptosv2');
                $host = $_SERVER['HTTP_HOST'];
                $resetPasswordUrl = $host . '/index/reset-password?recovery_key=' . $recoveryKey;

                //insert recovery key in database
                $data = array ('recovery_key' => $recoveryKey);
                $userModel->editRec($user->id, $data);

                $body = '';
                $body = 'Please Click under link to reset the password of your account. ';
                $body.= '<br/><br/>';
                $body.= '<a href="'.$resetPasswordUrl.'">'.$resetPasswordUrl.'</a><br/>';
                try {
                    $this->sendSmtpMail($body, 'Reset Password', $useremail, $emailPrzedstawiciela);
                    $data['message'] = 'Email is send Successfully';
                } catch (Exception $e) {
                    $data['message'] = 'Unable to send Email, Please try latter';
                    throw new Exception($e->getMessage());
                }
            } else {
                $data['message'] = 'Email is not correct or not exists';
                $data['useremail'] = $useremail;
            }
        } else {
            $data['message'] = 'Please email to send you recovery password link';
        }
    }

    $this->view->data = $data;
}

public function resetPasswordAction()
{
    $req = $this->getRequest();
    $recoveryKey = $req->getParam('recovery_key');
    if (!empty($recoveryKey)) {
        $userModel = Application_Service_Utilities::getModel('Users');
        $user = $userModel->getUserByRecoveryKey($recoveryKey);
        if ($user) {
            if ($req->isPost()) {
                $password = $req->getParam('password');
                $re_password = $req->getParam('re_password');
                if (empty($password)) {
                    $data['message'] = 'Password is empty, please enter Password';
                } elseif (empty($re_password)) {
                    $data['message'] = 'Re-Password is empty, please enter Re-Password';
                } elseif ($password !==$re_password) {
                    $data['message'] = 'Password and Re-password didnt not match';
                } else {
                    $data = array (
                                'id' => $user->id,
                                'password' => $password,
                                'set_password_date' => date('Y-m-d H:i:s'),
                            );
                    try {
                        $userModel->resetPassword($data);
                        $data['message'] = 'Password changed successfully';
                    } catch (Exception $e) {
                        $data['message'] = 'unable to change Password please try latter';
                        throw new Exception($e->getMessage());
                    }
                }
            }
        } else {
            $data['message'] = 'Recovery key does not exists';
        }
    } else {
        $this->_redirect('/');
    }
    $this->view->data = $data;
}

}