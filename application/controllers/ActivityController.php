<?php  

class ActivityController extends Muzyka_Admin
{

      protected $activityModel;
      protected $activityLogModel;
      protected $userloggedin;
      protected $registryService;
      protected $entitiesModel;
    /** @var Application_Model_Registry */
    protected $registryModel;

    /** @var Application_Model_RegistryEntries */
    protected $registryEntriesModel;

    public function init()
    {
       parent::init();

        $this->registryModel = Application_Service_Utilities::getModel('Registry');
        $this->registryEntriesModel = Application_Service_Utilities::getModel('RegistryEntries');
       $this->activityModel = Application_Service_Utilities::getModel('Activity');
       $this->activityLogModel = Application_Service_Utilities::getModel('ActivityLog');
       $this->userloggedin = new Zend_Session_Namespace('user');
       $this->registryService = Application_Service_Registry::getInstance();
       $this->entitiesModel = Application_Service_Utilities::getModel('Entities');
    }


    public function indexAction()
    {
        $this->setDetailedSection('Lista rejestrów');

        $paginator = $this->activityModel->getList(["`status` = ?" => 0]);

        $this->view->paginator = $paginator;
        $this->view->curr_date = date_create(); // Current time and date

    }

    public function ajaxAddActivityAction()
    {
        $this->setDialogAction();
        $activity = $this->activityModel->getActivity("Types of Activity");

        $this->view->dialogTitle = "Schedule an activity";
        $this->view->activityTypes = $activity;
        $this->view->userlogged = $this->userloggedin->user->login;
    }

    public function ajaxEditActivityAction()
    {
        $req = $this->getRequest();
        $activityId = $req->getParam('id');
        $this->setDialogAction();

        $row = $this->activityModel->getFull([
          'id' => $activityId
        ], true);
        $activity = $this->activityModel->getActivity("Types of Activity");
      
        $this->view->data = $row;
        $this->view->userlogged = $this->userloggedin->user->login;
        $this->view->dialogTitle = "Edit activity ";
        $this->view->activityId =  $activityId;
        $this->view->activityTypes = $activity;
    }
    
    public function saveActivityAction()
    {
         $data = $this->getRequest()->getPost();
         $id = $this->activityModel->save($data['parameter']);
         $this->flashMessage('success', 'Aktywność zapisana');
         $this->redirect('activity/index');
    }

    public function updateAction()
    {
      $data = $this->getRequest()->getPost();
      $this->activityModel->update($data);
     // file_put_contents(ROOT_PATH . '/translations/dump.ini', json_encode($data));
    }

    public function getosobyAction()
    {
        header("content-type:application/json");
        echo json_encode($this->activityModel->getOsobyData());
        die();
    }

    public function getactlistAction()
    {
        $registryname = $this->getParam('name');
        header("content-type:application/json");
        echo json_encode($this->activityModel->getActlistData($registryname));
        die();
    }

    public function ajaxSaveParamAction()
    {
        $data = $this->getRequest()->getPost();
          
        $result = $this->activityLogModel->save($data);

         $this->flashMessage('success', 'Aktywność zapisana');
         $this->redirect('activity/index');
    }

    public function ajaxAddParamAction()
    {
        $registryId = $this->getParam('regid');
        $actId = $this->getParam('actid');
        $this->setDialogAction();
        $this->setTemplate('ajax-param-form');
        //$registryId = 32;

        if (!$registryId) {
            $this->redirect('/registry');
        }

        $registry = $this->registryModel->getOne($registryId, true);

        $registry->loadData('entities');

        $paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId]);
        $this->registryEntriesModel->loadData('author', $paginator);
        Application_Service_Registry::getInstance()->entriesGetEntities($paginator);

        $this->view->paginator = $paginator;
        $this->view->registry = $registry;

        $this->view->actid = $actId;
        $this->view->dialogTitle = 'Add Registry to Activity';
        $this->view->entities = $this->entitiesModel->getAllForTypeahead();
    }

    public function getRegistryValueAction()
    {
        $req = $this->getRequest();
        $regId = $req->getParam('regid');
       // die($regId);
        header("content-type:application/json");
        echo json_encode($this->activityModel->getRegistryEntitiesById($regId, 'Name'));
        die();
    }

    public function actlogAction()
    {
        $activityLog = $this->activityLogModel->getList();
        $this->view->paginator = $activityLog;
    }

}