<?php
  /*
  Standards:
      ->  Naming Conventions : * beDescriptive * (varName,fncName,clsName,flName) (SQL:*_UPPERCASE*)
      ->  Case Types         : * camelCase     * (CONST:*_UPPERCASE*)
      ->  Do Comments        : * Good & Bad    * (Snipppets:['/':'single comment','/*':'block comment'])
      ->  Consistency        : * beConsistant  * (Comments,Code,Values)
      ->  Indentation        : * TAB = 4space  *
      ->  Readability        : * useSpaces     *
      ->  Indentation        : * TAB = 4space  *
  */
/**
 * @file
 * Contains \Drupal\hello_world\Form\.
 * @author Vinay Gawade
 */
namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfigFormBase as configFormB;
use Drupal\Core\Form\FormStateInterface as formStIn;

class customConfigSimpleForms extends configFormB{
  /**
   * {@inheritdoc}
   */
  public function getFormId(){
    return 'customConfigSimpleFormId';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()  {
    return [
      'hello_world.settings',
    ];
  }

  /**
  * {@inheritdoc}
  */
public function buildForm(array $form, formStIn $form_state)
 {
  $config = $this->config('hello_world.settings');
  $form['fullName'] = array(
    '#type' => 'textfield',
    '#title' => $this->t('Your Full Name:'),
    '#required' => TRUE,
    '#default_value' => $config->get('fullName'),

  );
  $form['aboutUser'] = array(
     '#type' => 'textarea',
       '#title' => $this->t('Tell Us About Yourself:'),
     '#default_value' => $config->get('aboutUser'),
   );
   $form['phoneNumber'] = array (
     '#type' => 'tel',
     '#title' => $this->t('Your Mobile No:'),
     '#default_value' => $config->get('phoneNumber'),

   );
   return parent::buildForm($form, $form_state);
 }

  /**
   * {@inheritdoc}
   */
public function validateForm(array &$form, formStIn $form_state){
    parent::validateForm($form, $form_state);
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
 ?>
