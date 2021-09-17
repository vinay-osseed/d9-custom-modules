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
 * Contains \Drupal\hello_world\Plugin\Block\.
 */
namespace Drupal\hello_world\Plugin\Block;
use Drupal\Core\Block\BlockBase as blockB;

/**
 * Provides a Custom Block.
 *
 * @Block(
 *   id = "customBlock",
 *   admin_label = @Translation("Hello World Custom Block"),
 *   category = @Translation("D9 Custom Block"),
 * )
 */
class customBlock extends blockB {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#type' => 'markup',
      '#markup' => '<strong>Hello World</strong> In Custom Block.',
      '#cache' => [
          'max-age' => 0,
          ]
    );
  }
}
