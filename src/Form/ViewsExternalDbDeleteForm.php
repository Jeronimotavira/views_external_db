<?php

/**
 * @file
 * Contains \Drupal\views_external_db\Form\ViewsExternalDbDeleteForm
 */

namespace Drupal\views_external_db\Form;

use Drupal\Core\Database\Database;
use Drupal\views_external_db\Service\ViewsExternalDbService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Views Database Connector configuration form.
 */
class ViewsExternalDbDeleteForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'views_external_db_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['views_external_db.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $service = new ViewsExternalDbService;
    $data = $service->getExternalTables();
    $header = [
      'db_name' => t('DB Name'),
      'table_name' => t('Table Name'),
    ];
    $output = array();
      foreach ($data as $record) {
        $output[$record->id] = [
          'db_name' => $record->db_name,
          'table_name' => $record->table_name,
        ];
      } 
      $form['table'] = [
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $output,
        '#empty' => t('No connection found'),
      ];
     /* foreach ($data as $record) {
        $form[$record->id] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Delete @database : @table_name.', ['@database' => $record->db_name ,'@table_name'=>$record->table_name]),
          '#default_value' => 0,
        );
      } */

     /* $rows[] = $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Eliminar'),
      );

      $content['table'] = array(
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
        '#empty' => t('No entries available.'),
      );*/
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Eliminar'),
      );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  
    $val = $form_state->getValues()['table'];
    $var = [];
    $otro = $form_state->getValues();
    foreach ($val as $v ) {
      if( $v != 0 ) {
        array_push($var, $v);
      }
    }
    if(empty($var)){
      \Drupal::messenger()->addError(t('opcion no valida para esta accion.'));
      return ;
    }else{
      \Drupal::database()->delete('views_external_db')
      ->condition('id', $var, 'IN')
      ->execute();
      \Drupal::messenger()->addStatus(t('Se han borrado las base de dato recuerde quitar las vistas relacionadas con estas conexiones.'));
    }
   

  }
}