<?php

function my_custom_action_on_product_publish( $post_id, $post, $update ) {

     // If this is a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) )
        return;   

   $post_type =$post->post_type;
   $post_status =$post->post_status;    

    if ( $post_type== 'product' && $post_status=='publish') {

        try{
      
        $mode = get_option( 'eLS_paypal_mode', '' );

        $endpoint = ($mode=='sandbox') ? 'https://api.sandbox.paypal.com/v1/catalogs/products' : 'https://api.paypal.com/v1/catalogs/products';

            // Set up the HTTP headers
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer '.get_paypal_access_token()
            );

            // Set up the request body
            $request_body = array(
                'name' => 'eLs-'.get_the_title($post_id),
                'description' =>'eLs-'. get_the_title($post_id),
                'type' => 'SERVICE',
                // 'category' => 'SERVICE',
                'image_url' => 'https://example.com/images/product.jpg',
                'home_url' => 'https://example.com'
            );

            // Make the API call
            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_create_product = curl_exec($ch);
            curl_close($ch);

            // Parse the API response
            $response_data = json_decode($response_create_product, true);

          

            $product_id = $response_data['id'];

            update_post_meta($post_id,'_paypal_product_id',$product_id);

            create_paypal_plan($product_id,$post_id);


            } catch ( Exception $e ) {
    // Handle the exception
            }
                }
}
add_action( 'wp_insert_post', 'my_custom_action_on_product_publish',10, 3);



function create_paypal_plan ($product_id,$wc_product_id){

$mode = get_option( 'eLS_paypal_mode', '' );    

$endpoint_billing_plans = ($mode=='sandbox') ? 'https://api.sandbox.paypal.com/v1/billing/plans' : 'https://api.paypal.com/v1/billing/plans';

 $price = get_post_meta( $wc_product_id, '_regular_price', true );

 $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer '.get_paypal_access_token()
            );


$request_body_billing_plans = array(
    'product_id' => $product_id,
    'name' => get_the_title($wc_product_id).'-plan',
    'description' => 'My Subscription Plan Description',
    'type' => 'FIXED',
    'billing_cycles' => array(
        array(
            'frequency' => array(
                'interval_unit' => 'DAY',
                'interval_count' => 1
            ),
            'tenure_type' => 'REGULAR',
            'sequence' => 1,
            'total_cycles' => 0,
            'pricing_scheme' => array(
                'fixed_price' => array(
                    'value' => $price,
                    'currency_code' => 'USD'
                )
            )
        )
    ),


    'payment_preferences' => array(
        'auto_bill_outstanding' => true,
        'setup_fee' => array(
            'value' => '0.00',
            'currency_code' => 'USD'
        )
    )
   
);



$ch_billing_plan = curl_init($endpoint_billing_plans);
curl_setopt($ch_billing_plan, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch_billing_plan, CURLOPT_POST, true);
curl_setopt($ch_billing_plan, CURLOPT_POSTFIELDS, json_encode($request_body_billing_plans));
curl_setopt($ch_billing_plan, CURLOPT_RETURNTRANSFER, true);
$response_create_plan = curl_exec($ch_billing_plan);
curl_close($ch_billing_plan);

$response_billing_data = json_decode($response_create_plan, true);


$plan_id = $response_billing_data['id'];

update_post_meta($wc_product_id,'_paypal_product_plan_id',$plan_id);


}
