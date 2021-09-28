<?php

namespace Drupal\rest_api\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @file
 * Contains Drupal\rest_api\Plugin\rest\resource here.
 */

/**
 * Provides Info.
 *
 * @RestResource(
 *   id = "delete_blog_post",
 *   label = @Translation("Delete Article Using REST API [DELETE]"),
 *   uri_paths = {
 *     "canonical" = "/delete_blog_post/{nid}"
 *   }
 * )
 */
class DeleteBlogPost extends ResourceBase {

  use StringTranslationTrait;

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, LoggerInterface $logger, AccountProxyInterface $current_user) {

    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get($plugin_id),
      $container->get('current_user')
    );
  }

  /**
   * Responds to DELETE requests.
   *
   * Creates a new node.
   *
   * @param int $nid
   *   Data to Delete the node.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function delete($nid = NULL) {

    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      // Display the default access denied page.
      throw new AccessDeniedHttpException('Access Denied.');
    }

    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    if ($node) {
      $node->delete();
      // Deleted That Node From Instance.
    }

    // Return response with 204 - No Content Status Code.
    return new ResourceResponse(NULL, 204);

  }

}
