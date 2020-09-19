<?php

/**
 * @file
 * Contains \Drupal\views_external_db\Form\ViewsExternalDbAddForm
 */

namespace Drupal\views_external_db\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Views Database Connector configuration form.
 */
class ViewsExternalDbAddForm extends FormBase {

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
   
    $form['db_name'] = array(
        '#title' => t('Base de datos'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("Nombre de la base de datos."),
        '#required' => TRUE,
      );
      $form['user_name'] = array(
        '#title' => t('Usuario'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("Usuario de la base de datos."),
        '#required' => TRUE,
      );
      $form['password'] = array(
        '#title' => t('Password'),
        '#type' => 'textfield',
        '#size' => 155,
        '#description' => t("Password de la base de datos."),
        '#required' => TRUE,
      );
      $form['table_name'] = array(
        '#title' => t('Tabla'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("Nombre de la Tabla en la base de datos."),
        '#required' => TRUE,
      );
      $form['host'] = array(
        '#title' => t('Hostname'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("Nombre del hostname (LOCALHOST)."),
        '#required' => TRUE,
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Añadir'),
      );
    
    return $form;
  }

  /**
   * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::database()->insert('views_external_db')
    ->fields(array(
      'db_name' => $form_state->getValue('db_name'),
      'user_name' => $form_state->getValue('user_name'),
      'password' => $form_state->getValue('password'),
      'table_name' => $form_state->getValue('table_name'),
     ))
    ->execute();
    drupal_flush_all_caches();
    \Drupal::messenger()->addStatus(t('La DB y la tabla fueron añadidas para crear vistas.'));

  }
}