<?php

/**
 * @file
 * Responsible for hooking views to add each database and its tables.
 */

use Drupal\Component\Utility\Html;
use Drupal\views_external_db\Service\ViewsExternalDbService;
/**
 * Implements hook_views_data().
 */
function views_external_db_views_data() {
    
  $servicio = new ViewsExternalDbService;
  $tablas = $servicio->getExternalTables();
  $data = [];
    foreach($tablas as $table){
      $miTabla = $servicio->infoViews($table);
        if (!isset($data[$table->table_name])) {
          // Set the title.
          $title = '[Externa DB'. Html::escape($table->db_name).']   ';
          $title .= Html::escape($table->table_name);
          // Setup the table for Views to be able to see it.
          $data[$table->table_name]['table']['group'] = $table->table_name;
          $data[$table->table_name]['table']['base'] = [
            // Use the first column's name as the primary field.
            'field' => $miTabla[1][0][1],
            'title' => $title,
            'database' => $table->db_name,
            'weight' => -9001,
          ];
        
          foreach ($miTabla[1] as $col) {
           if ($col[0] == 'numeric') {
            $data[$table->table_name][$col[1]] = [
              'title' => $col[1],
              'help' => $col[1],
              'field' => [
                'id' => 'numeric',
              ],
              'sort' => [
                'id' => 'standard',
              ],
              'filter' => [
                'id' => 'numeric',
              ],
              'argument' => [
                'id' => 'numeric',
              ],
            ];
           }
           if ($col[0] == 'date') {
            $data[$table->table_name][$col[1]] = [
              'title' => $col[1],
              'help' => $col[1],
              'field' => [
                'id' => 'date',
              ],
              'sort' => [
                'id' => 'date',
              ],
              'filter' => [
                'id' => 'date',
              ],
            ];
           }
           if ($col[0] == 'string') {
            $data[$table->table_name][$col[1]] = [
              'title' => $col[1],
              'help' => $col[1],
              'field' => [
                'id' => 'standard',
              ],
              'sort' => [
                'id' => 'standard',
              ],
              'filter' => [
                'id' => 'string',
              ],
              'argument' => [
                'id' => 'string',
              ],
            ];
           }
           if ($col[0] == 'broken') {
            $data[$table->table_name][$col[1]] = [
              'title' => $col[1],
              'help' => $col[1],
              'field' => [
                'id' => 'broken',
              ],
              'sort' => [
                'id' => 'broken',
              ],
              'filter' => [
                'id' => 'broken',
              ],
              'argument' => [
                'id' => 'broken',
              ],
            ];
           }
          }
        }
    }
    return $data;
}
   

