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
 *   id = "patch_blog_post",
 *   label = @Translation("Update Article Using REST API [patch]"),
 *   uri_paths = {
 *     "canonical" = "/update_blog_post/{nid}"
 *   }
 * )
 */
class patchBlogPost extends ResourceBase {

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
   * Responds to POST requests.
   *
   * Creates a new node.
   *
   * @param mixed $data
   *   Data to create the node.
   * @param int $nid
   *   Data to Delete the node.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function patch($data, $nid = NULL) {

    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      // Display the default access denied page.
      throw new AccessDeniedHttpException('Access Denied.');
    }

    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);

    if ($node == NULL) {
      // Response With Node Id And Message.
      $message = $this->t("@nid Node Not Found", ['@nid' => $nid]);

      // Return response with 404 - Not Found Status Code.
      return new ResourceResponse($message, 404);
    }

    foreach ($data as $value) {

      if (!empty($value['image_data'])) {
        // Filter By Regex.
        $clean_base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $value['image_data']);
        // Decode base64 Data.
        $base64 = base64_decode($clean_base64);
      }

      $filename = "public://" . $value['image_name'];
      // Save the Image.
      $savedFile = file_save_data($base64, $filename, FILE_EXISTS_REPLACE);
      $imageId = $savedFile->id();

      // Upating Data
      $node->title->value = $value['title'];
      $node->body->value = $value['body'];
      $field_image = [
        'target_id' => $imageId,
        'alt' => $value['image_name'],
      ];
      $node->field_image = $field_image;
      $node->save();

      $this->logger->notice($this->t("Node with nid @nid Updated!\n", ['@nid' => $nid]));
    }
    // Response With Node Id And Message.
    $message = $this->t("@nid Node Updated Successfully", ['@nid' => $nid]);

    // Return response with 202 - Accepted Status Code.
    return new ResourceResponse($message, 202);

  }

}
