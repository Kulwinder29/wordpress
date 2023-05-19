<?php

/**
 * Plugin Name: eLs Custom Developed Paypal Plugin
 * Plugin URI: https://www.example.com/my-custom-plugin
 * Description: A brief description of what your plugin does.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://www.example.com/
 */

// Add your plugin code here
register_activation_hook(__FILE__, 'activation');
function activation()
{
  create_table();
}

function create_table()
{
  global $wpdb;
  $table = $wpdb->prefix  . 'user_subscription';
  $table2 = $wpdb->prefix  . 'paypal_order';
  $charset_collate = $wpdb->get_charset_collate();
  $table1_sql = "CREATE TABLE IF NOT EXISTS `$table` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `userid` int NOT NULL,
      `planid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
      `subscriptionid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
      `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
      `starttime` date DEFAULT NULL,
      `createddate` date NOT NULL,
      `nextpaydate` date DEFAULT NULL,
      `productid` int NOT NULL,
       PRIMARY KEY (`id`)
       )$charset_collate ;";

  // $wpdb->query($table1_sql);

  $table2_sql = "CREATE TABLE IF NOT EXISTS `$table2` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `product_id` int NOT NULL,
    `subscription_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `plan_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `create_time` date NOT NULL,
    `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
     PRIMARY KEY (`id`)
     )$charset_collate ;";


  // $wpdb->query($table2_sql);

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($table1_sql);
  dbDelta($table2_sql);
}

// register_deactivation_hook(__FILE__,'deactivation');

// function deactivation()
// {
//   global $wpdb, $table_prefix;
//   $table = $table_prefix . 'user_subscription';
//   $table1_sql = "DROP TABLE `$table`";
//   $wpdb->query($table1_sql);
// }

function els_paypal_plugin_settings_page()
{
  add_options_page(
    'PayPal Credential Settings',  // page title
    'PayPal Plugin',           // menu title
    'manage_options',             // capability
    'my-custom-plugin',           // menu slug
    'els_paypal_plugin_settings'   // callback function
  );
}
add_action('admin_menu', 'els_paypal_plugin_settings_page');

function els_paypal_plugin_settings()
{
?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields('my-custom-plugin-settings'); ?>
      <?php do_settings_sections('my-custom-plugin'); ?>
      <?php submit_button(); ?>
    </form>
  </div>
<?php
}

function els_paypal_plugin_settings_fields()
{
  add_settings_section(
    'my-custom-plugin-section',   // section ID
    'My Custom Plugin Settings',  // section title
    '',                           // section callback
    'my-custom-plugin'            // page slug
  );

  add_settings_field(
    'eLS_paypal_client_id',     // field ID
    'Enter Paypal Client Key',                      // field label
    'eLS_paypal_client_id_field', // field callback
    'my-custom-plugin',          // page slug
    'my-custom-plugin-section'   // section ID
  );

  add_settings_field(
    'eLS_paypal_secret_id',    // field ID
    'Enter Paypal Secret Key',                     // field label
    'eLS_paypal_secret_id_field', // field callback
    'my-custom-plugin',          // page slug
    'my-custom-plugin-section'   // section ID
  );

  add_settings_field(
    'eLS_paypal_mode',   // field ID
    'Select Payment Mode',                    // field label
    'eLS_paypal_mode_field', // field callback
    'my-custom-plugin',          // page slug
    'my-custom-plugin-section'   // section ID
  );

  register_setting(
    'my-custom-plugin-settings', // settings group name
    'eLS_paypal_client_id'      // setting name
  );

  register_setting(
    'my-custom-plugin-settings', // settings group name
    'eLS_paypal_secret_id'     // setting name
  );

  register_setting(
    'my-custom-plugin-settings', // settings group name
    'eLS_paypal_mode'    // setting name
  );
}
add_action('admin_init', 'els_paypal_plugin_settings_fields');

function eLS_paypal_client_id_field()
{
  $client = get_option('eLS_paypal_client_id', '');
?>
  <input type="text" name="eLS_paypal_client_id" value="<?php echo esc_attr($client); ?>">
<?php
}

function eLS_paypal_secret_id_field()
{
  $secret = get_option('eLS_paypal_secret_id', '');
?>
  <input type="text" name="eLS_paypal_secret_id" value="<?php echo esc_attr($secret); ?>">
<?php
}

function eLS_paypal_mode_field()
{
  $mode = get_option('eLS_paypal_mode', '');
?>
  <select name="eLS_paypal_mode">
    <option value="sandbox" <?php selected($mode, 'sandbox'); ?>>Sandbox</option>
    <option value="live" <?php selected($mode, 'live'); ?>>Live</option>

  </select>
<?php
}



$paypalInitialize = plugin_dir_path(__FILE__) . 'paypal/paypalinitialize.php';

if (file_exists($paypalInitialize)) {
  require_once($paypalInitialize);
}


// echo "hello".get_paypal_access_token();


