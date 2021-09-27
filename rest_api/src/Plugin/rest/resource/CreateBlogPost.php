<?php

namespace Drupal\rest_api\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\Entity\Node;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MongoDB\BSON\Regex;

/**
 *
 * @file
 * Contains Drupal\rest_api\Plugin\rest\resource here.
 */

/**
 * Provides Info.
 *
 * @RestResource(
 *   id = "create_blog_post",
 *   label = @Translation("Create Article Using REST API [POST]"),
 *   uri_paths = {
 *     "canonical" = "/create_blog_post"
 *   }
 * )
 */
class CreateBlogPost extends ResourceBase {

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
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data) {

    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      // Display the default access denied page.
      throw new AccessDeniedHttpException('Access Denied.');
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

      $node = Node::create(
      // Pass The Article Node Parameters Here.
        [
          'type' => 'article',
          'title' => $value['title'],
          'body' => [
            'summary' => '',
            'value' => $value['body'],
            'format' => 'basic_html',
          ],
          'field_image' => [
            'target_id' => $imageId,
            'alt' => $value['image_name'],
          ],
        ]
      );
      $node->enforceIsNew();

      // Save Node Values.
      $node->save();

      $this->logger->notice($this->t("Node with nid @nid saved!\n", ['@nid' => $node->id()]));
      // Get Currently Saved Node's Id.
      $nodes[] = $node->id();
    }
    // Response With Node Id And Message.
    $message = $this->t("New Nodes Created with nids : @message", ['@message' => implode(",", $nodes)]);

    // Return response with 201 - Created Status Code.
    return new ResourceResponse($message, 201);

  }

}
