<?php

 
namespace Drupal\views_external_db\Service;
 
use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Html;
use Drupal\Core\Database\Connection;

class ViewsExternalDbService {

    public function __construct() {

    }
    
    function getExternalTables(){
        $database = \Drupal::database();
        $query = $database->select('views_external_db', 'r');
        $query->fields('r');
        $data = $query->execute();
        return $data;
    }

    function getTableConnection($dbname , $tablename){
      
      if($dbname != null and $tablename != null){
        $database = \Drupal::database();
        $query = $database->select('views_external_db', 'r');
        $query->fields('r');
        $query->condition('db_name', $dbname, '=');
        $query->condition('table_name', $tablename, '=');
        $data = $query->execute();
        return $data->fetchAll()[0];
      }
      return null;
      
    }

    function checkconection($param){
      try {
        $pdo = new \PDO("mysql:host=$param->host;dbname=".$param->db_name, $param->user_name, $param->password);
        if($pdo->query("describe ".$param->table_name.";")){
          $pdo = null;
          return true;
        } 
      } catch (\Exception $e) {
        return false;     
      }
      
     
    }

    function infoViews($param) {
        $types = $this->getTypes();
        // Switch to database in question.
        if(!$this->checkconection($param)){
          return null;
        }
        Database::addConnectionInfo($param->db_name, 'default', array( 'driver' => 'mysql',
        'database' => $param->db_name,
        'username' => $param->user_name,
        'password' => $param->password,
        'host' => $param->host,));
      
        Database::setActiveConnection($param->db_name);

        // The database in question.
      
          $new_db = Database::getConnection('default', $param->db_name);
      
           
        // Get a list of the tables in this database.
     
        $mitable = $new_db->query('DESCRIBE '.$param->table_name.' ;');
    
      
        while ($row = $mitable->fetchAssoc()) {
         
          foreach ($types as $type => $matches) {
            foreach ($matches as $match) {
              if (stristr($row['Type'], $match)) {
                $t = $type;
              }
            }
          }
          $collist2[] = [$t, $row['Field']];
        
        }
        $tablelist = ['trabajador',$collist2];

        Database::setActiveConnection('default');
        return $tablelist;
      }

      function getTypes() {
        $types = [
          'numeric' => [
            'int',
            'decimal',
            'numeric',
            'float',
            'double',
            'bit',
          ],
          'date' => [
            'date',
            'time',
            'year',
          ],
          'string' => [
            'char',
            'binary',
            'blob',
            'text',
            'enum',
            'set',
          ],
        ];
      
        return $types;
      }

}