// on Wc Product Create Function 
$file_path = plugin_dir_path(__FILE__) . 'function/create_paypal_product.php';

// Include the file
if (file_exists($file_path)) {
  require_once($file_path);
}


function my_webhook_listener()
{
  global $wpdb;
  // Get the webhook event data from PayPal
  $request_body = file_get_contents('php://input');

  $payload = json_decode($request_body, true);
  $status = $payload['resource']['status'];

  // Get the subscription ID from the webhook data
  // $subscription_id = $payload['resource']['id'];



  if ($payload['event_type'] === 'BILLING.SUBSCRIPTION.CREATED') {
    $user_id = $payload['resource']['custom_id'];
    $subscription_id = $payload['resource']['id'];
    $planId = $payload['resource']['plan_id'];
    $create_time = $payload['resource']['create_time'];
    $status = $payload['resource']['status'];

    $table_name = $wpdb->prefix . 'postmeta';
    $query = "SELECT post_id FROM $table_name WHERE meta_key='_paypal_product_plan_id' AND meta_value='$planId'";
    $product_id = $wpdb->get_var($query);

    $table = $wpdb->prefix . 'user_subscription';
    $data = array(
      'userid' => $user_id,
      'planid' => $planId,
      'subscriptionid' => $subscription_id,
      'status' => $status,
      'createddate' => $create_time,
      'productid' => $product_id,
    );
    $wpdb->insert($table, $data);
  }
  paypal_order_create($payload);
  if ($payload['event_type'] === 'PAYMENT.SALE.COMPLETED') {
    paypal_renewable_order_create($payload);
  }

  if ($payload['event_type'] === 'BILLING.SUBSCRIPTION.ACTIVATED') {
    $subscription_id = $payload['resource']['id'];
    $status = $payload['resource']['status'];
    // Get the next billing cycle object from the webhook data
    $next_billing_cycle = $payload['resource']['billing_info']['next_billing_time'];
    // Extract the date and time from the billing cycle object
    $next_payment_date = date('Y-m-d H:i:s', strtotime($next_billing_cycle));
    $start_billing_cycle = $payload['resource']['start_time'];
    // Extract the date and time from the billing cycle object
    $start_date = date('Y-m-d H:i:s', strtotime($start_billing_cycle));

    $table_name = $wpdb->prefix . 'user_subscription';

    $data = array(
      'status' => $status,
      'starttime' => $start_billing_cycle,
      'nextpaydate' => $next_billing_cycle,
    );
    $where = array(
      'subscriptionid' => $subscription_id,
    );

    $wpdb->update($table_name, $data, $where);
  }
  // Do something with the webhook data, e.g. update a database
}
add_action('wp_ajax_nopriv_paypal_webhook', 'my_webhook_listener');
add_action('wp_ajax_paypal_webhook', 'my_webhook_listener');

function paypal_order_create($payload)
{
  global $wpdb;
  if ($payload['event_type'] === 'BILLING.SUBSCRIPTION.ACTIVATED') {
    $user_id = $payload['resource']['custom_id'];
    $subscription_id = $payload['resource']['id'];
    $planId = $payload['resource']['plan_id'];
    $create_time = $payload['resource']['create_time'];
    // $status = $payload['resource']['status'];

    $table_name = $wpdb->prefix . 'postmeta';
    $query = "SELECT post_id FROM $table_name WHERE meta_key='_paypal_product_plan_id' AND meta_value='$planId'";
    $product_id = $wpdb->get_var($query);

    $table = $wpdb->prefix . 'paypal_order';
    $data = array(
      'user_id' => $user_id,
      'product_id' => $product_id,
      'subscription_id' => $subscription_id,
      'plan_id' => $planId,
      'create_time' => $create_time,
      'status' => 'COMPLETED',
    );
    $wpdb->insert($table, $data);
  }
}


function paypal_renewable_order_create($payload)
{
  global $wpdb;
  $user_id = $payload['resource']['custom'];
  $subscription_id = $payload['resource']['billing_agreement_id'];
  $create_time = $payload['resource']['create_time'];
  $status = $payload['resource']['state'];

  $user_subscription_table_name = $wpdb->prefix . 'user_subscription';
  $user_query = "SELECT planid FROM $user_subscription_table_name WHERE subscriptionid='$subscription_id'";
  $planid = $wpdb->get_var($user_query);

  $table_name = $wpdb->prefix . 'postmeta';
  $query = "SELECT post_id FROM $table_name WHERE meta_key='_paypal_product_plan_id' AND meta_value='$planid'";
  $product_id = $wpdb->get_var($query);

  $table = $wpdb->prefix . 'paypal_order';
  $data = array(
    'user_id' => $user_id,
    'product_id' => $product_id,
    'subscription_id' => $subscription_id,
    'plan_id' => $planid,
    'create_time' => $create_time,
    'status' => $status,
  );

  $where = array(
    'subscription_id' => $subscription_id,
  );

  $wpdb->update($table, $data, $where);
}
