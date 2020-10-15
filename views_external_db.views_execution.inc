<?php 
use Drupal\Core\Database\Database;
use Drupal\views_external_db\Service\ViewsExternalDbService;

function views_external_db_views_query_alter(Drupal\views\ViewExecutable $view, Drupal\views\Plugin\views\query\Sql $query) {
 
    $servicio = new ViewsExternalDbService;
    $table = $servicio->getTableConnection($view->base_database , $view->storage->get('base_table'));
    if($table != null){
        Database::addConnectionInfo($table->db_name, 'default', array( 'driver' => 'mysql',
        'database' => $table->db_name,
        'username' => $table->user_name,
        'password' => $table->password,
        'host' => $table->host,));

    }
    
}


?>