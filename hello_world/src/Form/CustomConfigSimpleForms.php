<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfigFormBase as configFormB;
use Drupal\Core\Form\FormStateInterface as formStIn;

/**
 * @file
 * Contains \Drupal\hello_world\Form\ here.
 */

/**
 * Return simple form.
 */
class CustomConfigSimpleForms extends configFormB {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customConfigSimpleFormId';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'hello_world.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, formStIn $form_state) {
    $config = $this->config('hello_world.settings');
    $form['fullName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Full Name:'),
      '#required' => TRUE,
      '#default_value' => $config->get('fullName'),
    ];

    $form['aboutUser'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Tell Us About Yourself:'),
      '#default_value' => $config->get('aboutUser'),
    ];

    $form['phoneNumber'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your Mobile No:'),
      '#default_value' => $config->get('phoneNumber'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, formStIn $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('hello_world.settings')
      ->set('fullName', $form_state->getValue('fullName'))
      ->set('aboutUser', $form_state->getValue('aboutUser'))
      ->set('phoneNumber', $form_state->getValue('phoneNumber'))
      ->save();
  }

}
