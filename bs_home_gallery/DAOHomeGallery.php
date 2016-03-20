<?php

/**
 * Created by WTEH.
 * Date: 3/20/2016
 * Time: 12:35 AM
 */
class DAOHomeGallery extends DAO
{
    /**
     * @var
     */
    private static $instance;


    /**
     *
     */
    function __construct() {
        parent::__construct();
        $this->setTableName('bs_home_gallery') ;
        $this->setPrimaryKey('bs_key_id') ;
        $this->setFields( array('s_title', 's_description', 's_image') ) ;
    }

    /**
     * @return DAOCategoryIcon
     */
    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return array
     */
    public function getAllImageGallery($order)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTableName());
        $this->dao->orderBy($this->getPrimaryKey() ,$order);
        $result = $this->dao->get();

        if($result == false) {
            return array();
        }

        return $result->result();
    }

    /**
     * @return array
     */
    public function addHomeImageGallery(array $values)
    {
        return $this->dao->insert($this->getTableName(), $values);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleteByGalleryId($id)
    {
        $cond = array(
            $this->getPrimaryKey() => $id
        );

        return $this->dao->delete($this->getTableName(), $cond);
    }

    /**
     * @return mixed
     */
    public function dropHomeGalleryTable()
    {
        return $this->dao->query('DROP TABLE '.$this->getTableName());
    }

    /**
     * @param $sql
     * @return bool
     */
    public function importSql($sql)
    {
        return $this->dao->importSQL($sql);
    }
}