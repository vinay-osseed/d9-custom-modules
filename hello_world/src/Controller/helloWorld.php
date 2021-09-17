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
 * Contains \Drupal\hello_world\Controller\.
 * @author Vinay Gawade
 */
namespace Drupal\hello_world\Controller;
/**
 * return simple markup text
 * @return array
 */
class helloWorld {
  public function show() {
    $text = array(
      '#markup' => "Practice Makes Developer's <del>Perfect</del> Good Debugger!",
    );
    return $text;
  }
}
?>
