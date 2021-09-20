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
use Drupal\Core\Form\FormBase as formB;
use Drupal\Core\Form\FormStateInterface as formStIn;

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

    $form['fullName'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Your Full Name:'),
      '#required' => TRUE,
    );

    $form['aboutUser'] = array(
      '#title' => $this->t('Tell Us About Yourself:'),
      '#type' => 'textfield',
      '#required' => TRUE,
    );

    $form['phoneNumber'] = array (
      '#type' => 'tel',
      '#title' => $this->t('Your Mobile No:'),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
          '#button_type' => 'primary',
        );
    return $form;
   }
/**
*{@inheritdoc}
*/
    public function submitForm(array &$form, formStIn $form_state) {
        $this->messenger()->addMessage($this->t('Your Form is Submitted!'));
    }

    public function validateForm(array &$form, formStIn $form_state) {

        if (strlen($form_state->getValue('phoneNumber')) < 10) {
            $form_state->setErrorByName('phoneNumber', $this->t('Mobile number is too Short.'));
        }

        if (strlen($form_state->getValue('phoneNumber')) > 10) {
            $form_state->setErrorByName('phoneNumber', $this->t('Mobile number is too Long.'));
        }
    }

 }
?>
