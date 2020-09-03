<?php
  /**
   * Super-simple webhook example that does two things:
   *      1. Subscribes new plugin users to MailChimp's mailing list.
   *      2. Send post uninstall custom email to users based on different uninstall reasons.
   *
   * @copyright   Copyright (c) 2016, Freemius, Inc.
   * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
   * @since       1.0.3
   */

  // Retrieve the request's body and parse it as JSON
  $input = @file_get_contents("php://input");

  $event_json = json_decode($input);

  if ( ! isset($event_json->id)) {
    http_response_code(200);
    exit();
  }

  /**
   * Freemius PHP SDK can be downloaded from GitHub:
   *  https://github.com/Freemius/php-sdk
   */
  require_once '/freemius-sdk/Freemius.php';

  $fs = new Freemius_Api(
    'plugin',
    'YOUR_PLUGIN_ID',
    'YOUR_PLUGIN_PUBLIC_KEY',
    'YOUR_PLUGIN_SECRET_KEY'
  );

  $fs_event = $fs->Api("/events/{$event_json->id}.json");

  switch ($fs_event->type) {
    case 'install.installed':
      $user  = $fs_event->objects->user;
      $email = $user->email;
      $first = $user->first;
      $last  = $user->last;
      $full_name = $first . ' ' . $last;

      /**
       * ConvertKit SDK can be downloaded from here:
       *  https://github.com/ConvertKit/ConvertKitSDK-PHP
       */
      require_once '/convertkit-sdk/ConvertKit_API.php';

      $api = new \ConvertKit_API\ConvertKit_API($api_key, $api_secret);

      $tag_id = '99999'; // This tag must be valid for your ConvertKit account.

      $options = [
        'email'      => $email,
        'name'       => $full_name,
        'first_name' => $first,
        'tags'       => $tag_id
      ];

      $subscribed = $api->form_subscribe($this->test_form_id, $options);
      break;
    case 'install.uninstalled':
      $user  = $fs_event->objects->user;
      $email = $user->email;
      $first = $user->first;
      $last  = $user->last;

      $fs_uninstall = $fs->Api("/installs/{$fs_event->objects->install->id}/uninstall.json");

      // Send email with your favorite service (we use SendGrid).
      define('FS__UNINSTALL_REASON_NO_LONGER_NEEDED', 1);
      define('FS__UNINSTALL_REASON_FOUND_BETTER_PLUGIN', 2);
      define('FS__UNINSTALL_REASON_USED_FOR_SHORT_PERIOD', 3);
      define('FS__UNINSTALL_REASON_BROKE_WEBSITE', 4);
      define('FS__UNINSTALL_REASON_STOPPED_WORKING', 5);
      define('FS__UNINSTALL_REASON_CANT_CONTINUE_PAYING', 6);
      define('FS__UNINSTALL_REASON_OTHER', 7);
      define('FS__UNINSTALL_REASON_DID_NOT_WORK_ANONYMOUS', 8);
      define('FS__UNINSTALL_REASON_DONT_LIKE_INFO_SHARE', 9);
      define('FS__UNINSTALL_REASON_UNCLEAR_HOW_WORKS', 10);
      define('FS__UNINSTALL_REASON_MISSING_FEATURE', 11);
      define('FS__UNINSTALL_REASON_DID_NOT_WORK', 12);
      define('FS__UNINSTALL_REASON_EXPECTED_SOMETHING_ELSE', 13);
      define('FS__UNINSTALL_REASON_EXPECTED_TO_WORK_DIFFERENTLY', 14);

      switch ($fs_uninstall->reason_id) {
        case FS__UNINSTALL_REASON_NO_LONGER_NEEDED:
          // Send email.
          break;
        case FS__UNINSTALL_REASON_STOPPED_WORKING:
          // Send email.
          break;
        default:
          // Send email.
          break;
      }

      break;
  }

  http_response_code(200);