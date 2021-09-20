<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase as formB;
use Drupal\Core\Form\FormStateInterface as formStIn;

/**
 * @file
 * Contains \Drupal\hello_world\Form\ here.
 */

/**
 * Return simple custom form.
 */
class CustomForm extends formB {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simpleCustomFormId';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, formStIn $form_state) {
    $form['fullName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Full Name:'),
      '#required' => TRUE,
    ];

    $form['aboutUser'] = [
      '#title' => $this->t('Tell Us About Yourself:'),
      '#type' => 'textfield',
      '#required' => TRUE,
    ];

    $form['phoneNumber'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your Mobile No:'),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, formStIn $form_state) {
    $this->messenger()->addMessage($this->t('Your Form is Submitted!'));
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, formStIn $form_state) {
    if (strlen($form_state->getValue('phoneNumber')) < 10) {
      $form_state->setErrorByName('phoneNumber', $this->t('Mobile number is too Short.'));
    }
    if (strlen($form_state->getValue('phoneNumber')) > 10) {
      $form_state->setErrorByName('phoneNumber', $this->t('Mobile number is too Long.'));
    }
  }

}
