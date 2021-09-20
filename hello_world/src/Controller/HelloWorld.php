<?php

namespace Drupal\hello_world\Controller;

/**
 *
 * @file
 * Contains \Drupal\hello_world\Controller\ here.
 */


/**
 * Return simple markup text.
 *
 * @return array
 */
class HelloWorld {

  /**
   * Here's the function to return the title and markup text.
   */
  public function show() {
    $text = [
      '#title' => "HELLO WORLD",
      '#markup' => "Practice Makes Developer's <del>Perfect</del> Good Debugger!",
    ];
    return $text;
  }

}
