<?php

class Application_Model_Eventspersons extends Muzyka_DataModel
{
    protected $_name = 'eventspersons';

    public $id;
    public $name;
    public $eventscompany_id;
    public $eventspersonstype_id;
    public $lastname;
    public $number;
    public $veterinary_number;
    public $pesel;
    public $created_at;
    public $updated_at;

    public $memoProperties = array(
        'id',
        'eventscompany_id',
        'eventspersonstype_id',
    );

    public function getOne($id)
    {
        $sql = $this->select()
            ->where('id = ?', $id);

        return $this->fetchRow($sql);
    }

    public function getAll()
    {
        return $this->select()
            ->order('name ASC')
            ->query()
            ->fetchAll();
    }

    public function save($data)
    {
        if (!(int)$data['id']) {
            $row = $this->createRow();
            $row->created_at = date('Y-m-d H:i:s');
        } else {
            $row = $this->getOne($data['id']);
            $row->updated_at = date('Y-m-d H:i:s');
        }

        $row->name = preg_replace('/\s+/', ' ', trim(mb_strtoupper($data['name'])));
        $row->lastname = preg_replace('/\s+/', ' ', trim(mb_strtoupper($data['lastname'])));
        $row->number = $data['number'];
        $row->veterinary_number = $data['veterinary_number'];
        $row->eventscompany_id = $data['eventscompany_id'] * 1;
        $row->eventspersonstype_id = $data['eventspersonstype_id'] * 1;
        $row->pesel = (int)$data['pesel'];
        $id = $row->save();
        $this->addLog($this->_name, $row->toArray(), __METHOD__);

        return $id;
    }

    public function remove($id)
    {
        $row = $this->getOne($id);
        if (!($row instanceof Zend_Db_Table_Row)) {
            throw new Exception('Rekord nie istnieje lub zostal skasowany');
        }

        $row->delete();
        $this->addLog($this->_name, $row->toArray(), __METHOD__);
    }
}