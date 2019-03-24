<?php

class RegistryEntriesController extends Muzyka_Admin {

    /** @var Application_Model_Registry */
    protected $registryModel;

    /** @var Application_Model_RegistryEntries */
    protected $registryEntriesModel;

    /** @var Application_Service_Registry */
    protected $registryService;
    protected $baseUrl = '/registry-entries';

    public function init() {
        parent::init();

        $this->registryModel = Application_Service_Utilities::getModel('Registry');
        $this->registryEntriesModel = Application_Service_Utilities::getModel('RegistryEntries');
        $this->dictionaryModel = Application_Service_Utilities::getModel('Dictionary');
        $this->registryService = Application_Service_Registry::getInstance();
        $this->registryEntitiesModel = Application_Service_Utilities::getModel('RegistryEntities');
        $this->registryFilterModel = Application_Service_Utilities::getModel('RegistryFilter');
        $this->entitiesModel = Application_Service_Utilities::getModel('Entities');
        Zend_Layout::getMvcInstance()->assign('section', 'Kategorie szkoleń');
        $this->view->baseUrl = $this->baseUrl;
    }

    public static function getPermissionsSettings() {
        $readPermissionsResolverById = array(
            'function' => 'registryAccessById',
            'params' => array('id'),
            'manualParams' => array(2 => 'read'),
            'permissions' => array(
                -1 => ['perm/registry/all-access'],
                0 => ['perm/registry/all-access'],
                1 => ['user/anyone'],
                ),
            );
        $readPermissionsResolverByRegistryId = $readPermissionsResolverById;
        $readPermissionsResolverByRegistryId['params'] = array('registry_id', 'id');

        $writePermissionsResolverById = $readPermissionsResolverById;
        $writePermissionsResolverById['manualParams'][2] = 'write';
        $writePermissionsResolverByRegistryId = $writePermissionsResolverById;
        $writePermissionsResolverByRegistryId['params'] = array('registry_id', 'id');

        $adminPermissionsResolverById = $readPermissionsResolverById;
        $adminPermissionsResolverById['manualParams'][2] = 'admin';
        $adminPermissionsResolverByRegistryId = $adminPermissionsResolverById;
        $adminPermissionsResolverByRegistryId['params'] = array('registry_id');

        $permissionsResolverBase = array(
            'function' => 'registryAccessBase',
            'permissions' => array(
                0 => ['perm/registry/all-access'],
                1 => ['user/anyone'],
                ),
            );

        $settings = array(
            'nodes' => [
            'registry-entries' => [
            '_default' => [
            'permissions' => [],
            ],

            'index' => [
            'getPermissions' => [$readPermissionsResolverByRegistryId],
            ],
            'bulk-actions' => [
            'getPermissions' => [$readPermissionsResolverByRegistryId],
            ],
            'report' => [
            'getPermissions' => [$readPermissionsResolverByRegistryId],
            ],
            'ajax-update' => [
            'getPermissions' => [$permissionsResolverBase],
            ],
            'update' => [
            'getPermissions' => [$writePermissionsResolverByRegistryId],
            ],
            'save' => [
            'getPermissions' => [$writePermissionsResolverByRegistryId],
            ],
            'remove' => [
            'getPermissions' => [$writePermissionsResolverByRegistryId],
            ],

            ],
            ]
            );

        return $settings;
    }

