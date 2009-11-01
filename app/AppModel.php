<?php

class AppModel
{
    
    protected $db;
    protected $table;
    
    function __construct()
    {
        try 
        {
            $this->db = new PDO('sqlite:' . getenv('DOCUMENT_ROOT') . '/database.sdb');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            BadKitty::getInstance()->error('db');
        }
    }
    
    /**
     * General find function
     */
    public function find($amount = 'all', $fields = array(), $conditions = array())
    {
        $query = 'SELECT ';
        
        if (empty($fields)) 
        {
            $query .= '* ';
        }
        else
        {
            for ($i = 0; $i < count($fields); $i++) 
            {
                $query .= $field;
                if ($i < (count($fields) - 1)) 
                {
                    $query .= ', ';
                }
            }
        }
        
        $query .= "FROM `$this->table` ";
        
        if (!empty($conditions)) 
        {
            $query .= 'WHERE ';
            $i = 0;
            foreach ($conditions as $key => $value) 
            {
                if ($i > 0)
                {
                    $query .= ' AND ';
                }
            
                $query .= '`' . $key . '` = "' . $value . '"';
            
                $i++;
            }
        }
        
        $model = $this->db->prepare($query);
        $model->execute();

        switch ($amount) {
            case 'first':
                $results = $model->fetch();
                break;
            
            default:
                $results = $model->fetchAll();
                break;
        }

        return $results;
    }
    
    /**
     * Write your own method to check if an entry exists in the database
     * @return boolean
     */
    public function isNew($data)
    {
        return TRUE;
    }
    
    /**
     * Use add or update depending on whether or not this is a new entry.
     * @return void
     */
    public function save($data)
    {
        if ($this->isNew($data)) 
        {
            return $this->add($data);
        }
        else
        {
            return $this->update($data);
        }
    }
    
}

?>