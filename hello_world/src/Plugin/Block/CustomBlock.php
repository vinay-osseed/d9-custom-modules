<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase as blockB;

/**
 *
 * @file
 * Contains \Drupal\hello_world\Plugin\Block\ here.
 */

/**
 * Provides a Custom Block.
 *
 * @Block(
 *   id = "customBlock",
 *   admin_label = @Translation("Hello World Custom Block"),
 *   category = @Translation("D9 Custom Block"),
 * )
 */
class CustomBlock extends blockB {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'markup',
      '#markup' => '<strong>Hello World</strong> In Custom Block.',
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