    public function datagridModalAction()
    {
        $this->view->ajaxModal = 1;
        $registryId = $this->getParam('id', 0);
        if($registryId){
            $registry = $this->registryModel->getOne(['id = ?' => $registryId]);
        }
        $registries = $this->registryModel->getList();
        $registry->loadData('entities');         

        $paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId]);
        $this->registryEntriesModel->loadData('author', $paginator);
        Application_Service_Registry::getInstance()->entriesGetEntities($paginator);
        
        $this->view->paginator = $paginator;
        $this->view->registry = $registry;
        $this->view->registries = $registries;
        $this->view->id = $this->getParam('id', 0);
        $this->view->registryId = $registryId;

    } 


    public function indexAction() {
        $registryId = $this->getParam('registry_id', 0);

        if (!$registryId) {
            $this->redirect('/registry');
        }

        $registry = $this->registryModel->getOne($registryId, true);

        $registry->loadData('entities');

        $filterId = $this->getParam('filter_id', 0);
        if(isset($filterId) && $filterId > 0)
        {
            $conditions = array();
            $filter_arr = $this->registryFilterModel->getOne($filterId);
            $conditions_arr = unserialize($filter_arr['meta_content']);
			
			
            if(isset($conditions_arr) && sizeof($conditions_arr) > 0)
            {
                $condition_stmt = array();
                foreach($conditions_arr as $condition_detail)
                {
                    if(isset($condition_detail['parameter_id']) && $condition_detail['parameter_id'] > 0 )
                    {
                        $keyword = $condition_detail['keyword'];
                        $condition = trim($condition_detail['condition']);
                        $entityId = trim($condition_detail['entity_id']);
                        switch ($condition) {
                            case 'equal':
                                $str = " `value` = ? ";
                                break;
                            case 'not-equal':
                                $str = " `value` != ? ";
                                break;
                            case 'empty':
                                $str = " `value` = ? ";
                                $keyword = "''";
                                break;
                            case 'not-equal':
                                $str = " `value` != ? ";
                                break;
                            case 'like':
                                $str = " `value` LIKE ? ";
                                $keyword = "%".$keyword."%";
                                break;
                            case 'not-like':
                                $str = " `value` NOT LIKE ? ";
                                $keyword = "%".$keyword."%";
                                break;
                            case 'start_with':
                                $str = " `value` LIKE ? ";
                                $keyword = $keyword."%";
                                break;
                            case 'not_start_with':
                                $str = " `value` NOT LIKE ? ";
                                $keyword = $keyword."%";
                                break;
                            case 'end_with':
                                $str = " `value` LIKE ? ";
                                $keyword = "%".$keyword;
                                break;
                            case 'not_end_with':
                                $str = " `value` NOT LIKE ? ";
                                $keyword = "%".$keyword;
                                break;
                        }
                        if($entityId == 1)
                        {
                            if($str == '')
                                $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_varchar where registry_entity_id = '".$condition_detail['parameter_id']."')"] = trim($keyword);
                            else
                                $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_varchar where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$str." )"] = trim($keyword);
                        }else if($entityId == 2)
                        {
                            if($str == '')
                                $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_text where registry_entity_id = '".$condition_detail['parameter_id']."'"] = trim($keyword);
                            else
                                $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_text where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$str." )"] = trim($keyword);
                        }else if($entityId == 4 )
                        {
							$start_str = " `value` >= ? ";	
							$end_str = " `value` <= ? ";	
                            $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_date where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$start_str." )"] = trim($keyword['from']);
							$condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_date where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$end_str." )"] = trim($keyword['to']);
                        }else if($entityId == 5 )
                        {
							$start_str = " `value` >= ? ";	
							$end_str = " `value` <= ? ";	
                            $condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_datetime where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$start_str." )"] = trim($keyword['from']);
							$condition_stmt["re.id IN (SELECT entry_id from registry_entries_entities_datetime where registry_entity_id = '".$condition_detail['parameter_id']."' AND ".$end_str." )"] = trim($keyword['to']);
                        }else if($entityId == 6)
						{
							$str = str_replace("`value`","CONCAT(UPPER(o.nazwisko),' ',UPPER(o.imie))",$str);
							$condition_stmt["re.id IN (SELECT entry_id FROM registry_entries_entities_int as ree,registry_entities as re,osoby as o WHERE re.id=ree.registry_entity_id AND o.id=ree.value AND re.entity_id='".$entityId."' AND ".$str." )"]=  trim($keyword);
						}
                    }
                }
                $condition_stmt['registry_id = ?'] = $registryId;
				//echo "<pre>";print_r($condition_stmt);echo "</pre>";
                $paginator = $this->registryEntriesModel->getList($condition_stmt);
            }

        }else{
            $paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId ]);
        }

        $this->registryEntriesModel->loadData('author', $paginator);
        Application_Service_Registry::getInstance()->entriesGetEntities($paginator);
        $filters = $this->registryFilterModel->fetchAll();
        $this->view->filters = $filters;
        $this->view->paginator = $paginator;
        $this->view->registry = $registry;
        $this->setDetailedSection($registry->title . ': lista wpisów');
    }
	
    public function bulkActionsAction() {
        $registryId = $this->getParam('registry_id', 0);

        if (!$registryId) {
            $this->redirect('/registry');
        }

        $registry = $this->registryModel->getOne($registryId, true);

        $rowAction = $_POST['rowsAction'];
        $rowSelect = $this->_getParam('id');
        $rowSelect = array_keys(Application_Service_Utilities::removeEmptyValues($rowSelect));

        switch ($rowAction) {
            case "delete":
            foreach ($rowSelect as $id) {
                $this->registryEntriesModel->remove($id);
            }
            break;
        }

        $this->redirectBack();
    }

    public function documentsAction() {
        $registryId = $this->getParam('registry_id', 0);
        $entryId = $this->getParam('id', 0);

        if (!$registryId) {
            $this->redirect('/registry');
        }

        $registry = $this->registryModel->getFull($registryId, true);
        $entry = $this->registryEntriesModel->getFull([
            'id' => $entryId,
            'registry_id' => $registryId,
            ], true);

        $paginator = Application_Service_Utilities::getModel('RegistryEntriesDocuments')->getListFull(['entry_id = ?' => $entryId]);

        $this->view->paginator = $paginator;
        $this->view->registry = $registry;
        $this->view->entry = $entry;
        $this->setDetailedSection($registry->title . ': lista dokumentów');
    }

    public function downloadDocumentAction() {
        $registryId = $this->getParam('registry_id', 0);
        $entryId = $this->getParam('entry_id', 0);
        $documentId = $this->getParam('id', 0);

        $document = Application_Service_Utilities::getModel('RegistryEntriesDocuments')->getOne([
            'entry_id' => $entryId,
            'id' => $documentId,
            ], true);

        $this->_helper->layout->setLayout('report');
        $layout = $this->_helper->layout->getLayoutInstance();
        $layout->assign('content', $document->data);
        $htmlResult = $layout->render();

        $filename = 'dokument_' . $this->getTimestampedDate() . '.pdf';

        $this->outputHtmlPdf($filename, $htmlResult);
    }

    public function previewDocumentAction() {
        $registryId = $this->getParam('registry_id', 0);
        $entryId = $this->getParam('entry_id', 0);
        $documentId = $this->getParam('id', 0);

        $document = Application_Service_Utilities::getModel('RegistryEntriesDocuments')->getOne([
            'entry_id' => $entryId,
            'id' => $documentId,
            ], true);

        $this->setTemplate('/home/preview-document', null, true);
        $this->view->ajaxModal = 1;
        $this->view->documentContent = $document->data;
    }

    public function updateDocumentAction() {
        $registryId = $this->getParam('registry_id', 0);
        $entryId = $this->getParam('entry_id', 0);
        $documentId = $this->getParam('id', 0);

        $document = Application_Service_Utilities::getModel('RegistryEntriesDocuments')->getOne([
            'entry_id' => $entryId,
            'id' => $documentId,
            ], true);

        $this->setTemplate('/home/preview-document', null, true);
        $this->view->ajaxModal = 1;
        $this->view->documentContent = $document->data;
    }

    public function reportAction() {
        $this->_helper->layout->setLayout('report');
        $registryId = $this->getParam('registry_id', 0);

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
        $this->view->title = $registry->title;
        $this->view->date = date('Y-m-d');
        
        $settings = Application_Service_Utilities::getModel('Settings');
        $this->view->name = $settings->get(1)['value'];

//        vdie($registry);

        $layout = $this->_helper->layout->getLayoutInstance();
        
        $layout->assign('content', $this->view->render('registry-entries/report.html'));
        $htmlResult = $layout->render();
        
        $date = new DateTime();
        $time = $date->format('\TH\Hi\M');
        $timeDate = new DateTime();
        $timeDate->setTimestamp(0);
        $timeInterval = new DateInterval('P0Y0D' . $time);
        $timeDate->add($timeInterval);
        $timeTimestamp = $timeDate->format('U');
        $filename = 'raport_rejestry_' . date('Y-m-d') . '_' . $timeTimestamp . '.pdf';

        $htmlResult = html_entity_decode($htmlResult);
        $this->outputHtmlPdf($filename, $htmlResult, true, true);
    }

    public function ajaxUpdateAction() {
        $this->setDialogAction();
        $this->updateAction();
        $this->view->dialogTitle = 'Dodaj wpis';
    }
    
    //ftforest640@gmail.com
    public function ajaxPopUpWindowAction() {

        //$req = $this->getRequest();
        //$data = $req->getParam('document');
        $this->setDialogAction();
        $this->setTemplate('ajax-pop-up-window');

        $id = $this->getParam('id', 0);
        $registryId = $this->getParam('registry_id', 0);
        $registry = $this->registryModel->getOne($registryId, true);
        if ($id) {
            $row = $this->registryEntriesModel->getFull([
                'id' => $id,
                'registry_id' => $registryId,
            ], true);
            Application_Service_Registry::getInstance()->entryGetEntities($row);


                $sectionName = 'edytuj wpis';

        } elseif ($registryId) {


            $row = $this->registryEntriesModel->createRow([
                'registry_id' => $registryId,
            ]);

            $row->loadData(['registry']);

            $sectionName = 'dodaj wpis';
        } else {
            throw new Exception('404', 404);
        }
        foreach ($row->registry->entities as $val) {
            $character = json_decode($val->config);
            $steps[] =  $character->tab;
        }

        $this->view->data = $row;

        //========================Autor
        $registryId = $this->getParam('registry_id', 0);

        if (!$registryId) {
            $this->redirect('/registry');
        }

        $registry = $this->registryModel->getOne($registryId, true);

        $registry->loadData('entities');

        $paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId]);
        $this->registryEntriesModel->loadData('author', $paginator);
        Application_Service_Registry::getInstance()->entriesGetEntities($paginator);
        /*        echo "<pre>";
                print_r($paginator);*/

        //$this->view->paginator = $paginator;

        foreach($paginator as $d){
            if($id==$d['id']){
                $this->view->autor = $d['author']['display_name'];
            }
        }

    }
    
    public function updateAction() {

        $id = $this->getParam('clone', 0);
        $cloneMode = true;
        if (!$id) {
            $id = $this->getParam('id', 0);
            $cloneMode = false;
        }

        $registryId = $this->getParam('registry_id', 0);
        $registry = $this->registryModel->getOne($registryId, true);
        if ($id) {
            $row = $this->registryEntriesModel->getFull([
                'id' => $id,
                'registry_id' => $registryId,
                ], true);
            Application_Service_Registry::getInstance()->entryGetEntities($row);

            if ($cloneMode) {
                $sectionName = 'dodaj wpis';
                $row->id = null;
            } else {
                $sectionName = 'edytuj wpis';
            }
        } elseif ($registryId) {
            

            $row = $this->registryEntriesModel->createRow([
                'registry_id' => $registryId,
                ]);

            $row->loadData(['registry']);
            //$arr = (array) $row;
           
        /*  print_r($row->registry->entities_named); 
             die;*/
           /* foreach ($row->registry->entities_named as $key) {
             if($key->config_data->tab === 0)
             {
                 echo $key->config_data->tab;
    
             }
            }*/

           
          


            $sectionName = 'dodaj wpis';
        } else {
            throw new Exception('404', 404);
        }
        $tab_names = array();
        foreach ($row->registry->entities as $val) {
            $character = json_decode($val->config);
            $steps[] =  $character->tab;
            if(isset($character->tab_name))
            {
                $tab_names[$character->tab] = $character->tab_name;
            }
        }
            $maxstepval = max($steps);
            if(isset($maxstepval)){
               $maxstep=$maxstepval; 
            }else{
               $maxstep=0;
            }
           
            $this->view->maxstep = $maxstep;
            $this->view->tab_names = $tab_names;
        $this->view->data = $row;
     //  echo "<pre>"; print_r($row);exit();
        $this->setDetailedSection($registry->title . ': ' . $sectionName);
    }

    public function saveAction() {
		
        $id = $this->getParam('id', 0);
        $registryId = $this->getParam('registry_id', 0);

        $primarykeyrow = Application_Service_Utilities::getModel('RegistryEntities')->checkPrimaryKeyField($registryId);
        $elementid = $primarykeyrow['id'];

        $valueofpk = $this->db->query("SELECT `value` FROM `registry_entries_entities_varchar` WHERE `registry_entity_id` in (select `id` from `registry_entities` where `system_name`='".$primarykeyrow['system_name']."')")->fetchAll();

        foreach ($valueofpk as $val) {
            if($_POST['element_'.$elementid] == $val['value'])
            {
             $idarr = $this->db->query("SELECT `entry_id` FROM `registry_entries_entities_varchar` WHERE `registry_entity_id` = '".$elementid."' AND `value` = '".$_POST['element_'.$elementid]."' ")->fetchAll();
             $id = $idarr[0]['entry_id'];
         }
     }
   //  echo $id;

     if ($id) {
        $row = $this->registryEntriesModel->getFull([
            'id' => $id,
            'registry_id' => $registryId,
            ], true);
    } else {
        $row = $this->registryEntriesModel->createRow(array_merge($this->getRequest()->getParams(), [
            'registry_id' => $registryId,
            'author_id' => Application_Service_Authorization::getInstance()->getUserId(),
            ]));
    }

    try {
        $this->db->beginTransaction();

        $data = $this->getRequest()->getParams();
		
        $this->registryService->entrySave($row, $data);

        if ($data['update_documents']) {
            $this->registryService->entryUpdateDocuments($row->id);
        }

        if (!$id) {
            foreach ($row->registry->documents_templates as $documentsTemplate) {
                if ($documentsTemplate->flag_auto_create) {
                    $this->registryService->entryCreateDocument($row->id, $documentsTemplate->id);
                }
            }
        }

        $regid = $this->dictionaryModel->getRegidByName('dictionary');
        if ($registryId === $regid) {
             $this->dictionaryModel->setDictionary();
        }
        
        $this->db->commit();
    } catch(Application_SubscriptionOverLimitException $x){
        $this->_redirect('subscription/limit');
    } catch (Exception $e) {
        throw new Exception('Próba zapisu danych nie powiodła się.' . $e->getMessage(), 500, $e);
    }

    if ($this->_request->isXmlHttpRequest()) {
        $this->outputJson([
            'status' => (int) 1,
            ]);
    } else {
        $this->flashMessage('success', 'Dodano wpis');
        if ($this->getParam('addAnother')) {
            $this->redirect($this->baseUrl . '/update/registry_id/' . $registryId);
        } else {
            $this->redirect($this->baseUrl . '/index/registry_id/' . $registryId);
        }
    }
}

    public function removeAction() {
        try {
            $req = $this->getRequest();
            $id = $req->getParam('id', 0);

            $row = $this->registryEntriesModel->requestObject($id);
            $this->registryEntriesModel->remove($row->id);
        } catch (Exception $e) {
            throw new Exception('Operacja nieudana', $e->getCode(), $e);
        }

        if ($this->_request->isXmlHttpRequest()) {
            $this->outputJson([
                'status' => (int) 1,
                ]);
        } else {
            $this->redirectBack();
        }
    }

    public function ajaxCreateDocumentAction() {
        $this->setDialogAction();
        $id = $this->getParam('id', 0);
        $registryId = $this->getParam('registry_id', 0);
        $registry = $this->registryModel->getFull($registryId, true);

        $row = $this->registryEntriesModel->getFull([
            'id' => $id,
            'registry_id' => $registryId,
            ], true);
        $this->registryService->entryGetEntities($row);

        $this->view->entry = $row;
        $this->view->registry = $registry;
        $this->view->dialogTitle = 'Utwórz dokument';
    }

     public function bulkeditAction(){

        $time = date("Y-m-d H:i:s");
        $id = $_POST['id'];
        $entry_ids = $_POST['entry_ids'];
        $data = $_POST['data'];
        $entity_id = $_POST['entity_id'];
        $entry_ids = explode(",", $entry_ids);
 
        if ($entity_id == "4") $tablename = "registry_entries_entities_date";
        else $tablename = "registry_entries_entities_varchar";
 
        foreach ($entry_ids as $entry_id) {
            $query = 'SELECT id FROM '.$tablename.'
            WHERE entry_id = '.$entry_id.' AND
            registry_entity_id = '.$id; 
            $num = $this->db->query($query)->num_rows;
        
            // if(empty($num) || $num == 0){
            //     $query = "INSERT INTO ".$tablename."   
            //     (registry_entity_id, entry_id, value , created_at) VALUES 
            //     (".$id.",".$entry_id.","."'".$data."', '".$time."')";
            //     $this->db->query($query);
            //     return;
            // }
 
            $query = 'UPDATE '.$tablename.'  
                SET value = '."'".$data."' ".'
                WHERE entry_id = '.$entry_id.' AND 
                registry_entity_id = '.$id;
            $this->db->query($query);
        }

        $response['status'] = "success";
        return $response;
    }
    
    public function ajaxSaveCreateDocumentAction() {
        try {
            $req = $this->getRequest();
            $data = $req->getParam('document');
            $this->db->beginTransaction();

            $this->registryService->entryCreateDocument($data['entry_id'], $data['document_template_id']);

            $this->db->commit();
        } catch (Exception $e) {
            throw new Exception('Operacja nieudana', $e->getCode(), $e);
        }

        $this->flashMessage('success', 'Utworzono dokument');

        $this->outputJson([
            'status' => (int) 1,
            'app' => [
            'reload' => 1,
            ],
            ]);
    }


    public function ajaxSearchFilterAction()
    {
        try {
            $req = $this->getRequest();
            $registryId = $_POST['registry_id'];
            $count = $_POST['count'];
            $parameterEntityId = $_POST['parameter-entity-id'];
            list($parameterId,$entityId)  = explode("-",$parameterEntityId);
            $entity_details = $this->entitiesModel->getOne($entityId);
            if(isset($entity_details['conditions']) && !empty($entity_details['conditions']))
            {
                //$optionStr = '<div class="col-sm-4"><select name="condition_for_'.$count.'" id="condition_for_'.$count.'" class="form-control">';
                $optionStr = '<select name="condition_for[]" id="condition_for_'.$count.'" class="form-control">';
                $lists = unserialize($entity_details['conditions']);
                if(count($lists) > 0 && !empty($lists))
                {
                    foreach($lists as $k => $v)
                    {
                        $optionStr .= "<option value='".$k."'>".$v."</option>";
                    }
                }
                $optionStr .= "</select>";
                echo $optionStr; exit();
            }
        }catch (Exception $e) {
            throw new Exception('Operacja nieudana', $e->getCode(), $e);
        }

        exit();
    }

 public function ajaxSaveFilterAction()
    {
        $filterCondition = array();
        if(isset($_POST['regtype']) && sizeof($_POST['regtype']) > 0 )
        {
            $con = 0;
            $da = 0;
            foreach($_POST['regtype'] as $k => $parameterEntityId )
            {
                list($parameterId,$entityId) = explode('-',$parameterEntityId);
                $filterCondition[$k]['parameter_id'] = $parameterId;
                $filterCondition[$k]['entity_id'] = $entityId;
                $conditionFieldName = 'condition_for';
                
                $expEntity = explode("-",$parameterEntityId);
                if($expEntity[1]==4){
                    $filterCondition[$k]['keyword'] = array("from"=>$_POST['from'][$da],"to"=>$_POST['to'][$da]);
                    $da++;
                }
                else{
                    //$_POST[$conditionFieldName][] = 'equal';
                    if(isset($_POST[$conditionFieldName][$con]))
                    {
                        $filterCondition[$k]['condition'] = $_POST[$conditionFieldName][$con];
                    }

                    $keyword = 'keyword';
                    if(isset($_POST[$keyword][$con]))
                    {
                        $filterCondition[$k]['keyword'] = $_POST[$keyword][$con];
                    }
                    $con++;
                }
            }
            //echo serialize($filterCondition);
            $userId = $this->userSession->user->id;
            $regId = $_POST['reg_id'];
            $filter_name = trim($_POST["filter_name"]);
            $filter_scope = trim($_POST["filter_scope"]);
            
            if(isset($_POST['hidFilterId']) && $_POST['hidFilterId']!=""){
                $updateSql = 'UPDATE registry_filters SET meta_content = '."'".serialize($filterCondition)."' ".'WHERE id='.$_POST['hidFilterId'];
                $this->db->query($updateSql);
            } else {
                $insertSql = 'INSERT INTO registry_filters SET
                user_id = '.$userId. ',
                meta_content = '."'".serialize($filterCondition)."'". ' ,
                filter_name = '."'".$filter_name."'".',
                filter_score = '."'".$filter_scope."'".',
                created_at = '."'".date('Y-m-d :h:i:s')."'" ;
                $this->db->query($insertSql);
            }
        }
        exit();
    }
    public function getTopNavigation()
    {

        $data = array(
            array(
                'label' => 'Raporty',
                'path' => 'javascript:;',
                'icon' => 'fa icon-print-2',
                'rel' => 'reports',
                'children' => array(
                    array(
                        'label' => 'Raport pracowników',
                        'path' => '/osoby/profilespdf',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Tabela pracowników',
                        'path' => '/osoby/employees-report',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Wykaz kluczy',
                        'path' => '/reports/wykazkluczy',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Osoby upoważnione do przetwarzania danych',
                        'path' => '/reports/upowaznienie-przetwarzanie',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Osoby zapoznane z polityką bezpieczeństwa',
                        'path' => '/reports/wykazosobzapzpolbezpieczenstwa',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                )
            ),
            array(
                'label' => 'Operacje',
                'path' => 'javascript:;',
                'icon' => 'fa icon-tools',
                'rel' => 'operations',
                'children' => array(
                    array(
                        'label' => 'Import',
                        'path' => '/osoby/import',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Transfer upoważnień',
                        'path' => '/osoby/upowaznienia-transfer',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Modyfikacja uprawnień',
                        'path' => '/osoby/permissions-setter',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                    array(
                        'label' => 'Usunięcie wszystkich użytkowników',
                        'path' => '/osoby/remove-all-users/id/1',
                        'icon' => 'icon-align-justify',
                        'rel' => 'admin',
                    ),
                )
            ),

            array(
                'label' => 'Filters',
                'path' => 'javascript:;',
                'icon' => 'fa icon-tools',
                'rel' => 'filters',
                'children' => array(

                )
            ),
        );
        $userId = $this->userSession->user->id;
        $registryId = $this->getParam('registry_id', 0);

        $resultSets = $this->db->query("select * from registry_filters where user_id = '".$userId." ' ")->fetchAll();
        if(isset($resultSets) && count($resultSets) > 0 )
        {
            foreach($resultSets as $result)
            {
                $filterDialog = "#filterDialog_".$result['id'];
                $filter_arr[] =
                    array(
                        'label' => $result['filter_name'],
                        'path' => 'javascript:;',
                        'isdialog' => 1 ,
                        'target' => $filterDialog ,
                        'icon' => (isset($result['filter_score']) && $result['filter_score'] == 'private') ? 'icon-lock-1' : 'icon-lock-open-1',
                        'rel' => 'filters',
                        'isdelete' => 1
                    );
            }
            $filter_arr[] =
                array(
                    'label' => 'Reset filter',
                    'path' => '/registry-entries/index/registry_id/'.$registryId,
                    'icon' => 'icon-arrows-ccw',
                    'rel' => 'filters'
                );
        }
        $filter_arr[] =
            array(
                'label' => 'Add new filter',
                'path' => 'javascript:;',
                'isdialog' => 1 ,
                'target' => '#filterDialog' ,
                'icon' => 'icon-plus',
                'rel' => 'filters'
            );

        if(isset($filter_arr) && count($filter_arr) > 0)
        {
            $data[2]['children'] = $filter_arr;
        }

        $this->setSectionNavigation($data);
    }

    public function ajaxDeleteFilterAction()
    {
        if(isset($_POST['filter_id']))
        {
            $filter_id = trim($_POST["filter_id"]);
            $sql = 'DELETE FROM registry_filters WHERE
            id = '.$_POST['filter_id'];
            $this->db->query($sql);
        }
        exit();
    }
    
    public function diagramdAction(){
		try {
			$data = $this->_request->getPost();
			$id = $data['id'];
			$select =  $this->db->select()
			->from( 'event_diagram' )
			->where('id = ?', $id);
			$result = $this->db->fetchAll($select);
			if(!empty($result))
			{
				foreach ($result as $value) {
					echo $value['diagramj'];
					exit;
	                // $this->view->loaddiagram11 = '{"Hello Ali"}';
				}

			}
			else{
				echo "Ali";
				exit;
			}
		} catch (Exception $e) {
			var_dump($e);
			exit;
			Throw new Exception('Operacja nieudana', $e->getCode(), $e);
		}
	}
	
    public function diagramrAction(){
		try {
			$data = $this->_request->getPost();
			$dn= $data['dn'];
			$str = $data['str1'];
			$loadid = $data['loadid'];
			$select =  $this->db->select()
			->from( 'event_diagram' )
			->where('id = ?', $loadid);
			$result = $this->db->fetchAll($select);
			if(!empty($result))
			{
				$data = array('diagramj' => $str, 'name' => $dn);

				$where[] = "id = ". $loadid;

				$this->db->update('event_diagram', $data, $where);
				echo "Updated Succeccfully";
				exit;
			}
			else{
				$req = $this->getRequest();
				$data = $req->getParam('value');
				$data = array( 'name' => $dn,
					'diagramj' => $str);
				$this->db->insert('event_diagram', $data);
				echo "Save Succeccfully";
				exit;
			}
				
		} catch (Exception $e) {
			var_dump($e);
			exit;
			Throw new Exception('Operacja nieudana', $e->getCode(), $e);
		}
	}
	
	public function diagramAction() {
		$id = $this->getParam('id', 0);
		$registryId = $this->getParam('registry_id', 0);
		$this->view->id = $id;
		$this->view->registryId11 = $registryId;

		$select =  $this->db->select()
		->from( 'registry_event_diagram' )
		->where('rid = ?', $registryId)->where('eid = ?',$id);
		$result = $this->db->fetchAll($select);

		if(!empty($result))
		{
			foreach ($result as $value) {
				$this->view->loaddiagram11 = json_encode($value['diagramj']);
                // $this->view->loaddiagram11 = '{"Hello Ali"}';
			}

		}
		else{
			$this->view->loaddiagram11 = "";
		}

	}
	
	public function diagramblockAction() {
		$id = $this->getParam('id', 0);
		$registryId = $this->getParam('registry_id', 0);
		$this->view->id = $id;
		$this->view->registryId11 = $registryId;

		$registry = $this->registryModel->getOne($registryId, true);

		$registry->loadData('entities');

		$paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId]);
		$this->registryEntriesModel->loadData('author', $paginator);
		Application_Service_Registry::getInstance()->entriesGetEntities($paginator);

		$this->view->paginator = $paginator;
		$select =  $this->db->select()
		->from( 'registry_event_diagram' )
		->where('rid = ?', $registryId)->where('eid = ?',$id);
		$result = $this->db->fetchAll($select);

		$this->view->registry = $registry;

		if(!empty($result))
		{
			foreach ($result as $value) {
				$this->view->loaddiagram11 = json_encode($value['diagramj']);
                // $this->view->loaddiagram11 = '{"Hello Ali"}';
			}

		}
		else{
			$this->view->loaddiagram11 = "";
		}

	}
	
	public function getentitiesAction(){
		$registryId = $_POST['rid'];
 		$result = array();
		if (!$registryId) {
			$this->redirect('/registry');
		}

		$registry = $this->registryModel->getOne($registryId, true);

		$registry->loadData('entities');

		$paginator = $this->registryEntriesModel->getList(['registry_id = ?' => $registryId]);
		$this->registryEntriesModel->loadData('author', $paginator);
		Application_Service_Registry::getInstance()->entriesGetEntities($paginator);

		$i=0;
		foreach ($paginator as $d){
			foreach ($registry['entities'] as $entity)
			{
				$result[$i] = $d->entityToString($entity['id']);
				//$result[$i]=array('eventid'=>$d['id'],'eventname'=>$d->entityToString($entity['id']),'eventtype'=>$d->entityToString($entity['id']));
				break;
			}
			$i++;
		}
		 
		echo $res=json_encode($result);
		 
		die();
		 
	}

}
