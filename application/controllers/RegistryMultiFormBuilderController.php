<?php

class RegistryMultiFormBuilderController extends Muzyka_Admin
{
    protected $registryModel;

    public function init()
    {
        parent::init();


        $this->_helper->_layout->setLayout('multiformbuilder');
        $this->registryModel = Application_Service_Utilities::getModel('Registry');

    }

    public function indexAction()
    {

        $id = $this->getParam('id');
        $registry = $this->registryModel->getFull($id, true);
        $entities = array();
        $tab_names = array();

        foreach ($registry->entities as $key => $entity) {
          $tab = 0;
          if($entity->config != "" && !empty(json_decode($entity->config)))
          {
            $config = json_decode($entity->config);
            if(isset($config->tab))
            {
              $tab = $config->tab;
            }
          }
          $tab_name = 'Step '.($tab + 1);
          if($entity->config != "" && !empty(json_decode($entity->config)))
          {
            $config = json_decode($entity->config);
            if(isset($config->tab_name))
            {
              $tab_name = $config->tab_name;
            }
          }
          if(isset($tab_names[$tab]) && !empty($tab_names[$tab]))
          {
            if($tab_names[$tab] != $tab_name && $tab_name != 'Step '.($tab + 1))
            {
              $tab_names[$tab] = $tab_name;
            }
          }
          else
          {
            $tab_names[$tab] = $tab_name;
          }
          switch ($entity->entity->system_name) {
            case 'varchar':
                $field = array('id' => $entity->id, 'type' => 'text', 'label' => $entity->title, 'value' => $entity->default_value,
                'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$field['column']][] = $field;
              break;
            case 'employees':
                $field = array('id' => $entity->id, 'type' => 'employees', 'label' => $entity->title, 'value' => $entity->default_value,
                'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'documents':
                $field = array('id' => $entity->id, 'type' => 'documents', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'date':
                $field = array('id' => $entity->id, 'type' => 'date1', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'datetime':
                $field = array('id' => $entity->id, 'type' => 'datetime', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'zbiory':
                $field = array('id' => $entity->id, 'type' => 'zbiory', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'surveys':
                $field = array('id' => $entity->id, 'type' => 'surveys', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'consent':
                $field = array('id' => $entity->id, 'type' => 'consent', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'datagrid':
                $field = array('id' => $entity->id, 'type' => 'datagrid', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                  if(isset($multiform_data['registry']))
                  {
                    $field['registry'] = $multiform_data['registry'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'files':
                $field = array('id' => $entity->id,'type' => 'file', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['multiple']))
                  {
                    $field['multiple'] = $multiform_data['multiple'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'text-ckeditor':
                $field = array('id' => $entity->id, 'type' => 'textarea', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
            case 'registry-entries':
                $field = array('id' => $entity->id, 'type' => 'select', 'label' => $entity->title, 'value' => $entity->default_value, 'name' => $entity->name,
                'column' => $entity->column
                 , 'system_name' => $entity->system_name, 'setPrimary' => $entity->set_primary);
                if($entity->multiform_data != "" && !empty(json_decode($entity->multiform_data)))
                {
                  $multiform_data = (array)json_decode($entity->multiform_data);
                  if(isset($multiform_data['required']))
                  {
                    $field['required'] = $multiform_data['required'];
                  }
                  if(isset($multiform_data['description']))
                  {
                    $field['description'] = $multiform_data['description'];
                  }
                  if(isset($multiform_data['placeholder']))
                  {
                    $field['placeholder'] = $multiform_data['placeholder'];
                  }
                  if(isset($multiform_data['className']))
                  {
                    $field['className'] = $multiform_data['className'];
                  }
                  if(isset($multiform_data['access']))
                  {
                    $field['access'] = $multiform_data['access'];
                  }
                  if(isset($multiform_data['subtype']))
                  {
                    $field['subtype'] = $multiform_data['subtype'];
                  }
                  if(isset($multiform_data['maxlength']))
                  {
                    $field['maxlength'] = $multiform_data['maxlength'];
                  }
                  if(isset($multiform_data['role']))
                  {
                    $field['role'] = $multiform_data['role'];
                  }
                  if(isset($multiform_data['multiple']))
                  {
                    $field['multiple'] = $multiform_data['multiple'];
                  }
                  if(isset($multiform_data['labelScheme']))
                  {
                    $field['labelScheme'] = $multiform_data['labelScheme'];
                  }
                  if(isset($multiform_data['registry']))
                  {
                    $field['registry'] = $multiform_data['registry'];
                  }
                }
                $entities[$tab][$entity->column][] = $field;
              break;
          }
        }

        // var_dump($entities);

        $entities_tmp = array();

        foreach ($entities as $index => $tab) {
          $entities_tmp[$index] = json_encode(array_values($tab));
        }


        $registries = array(array('label' => 'Select', 'value' => ''));
        foreach ($this->registryModel->getAllForTypeahead() as $registry) {
          $registries[] = array('label' => htmlentities( (string)$registry['name'], ENT_QUOTES, 'utf-8', FALSE), 'value' => $registry['id']);
        }

        $this->view->entities = $entities_tmp;
        $this->view->registries_json = html_entity_decode(json_encode($registries));

        $this->view->tab_names = $tab_names;
        $this->view->id = $id;

    }

    public function saveAction()
    {

      $registryId = $this->getParam('id');
      $params = array();

      $param1 = $this->_request->getParam('data');
      $tab_names = $this->_request->getParam('names');

      $tab_names = array_map("strip_tags", $tab_names);

      /*
      var_dump($param1);
      $flipped_array =  array_flip($param1);
      $array_data = array_keys($flipped_array);
      $filtered_array =  array_filter($array_data);
      $tabs_arr = array_values($filtered_array);
      */
      $existing_ids = array(0);
      foreach($param1 as $tabkey => $tabval_str) {
        $tabval = json_decode($tabval_str, true);
        foreach($tabval as $column  => $value) {
          $keyval = $tabkey;
          /*
          $someJSON =  str_replace("<br />","",nl2br($value."\n"));
          $someArray = json_decode($someJSON, true);
          */
          $someArray = $value;
          foreach ($someArray as $key => $value) {
            $array = array();
            $array['title'] =  strip_tags($value["label"]);
            $array['is_multiple'] = ($value['multiple'])?1:0;
            $array['default_value'] = ($value['value'])?$value['value']:'';
            $array['set_primary'] = ($value['setPrimary'])?1:0;
            $array['registry_id'] = $registryId;
            $array['multiform_data'] = json_encode($value);
            $array['column'] = $column?$column:0;
            $array['name'] = $value['name'];

             if(isset($value['id']) && !empty($value['id']) && $value['id'] > 0)
             {
              $array['id'] = $value['id'];
              $existing_ids[] = $value['id'];
             }
             else
             {
              $array['id'] = '';
             }
            switch($value["type"])
            {
                case 'text': {
                  switch ($value["subtype"])
                  {
                      case 'text':
                            $array['entity_id'] = 1;
                            $array['config'] = array(
                                  "tab" => $keyval,
                                  "tab_name" => $tab_names[$keyval]
                                );
                        break;

                      case 'password':
                           $array['entity_id'] = 1;
                           $array['config'] = array(
                                  "tab" => $keyval,
                                  "tab_name" => $tab_names[$keyval]
                                );
                        break;
                    }
                    break;
                }
                case 'employees': {
                                $array['entity_id'] = 6;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'documents': {
                                $array['entity_id'] = 7;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'date1': {
                                $array['entity_id'] = 4;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'datetime': {
                                $array['entity_id'] = 5;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'zbiory': {
                                $array['entity_id'] = 11;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'consent': {
                                $array['entity_id'] = 14;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }
                case 'surveys': {
                                $array['entity_id'] = 15;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval]
                                    );
                                break;
                            }

                case 'datagrid': {
                                $array['entity_id'] = 16;
                                $array['config'] = array(
                                      "tab" => $keyval,
                                      "tab_name" => $tab_names[$keyval],
                                      'registry_id' => $value['registry']
                                    );
                                break;
                            }

                case 'file': {
                                switch ($value["subtype"])
                                {
                                  case 'file':
                                        $array['entity_id'] = 13;
                                        $array['config'] = array(
                                              "tab" => $keyval,
                                              "tab_name" => $tab_names[$keyval]
                                            );
                                    break;

                                }
                                break;
                            }


                case 'textarea': {

                  switch ($value["subtype"])
                  {
                    case 'textarea':
                          $array['entity_id'] = 2;
                          $array['config'] = array(
                                "tab" => $keyval,
                                "tab_name" => $tab_names[$keyval]
                              );
                      break;

                    default:
                         $array['entity_id'] = 2;
                         $array['config'] = array(
                                "tab" => $keyval,
                                "tab_name" => $tab_names[$keyval]
                              );
                      break;
                  }
                  break;
               }
              case 'select': {
                $array['entity_id'] = 10;
                if($value["multiple"]==true){
                  $array['is_multiple'] = 1;
                }

                $array['config'] = array(
                  "tab" => $keyval,
                  "tab_name" => $tab_names[$keyval],
                  'registry_id' => $value['registry'],
                  "label_schema" => $value['labelScheme']
                );
                break;
              }
              default:
                $array['entity_id'] = 1;
                $array['config'] = array(
                  "tab" => $keyval,
                  "tab_name" => $tab_names[$keyval]
                );
                break;
              }

              array_push($params,$array);
            }
          }
        }


      $removed_entities = Application_Service_Utilities::getModel('RegistryEntities')->getRemovedEntities($registryId, $existing_ids);
      foreach ($removed_entities as $entity_row) {
          $row = Application_Service_Utilities::getModel('RegistryEntities')->getOne([
            'id' => $entity_row['id'],
            'registry_id' => $registryId,
          ], true);
          Application_Service_Utilities::getModel('RegistryEntities')->removeEntity($row);
        }
        foreach ($params as $data)
                {
                try {
                    $this->db->beginTransaction();

                    $mode = empty($data['id'])
                        ? 'create'
                        : 'update';
                        $data['config_data'] = json_encode($data['config']);
                    $param = Application_Service_Utilities::getModel('RegistryEntities')->save($data);

                    Application_Service_Events::getInstance()->trigger(sprintf('registry.param.%s', $mode), $param);

                    $this->db->commit();
                    $status = true;
                    $message = 'Zapisano parametr';
                    }
                    catch (Exception $e)
                    {
                    }
                }
     exit();

    }




}
