<?php

class Application_Model_TicketsStatuses extends Muzyka_DataModel {

    protected $_name = "tickets_statuses";
    protected $_base_name = 'ts';
    protected $_base_order = ['ts.state ASC', 'ts.name ASC'];

    public $memoProperties = [
        'id',
        'type_id',
        'state',
    ];

    public $id;
    public $type_id;
    public $state;
    public $name;
    public $system_name;
    public $color;
    public $created_at;
    public $updated_at;

    /**
     * @return self|Zend_Db_Table_Row|Zend_Db_Table_Row_Abstract
     */
    public function save($data)
    {
        if (empty($data['id'])) {
            unset($data['id']);
            $row = $this->createRow($data);
            $row->created_at = date('Y-m-d H:i:s');
        } else {
            $row = $this->requestObject($data['id']);
            $row->setFromArray($data);
            $row->updated_at = date('Y-m-d H:i:s');
        }

        $id = $row->save();

        $this->addLog($this->_name, $row->toArray(), __METHOD__);

        return $row;
    }

}
