<?php

namespace Drupal\axelerant\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class PageNodeController.
 */
class PageNodeController extends ControllerBase {

  /**
   * Fetch Node Details.
   *
   * @param object $request
   *   Request object.
   *
   * @return json
   *   Return node data in json format.
   */
  public function details(Request $request) {

    // Fetch siteApiKey value.
    $route_site_api_key = $request->get('siteApiKey');
    // Fetch nid value.
    $nid = $request->get('nid');

    // Fetch system site configuration object.
    $config = \Drupal::config('system.site');
    // Fetch siteapikey value from config.
    $site_api_key = $config->get('siteapikey');

    // Compare site api key in url & config.
    if ($route_site_api_key == $site_api_key) {

      // Fetch node details.
      $node_obj = \Drupal::entityTypeManager()->getStorage('node')->load($nid);

      /*
       * Check if node:
       * a) node exists.
       * b) is of type page.
       * c) is published.
       */
      if (!empty($node_obj) && ($node_obj->id()) && ($node_obj->bundle() == 'page')
       && ($node_obj->status->value == 1)) {
        // Convert node obj to array.
        $node = $node_obj->toArray();

        // Return node data in json format.
        return new JsonResponse([
          'data' => $node,
          'status' => 200,
        ]);
      }
    }

    // Throw Access Denied error.
    throw new AccessDeniedHttpException();

  }

}
