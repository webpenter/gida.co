<?php
if ( ! class_exists( 'Homey_WooCommerce_Payment' ) ) {

    class Homey_WooCommerce_Payment {

        public function __construct() {

            add_action( 'woocommerce_remove_cart_item',  array($this, 'woo_cart_updated'), 10, 2 );
            add_action( 'wp_ajax_homey_featured_woo_pay',         array( $this, 'homey_featured_woo_pay') );

            add_action( 'wp_ajax_homey_reservation_woo_pay',         array( $this, 'homey_reservation_woo_pay') );

            add_action( 'wp_ajax_homey_instant_reservation_woo_pay',         array( $this, 'instant_reservation_woo_pay') );
            add_action( 'wp_ajax_homey_instant_hourly_reservation_woo_pay',         array( $this, 'instant_hourly_reservation_woo_pay') );

            add_action( 'woocommerce_order_status_completed',       array( $this, 'payment_complete') );
            add_action( 'woocommerce_order_status_processing',      array( $this, 'payment_complete') );
            add_action( 'woocommerce_order_status_on-hold',         array( $this, 'payment_pending' ) );

            add_action( 'woocommerce_order_status_completed',       array( $this, 'woo_commerce_order_payment_paid') );
            add_action( 'woocommerce_order_status_processing',      array( $this, 'woo_commerce_order_payment_paid') );
            add_action( 'woocommerce_order_status_on-hold',         array( $this, 'woo_commerce_order_payment_not_paid' ) );

            add_filter( 'woocommerce_cart_item_permalink','__return_false');
            add_action( 'woocommerce_before_single_product',        array( $this, 'product_redirect') );
            add_action( 'woocommerce_product_query',                array( $this, 'custom_pre_get_posts_query' ) );
        }


        function wc_get_invoice_id_using_wc_order_id($wc_order_id){
            global $wpdb;
            $tbl = $wpdb->prefix.'postmeta';
            $prepare_guery = $wpdb->prepare( "SELECT post_id FROM $tbl where meta_key ='wc_reference_order_id' and meta_value = '%s'", $wc_order_id );

            $get_values = $wpdb->get_col( $prepare_guery );
            $invoice_id = -1;

            error_log( print_r($get_values, true));

            if(isset($get_values[0])){
                $lastIndex = count($get_values)-1;
                $invoice_id = $get_values[$lastIndex];
            }

            return $invoice_id;
        }

        function woo_commerce_order_payment_paid($order_id) {
            $invoice_id = $this->wc_get_invoice_id_using_wc_order_id($order_id);
            if ($invoice_id != -1){
                update_post_meta( $invoice_id, 'invoice_payment_status', 1 );
            }
        }

        function woo_commerce_order_payment_not_paid($order_id) {
            $invoice_id = $this->wc_get_invoice_id_using_wc_order_id($order_id);
            if ($invoice_id != -1){
                update_post_meta( $invoice_id, 'invoice_payment_status', 0 );
            }
        }

        public function homeyWooCommerce() {

            if( homey_option('homey_payment_gateways', 'homey_custom_gw') == 'gw_woocommerce' ) {
                return true;
            } else {
                return false;
            }
        }

        function woo_cart_updated( $cart_item_key, $cart ) {

            $product_id = $cart->cart_contents[ $cart_item_key ]['product_id'];
            $is_woocommerce = intval( get_post_meta( $product_id, '_is_homey_woocommerce', true ) );

            if( $is_woocommerce == 1 ) {
                wp_delete_post( $product_id );
            }

        }

        function homey_featured_woo_pay() {

            $listing_id   = intval($_POST['listing_id']);
            $is_featured   = intval($_POST['is_featured']);

            $product_id  = $this->checkIfAlreadyInCart($listing_id);

            if( $product_id == 0 ) {
                $product_id = $this->homey_make_listing_featured($listing_id, $is_featured);
            }

            $cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );

            return $cart;
        }

        function homey_reservation_woo_pay() {

            $reservation_id   = intval($_POST['reservation_id']);

            $product_id  = $this->checkIfAlreadyInCart($reservation_id);

            if( $product_id == 0 ) {
                $product_id = $this->homey_make_reservation_payment($reservation_id);
            }

            $cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );

            return $cart;
        }

        function instant_reservation_woo_pay() {

            $listing_id   = intval($_POST['listing_id']);

            $product_id  = $this->checkIfAlreadyInCart($listing_id);

            if( $product_id == 0 ) {
                $product_id = $this->homey_make_instant_reservation_payment($_POST);
            }

            $cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );

            return $cart;
        }

        function instant_hourly_reservation_woo_pay() {

            $listing_id   = intval($_POST['listing_id']);

            $product_id  = $this->checkIfAlreadyInCart($listing_id);

            if( $product_id == 0 ) {
                $product_id = $this->homey_make_instant_hourly_reservation_payment($_POST);
            }

            $cart = WC()->cart->add_to_cart($product_id);

            return $cart;
        }

        function homey_make_listing_featured($listing_id, $is_featured ) {

            $current_user = wp_get_current_user();
            $userID       = get_current_user_id();
            $user_email   = $current_user->user_email;

            $listing_price = homey_option('price_featured_listing');
            $product_title = sprintf( esc_html__('Upgrade to "Featured" for Listing "%s" with id %s', 'homey-woocommerce-addon'), get_the_title($listing_id),$listing_id);

            $args = array(
                'post_content'   => '',
                'post_status'    => "publish",
                'post_title'     => $product_title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );

            $product_id = wp_insert_post( $args );


            update_post_meta( $product_id, '_is_homey_woocommerce', true );
            update_post_meta( $product_id, '_is_homey_payment_mode', 'per_listing' );
            update_post_meta( $product_id, '_virtual', 'yes' );  //no
            update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
            update_post_meta( $product_id, '_manage_stock', 'no' ); //no
            update_post_meta( $product_id, '_featured', 'no' );
            update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
            update_post_meta( $product_id, '_visibility', 'visible' );
            update_post_meta( $product_id, '_downloadable', 'no' ); //no
            update_post_meta( $product_id, '_invoice_id', $listing_id );
            update_post_meta( $product_id, '_backorders', 'no' ); //no
            update_post_meta( $product_id, '_price', $listing_price ); //''
            update_post_meta( $product_id, '_homey_listing_id', $listing_id );
            update_post_meta( $product_id, '_homey_is_featured', $is_featured );
            update_post_meta( $product_id, '_homey_user_id', $userID );
            update_post_meta( $product_id, '_homey_user_email', $user_email );

            update_post_meta( $product_id, '_wc_min_qty_product', 1 );
            update_post_meta( $product_id, '_wc_max_qty_product', 1 );
            $data_variation = [
                'types' => [
                    'name'         => 'types',
                    'value'        => 'service',
                    'position'     => 0,
                    'is_visible'   => 1,
                    'is_variation' => 1,
                    'is_taxonomy'  => 1
                ]
            ];
            update_post_meta( $product_id, '_product_attributes', $data_variation );
            update_post_meta( $product_id, '_product_version', '4.2.0' );

            return $product_id;

        }

        function homey_make_reservation_payment( $reservation_id ) {

            $blogInfo = esc_url( home_url('/') );
            $current_user = wp_get_current_user();
            $userID       = get_current_user_id();
            $user_email   = $current_user->user_email;

            $reservation_status = get_post_meta($reservation_id, 'reservation_status', true);

            if( $reservation_status != 'available') {
                return false;
            }

            $reservation_meta = get_post_meta($reservation_id, 'reservation_meta', true);
            $extra_options = get_post_meta($reservation_id, 'extra_options', true);
            $is_hourly = get_post_meta($reservation_id, 'is_hourly', true);

            $listing_id     = intval($reservation_meta['listing_id']);

            $upfront_payment  = floatval( $reservation_meta['upfront'] );

            $payment_description    =  esc_html__('Reservation payment on ','homey-woocommerce-addon').$blogInfo;

            $extra_expenses = homey_get_extra_expenses($reservation_id);
            $extra_discount = homey_get_extra_discount($reservation_id);

            if(!empty($extra_expenses)) {
                $expenses_total_price = $extra_expenses['expenses_total_price'];
                $upfront_payment = $upfront_payment + $expenses_total_price;
            }

            if(!empty($extra_discount)) {
                $discount_total_price = $extra_discount['discount_total_price'];
                $upfront_payment = $upfront_payment - $discount_total_price;
            }

            $total_price =  $upfront_payment;

            $product_title = sprintf( esc_html__('Reservation ID "%s" Listing ID %s', 'homey-woocommerce-addon'), $reservation_id, $listing_id);

            $args = array(
                'post_content'   => '',
                'post_status'    => "pending",
                'post_title'     => $product_title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );

            $product_id = wp_insert_post( $args );


            update_post_meta( $product_id, '_is_homey_woocommerce', true );
            update_post_meta( $product_id, '_is_homey_payment_mode', 'reservation_payment' );
            update_post_meta( $product_id, '_is_homey_is_hourly', $is_hourly );
            update_post_meta( $product_id, '_virtual', 'yes' );  //no
            update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
            update_post_meta( $product_id, '_manage_stock', 'no' ); //no
            update_post_meta( $product_id, '_featured', 'no' );
            update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
            update_post_meta( $product_id, '_visibility', 'visible' );
            update_post_meta( $product_id, '_downloadable', 'no' ); //no
            update_post_meta( $product_id, '_invoice_id', $listing_id );
            update_post_meta( $product_id, '_backorders', 'no' ); //no
            update_post_meta( $product_id, '_price', $total_price ); //''
            update_post_meta( $product_id, '_homey_listing_id', $listing_id );
            update_post_meta( $product_id, '_homey_reservation_id', $reservation_id );
            update_post_meta( $product_id, '_homey_user_id', $userID );
            update_post_meta( $product_id, '_homey_user_email', $user_email );

            update_post_meta( $product_id, '_wc_min_qty_product', 1 );
            update_post_meta( $product_id, '_wc_max_qty_product', 1 );
            $data_variation = [
                'types' => [
                    'name'         => 'types',
                    'value'        => 'service',
                    'position'     => 0,
                    'is_visible'   => 1,
                    'is_variation' => 1,
                    'is_taxonomy'  => 1
                ]
            ];
            update_post_meta( $product_id, '_product_attributes', $data_variation );
            update_post_meta( $product_id, '_product_version', '4.2.0' );

            return $product_id;

        }

        function homey_make_instant_reservation_payment( $post ) {

            $blogInfo = esc_url( home_url('/') );
            $current_user = wp_get_current_user();
            $userID       = get_current_user_id();
            $user_email   = $current_user->user_email;

            $listing_id     = intval($post['listing_id']);
            $check_in_date  = wp_kses ($post['check_in'], $allowded_html);
            $check_out_date = wp_kses ($post['check_out'], $allowded_html);
            $renter_message = wp_kses ($post['renter_message'], $allowded_html);
            $guests         = intval($post['guests']);
            $extra_options  = $post['extra_options'];

            $check_availability = check_booking_availability($check_in_date, $check_out_date, $listing_id, $guests);
            $is_available = $check_availability['success'];
            $check_message = $check_availability['message'];

            if(!$is_available) {

                return false;


            } else {
                $product_title = sprintf( esc_html__('Listing ID %s', 'homey-woocommerce-addon'), $listing_id);


                $booking_type = homey_booking_type_by_id($listing_id);

                if( $booking_type == 'per_week' ) {
                    $prices_array = homey_get_weekly_prices($check_in_date, $check_out_date, $listing_id, $guests, $extra_options);
                } else if( $booking_type == 'per_month' ) {
                    $prices_array = homey_get_monthly_prices($check_in_date, $check_out_date, $listing_id, $guests, $extra_options);
                } else {
                    $prices_array = homey_get_prices($check_in_date, $check_out_date, $listing_id, $guests, $extra_options);
                }
                $upfront_payment  =  floatval( $prices_array['upfront_payment'] );

                //$upfront_payment = round($upfront_payment, 2);

                $total_price = $upfront_payment;

                $payment_description    =  esc_html__('Reservation payment on ','homey').$blogInfo;

                $args = array(
                    'post_content'   => '',
                    'post_status'    => "publish",
                    'post_title'     => $product_title,
                    'post_parent'    => '',
                    'post_type'      => "product",
                    'comment_status' => 'closed'
                );

                $product_id = wp_insert_post( $args );


                update_post_meta( $product_id, '_is_homey_woocommerce', true );
                update_post_meta( $product_id, '_is_homey_payment_mode', 'instant_reservation_payment' );
                update_post_meta( $product_id, '_is_homey_is_hourly', 'no' );
                update_post_meta( $product_id, '_virtual', 'yes' );  //no
                update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
                update_post_meta( $product_id, '_manage_stock', 'no' ); //no
                update_post_meta( $product_id, '_featured', 'no' );
                update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
                update_post_meta( $product_id, '_visibility', 'visible' );
                update_post_meta( $product_id, '_downloadable', 'no' ); //no
                update_post_meta( $product_id, '_invoice_id', $listing_id );
                update_post_meta( $product_id, '_backorders', 'no' ); //no
                update_post_meta( $product_id, '_homey_check_in_date', $check_in_date );
                update_post_meta( $product_id, '_homey_check_out_date', $check_out_date );
                update_post_meta( $product_id, '_homey_guests', $guests );
                update_post_meta( $product_id, '_homey_extra_options', $extra_options );
                update_post_meta( $product_id, '_homey_renter_message', $renter_message );
                update_post_meta( $product_id, '_price', $total_price ); //''
                update_post_meta( $product_id, '_homey_listing_id', $listing_id );
                update_post_meta( $product_id, '_homey_user_id', $userID );
                update_post_meta( $product_id, '_homey_user_email', $user_email );

                update_post_meta( $product_id, '_wc_min_qty_product', 1 );
                update_post_meta( $product_id, '_wc_max_qty_product', 1 );
                $data_variation = [
                    'types' => [
                        'name'         => 'types',
                        'value'        => 'service',
                        'position'     => 0,
                        'is_visible'   => 1,
                        'is_variation' => 1,
                        'is_taxonomy'  => 1
                    ]
                ];
                update_post_meta( $product_id, '_product_attributes', $data_variation );
                update_post_meta( $product_id, '_product_version', '4.2.0' );

                return $product_id;
            }

        }

        function homey_make_instant_hourly_reservation_payment( $post ) {

            $blogInfo = esc_url( home_url('/') );
            $current_user = wp_get_current_user();
            $userID       = get_current_user_id();
            $user_email   = $current_user->user_email;

            $listing_id     = intval($post['listing_id']);
            $check_in_date  = wp_kses ($post['check_in'], $allowded_html);
            $renter_message = wp_kses ($post['renter_message'], $allowded_html);
            $guests         = intval($post['guests']);
            $extra_options  = $post['extra_options'];

            $check_in_hour  = wp_kses ($post['check_in_hour'], $allowded_html);
            $check_out_hour = wp_kses ($post['check_out_hour'], $allowded_html);
            $start_hour = wp_kses ($post['start_hour'], $allowded_html);
            $end_hour = wp_kses ($post['end_hour'], $allowded_html);

            $check_availability = check_hourly_booking_availability($check_in_date, $check_in_hour, $check_out_hour, $start_hour, $end_hour, $listing_id, $guests);


            $is_available = $check_availability['success'];
            $check_message = $check_availability['message'];

            if(!$is_available) {

                return false;


            } else {

                $product_title = sprintf( esc_html__('Listing ID %s', 'homey-woocommerce-addon'), $listing_id);

                $prices_array = homey_get_hourly_prices($check_in_hour, $check_out_hour, $listing_id, $guests, $extra_options);

                $upfront_payment  = floatval( $prices_array['upfront_payment'] );

                $total_price = $upfront_payment;

                $payment_description    =  esc_html__('Reservation payment on ','homey').$blogInfo;

                $args = array(
                    'post_content'   => '',
                    'post_status'    => "publish",
                    'post_title'     => $product_title,
                    'post_parent'    => '',
                    'post_type'      => "product",
                    'comment_status' => 'closed'
                );

                $product_id = wp_insert_post( $args );


                update_post_meta( $product_id, '_is_homey_woocommerce', true );
                update_post_meta( $product_id, '_is_homey_payment_mode', 'instant_hourly_reservation_payment' );
                update_post_meta( $product_id, '_is_homey_is_hourly', 'yes' );
                update_post_meta( $product_id, '_virtual', 'yes' );  //no
                update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
                update_post_meta( $product_id, '_manage_stock', 'no' ); //no
                update_post_meta( $product_id, '_featured', 'no' );
                update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
                update_post_meta( $product_id, '_visibility', 'visible' );
                update_post_meta( $product_id, '_downloadable', 'no' ); //no
                update_post_meta( $product_id, '_invoice_id', $listing_id );
                update_post_meta( $product_id, '_backorders', 'no' ); //no

                update_post_meta( $product_id, '_homey_check_in_date', $check_in_date );
                update_post_meta( $product_id, '_homey_check_in_hour', $check_in_hour );
                update_post_meta( $product_id, '_homey_check_out_hour', $check_out_hour );
                update_post_meta( $product_id, '_homey_start_hour', $start_hour );
                update_post_meta( $product_id, '_homey_end_hour', $end_hour );

                update_post_meta( $product_id, '_homey_guests', $guests );
                update_post_meta( $product_id, '_homey_extra_options', $extra_options );
                update_post_meta( $product_id, '_homey_renter_message', $renter_message );
                update_post_meta( $product_id, '_price', $total_price ); //''
                update_post_meta( $product_id, '_homey_listing_id', $listing_id );
                update_post_meta( $product_id, '_homey_user_id', $userID );
                update_post_meta( $product_id, '_homey_user_email', $user_email );

                update_post_meta( $product_id, '_wc_min_qty_product', 1 );
                update_post_meta( $product_id, '_wc_max_qty_product', 1 );
                $data_variation = [
                    'types' => [
                        'name'         => 'types',
                        'value'        => 'service',
                        'position'     => 0,
                        'is_visible'   => 1,
                        'is_variation' => 1,
                        'is_taxonomy'  => 1
                    ]
                ];
                update_post_meta( $product_id, '_product_attributes', $data_variation );
                update_post_meta( $product_id, '_product_version', '4.2.0' );

                return $product_id;
            }

        }

        function checkIfAlreadyInCart($invoice_no) {

            $product_id = 0;

            $args = array(
                'post_type'      => 'product',
                'meta_key'       => '_invoice_id',
                'meta_value'     => $invoice_no,
                'posts_per_page' => 1
            );

            $qry = new WP_Query( $args );

            if ( $qry->have_posts() ):
                while ( $qry->have_posts() ): $qry->the_post();
                    $product_id =  get_the_ID();
                endwhile;
            endif;

            return $product_id;
        }

        function payment_pending( $order_id ) {
            $order    = wc_get_order( $order_id );
            $products = $order->get_items();

            foreach( $products as $product ) {

                $product_id = $product['product_id'];
                $order_title = $product['name'];

                $is_woocommerce = intval( get_post_meta( $product_id, '_is_homey_woocommerce', true ) );
                $payment_mode 	= get_post_meta( $product_id, '_is_homey_payment_mode', true );

                if( $payment_mode == 'per_listing' ) {

                    $this->per_listing_payment_completed( $product_id, $order, $order_title, $order_id, 'pending' );

                } else if( $payment_mode == 'reservation_payment' ) {
                    $this->reservation_payment( $product_id, $order, $order_title, $order_id, 'pending' );

                } else if( $payment_mode == 'instant_reservation_payment' ) {
                    $this->reservation_instant_payment( $product_id, $order, $order_title, $order_id , 'pending');
                } else if( $payment_mode == 'instant_hourly_reservation_payment' ) {
                    $this->reservation_instant_hourly_payment( $product_id, $order, $order_title, $order_id , 'pending');
                }

                if( $is_woocommerce == 1 ) {
                    wp_delete_post( $product_id );
                }

            }

        }

        function payment_complete( $order_id ) {
            $order    = wc_get_order( $order_id );
            $products = $order->get_items();

            foreach( $products as $product ) {

                $product_id = $product['product_id'];
                $order_title = $product['name'];

                $is_woocommerce = intval( get_post_meta( $product_id, '_is_homey_woocommerce', true ) );
                $payment_mode 	= get_post_meta( $product_id, '_is_homey_payment_mode', true );

                if( $payment_mode == 'per_listing' ) {

                    $this->per_listing_payment_completed( $product_id, $order, $order_title, $order_id );

                } else if( $payment_mode == 'reservation_payment' ) {
                    $this->reservation_payment( $product_id, $order, $order_title, $order_id );

                } else if( $payment_mode == 'instant_reservation_payment' ) {
                    $this->reservation_instant_payment( $product_id, $order, $order_title, $order_id );
                } else if( $payment_mode == 'instant_hourly_reservation_payment' ) {
                    $this->reservation_instant_hourly_payment( $product_id, $order, $order_title, $order_id );
                }

                if( $is_woocommerce == 1 ) {
                    wp_delete_post( $product_id );
                }

            }

        }

        function per_listing_payment_completed( $product_id, $woo_order, $order_title, $order_id, $woo_payment_status=1 ) {

            $admin_email  =  get_bloginfo('admin_email');
            $payment_method_title = $woo_order->get_payment_method_title();

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            $is_featured = get_post_meta( $product_id, '_homey_is_featured', true );
            $listing_id = intval( get_post_meta( $product_id, '_homey_listing_id', true ) );
            $userID = intval( get_post_meta( $product_id, '_homey_user_id', true ) );
            $user_email = get_post_meta( $product_id, '_homey_user_email', true );

            if( $is_featured == 1 ) {

                update_post_meta( $listing_id, 'homey_featured', 1 );
                update_post_meta( $listing_id, 'homey_featured_datetime', $date );

                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $order_id,
                );

                /*
                 * Send email
                 * */
                homey_email_composer( $user_email, 'featured_submission_listing', $args );
                homey_email_composer( $admin_email, 'admin_featured_submission_listing', $args );

            }
        }

        function reservation_payment( $product_id, $woo_order, $order_title, $order_id, $woo_payment_status = 'booked' ) {

            $user_email = get_post_meta( $product_id, '_homey_user_email', true );
            $admin_email  =  get_bloginfo('admin_email');
            $payment_method_title = $woo_order->get_payment_method_title();

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            $is_hourly = get_post_meta( $product_id, '_is_homey_is_hourly', true );
            $reservation_id = get_post_meta( $product_id, '_homey_reservation_id', true );
            $listing_id = get_post_meta( $product_id, '_homey_listing_id', true );
            $userID = get_post_meta( $product_id, '_homey_user_id', true );


            if($is_hourly  == 'yes') {

                //Book hours
                $booked_days_array = homey_make_hours_booked($listing_id, $reservation_id);
                update_post_meta($listing_id, 'reservation_booked_hours', $booked_days_array);

                //Remove Pending Hours
                $pending_dates_array = homey_remove_booking_pending_hours($listing_id, $reservation_id);
                update_post_meta($listing_id, 'reservation_pending_hours', $pending_dates_array);

            } else {
                //Book dates
                $booked_days_array = homey_make_days_booked($listing_id, $reservation_id);
                update_post_meta($listing_id, 'reservation_dates', $booked_days_array);

                //Remove Pending Dates
                $pending_dates_array = homey_remove_booking_pending_days($listing_id, $reservation_id);
                update_post_meta($listing_id, 'reservation_pending_dates', $pending_dates_array);
            }

            // Update reservation status
            update_post_meta( $reservation_id, 'reservation_status', $woo_payment_status );

            // Invoice generating for paid amount
            $invoiceID = homey_generate_invoice( 'reservation','one_time', $reservation_id, $date, $userID, 0, 0, '', 'Self' );
            update_post_meta( $invoiceID, 'invoice_payment_status', 1 );
            //save for woocommerce invoice ID
            update_post_meta( $product_id, '_homey_invoice_reference_id', $invoiceID );

            //Add host earning history
            homey_add_earning($reservation_id);

            $listing_owner = get_post_meta($reservation_id, 'listing_owner', true);
            $owner = homey_usermeta($listing_owner);
            if(isset($owner['email'])){
                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $order_id,
                    'reservation_detail_url' => reservation_detail_link($reservation_id)
                );

                /*
                 * Send email
                 * */
                homey_email_composer( $owner['email'], 'guest_sent_payment_reservation', $args );
            }

        }

        function reservation_instant_payment( $product_id, $woo_order, $order_title, $order_id, $woo_payment_status = 1 ) {

            $admin_email  =  get_bloginfo('admin_email');
            $payment_method_title = $woo_order->get_payment_method_title();

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            $reservation_id = get_post_meta( $product_id, '_homey_reservation_id', true );
            $listing_id = get_post_meta( $product_id, '_homey_listing_id', true );

            $listing_id = get_post_meta( $product_id, '_homey_listing_id', true );
            $check_in_date = get_post_meta( $product_id, '_homey_check_in_date', true );
            $check_out_date = get_post_meta( $product_id, '_homey_check_out_date', true );
            $guests = get_post_meta( $product_id, '_homey_guests', true );
            $extra_options = get_post_meta( $product_id, '_homey_extra_options', true );
            $renter_message = get_post_meta( $product_id, '_homey_renter_message', true );
            $userID = get_post_meta( $product_id, '_homey_user_id', true );

            $reservation_id = homey_add_instance_booking($listing_id, $check_in_date, $check_out_date, $guests, $renter_message, $extra_options);

            // Invoice generating for paid amount
            $invoiceID = homey_generate_invoice( 'reservation','one_time', $reservation_id, $date, $userID, 0, 0, '', 'Self instant' );
            update_post_meta( $invoiceID, 'invoice_payment_status', $woo_payment_status );
            //save for woocommerce invoice ID
            update_post_meta( $product_id, '_homey_invoice_reference_id', $invoiceID );

            //Create messages thread
            do_action('homey_create_messages_thread', $renter_message, $reservation_id);

            //Add host earning history
            homey_add_earning($reservation_id);

            $listing_owner = get_post_meta($reservation_id, 'listing_owner', true);
            $owner = homey_usermeta($listing_owner);
            if(isset($owner['email'])){
                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $order_id,
                    'reservation_detail_url' => reservation_detail_link($reservation_id)
                );

                /*
                 * Send email
                 * */
                homey_email_composer( $owner['email'], 'guest_sent_payment_reservation', $args );
            }
        }

        function reservation_instant_hourly_payment( $product_id, $woo_order, $order_title, $order_id, $woo_payment_status=1 ) {

            $payment_method_title = $woo_order->get_payment_method_title();

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            $listing_id = get_post_meta( $product_id, '_homey_listing_id', true );
            $check_in_date = get_post_meta( $product_id, '_homey_check_in_date', true );
            $check_in_hour = get_post_meta( $product_id, '_homey_check_in_hour', true );
            $check_out_hour = get_post_meta( $product_id, '_homey_check_out_hour', true );
            $start_hour = get_post_meta( $product_id, '_homey_start_hour', true );
            $end_hour = get_post_meta( $product_id, '_homey_end_hour', true );

            $guests = get_post_meta( $product_id, '_homey_guests', true );
            $extra_options = get_post_meta( $product_id, '_homey_extra_options', true );
            $renter_message = get_post_meta( $product_id, '_homey_renter_message', true );
            $userID = get_post_meta( $product_id, '_homey_user_id', true );

            $reservation_id = homey_add_hourly_instance_booking($listing_id, $check_in_date, $check_in_hour, $check_out_hour, $start_hour, $end_hour, $guests, $renter_message, $extra_options);

            // Invoice generating for paid amount
            $invoiceID = homey_generate_invoice( 'reservation','one_time', $reservation_id, $date, $userID, 0, 0, '', $payment_method_title );
            update_post_meta( $invoiceID, 'invoice_payment_status', $woo_payment_status );
            update_post_meta( $invoiceID, 'wc_reference_order_id', $order_id );
            update_post_meta( $invoiceID, 'wc_reservation_reference_id', $reservation_id );
            update_post_meta( $invoiceID, 'wc_payment_method_title', $payment_method_title );
            //save for woocommerce invoice ID
            update_post_meta( $product_id, '_homey_invoice_reference_id', $invoiceID );

            //Create messages thread
            do_action('homey_create_messages_thread', $renter_message, $reservation_id);

            //Add host earning history
            homey_add_earning($reservation_id);

            $listing_owner = get_post_meta($reservation_id, 'listing_owner', true);
            $owner = homey_usermeta($listing_owner);
            if(isset($owner['email'])){
                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no' =>  $order_id,
                    'reservation_detail_url' => reservation_detail_link($reservation_id)
                );

                /*
                 * Send email
                 * */
                homey_email_composer( $owner['email'], 'guest_sent_payment_reservation', $args );
            }

        }



        function custom_pre_get_posts_query( $query ) {
            $meta_query = (array) $query->get( 'meta_query' );
            $meta_query[] = array(
                'meta_key'      => '_is_homey_woocommerce',
                'meta_compare'  => 'NOT EXISTS',
                'value'         => ''
            );
            $query->set( 'meta_query', $meta_query );
        }

        function product_redirect() {

            $is_homey_custom = get_post_meta( get_the_ID(), '_is_homey_woocommerce', true );

            if( $is_homey_custom == 1 ) {
                wp_redirect( home_url(), 301 );
                exit();
            }
        }

    }

    add_action('woocommerce_review_order_before_cart_contents', 'homey_order_detail_before_cart_detail');
    function homey_order_detail_before_cart_detail(){

    $listing_id = isset($_GET['listing_id']) ? $_GET['listing_id'] : '';
    $guests = isset($_GET['guests']) ? $_GET['guests'] : '';
    $check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
    $check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
    if($check_in == ''){
        return '';
    }
    $check_in_unix = strtotime($check_in);
    $check_out_unix = strtotime($check_out);

    $booking_hide_fields = homey_option('booking_hide_fields');

    $check_in_date = date("d/m/Y", $check_in_unix);
    $check_out_date = date("d/m/Y", $check_out_unix);

    $room_type = homey_taxonomy_simple_by_ID('room_type', $listing_id);
    $listing_type = homey_taxonomy_simple_by_ID('listing_type', $listing_id);
    $slash = '';
    if(!empty($room_type) && !empty($listing_type)) {
        $slash = '/';
    }

    $guests_label = homey_option('srh_guest_label');
    if($guests > 1) {
        $guests_label = homey_option('srh_guests_label');
    }

    $guests_label .= ' '.$guests;

    $rating = homey_option('rating');
    $total_rating = get_post_meta( $listing_id, 'listing_total_rating', true );
echo '
<div class="booking-sidebar">
    <div class="block">
        <div class="booking-property clearfix">
            <div class="booking-property-info">
                <h3>';echo get_the_title($listing_id); echo '</h3>
                <div>'; echo esc_attr($room_type).' '.$slash.' '.esc_attr($listing_type); echo '</div>';
                if($rating && ($total_rating != '' && $total_rating != 0 ) ) {
                    echo '<div class="list-inline rating">';
                        echo homey_get_review_stars($total_rating, false, true);
                    echo '</div>';
                }
            echo '</div>
            <div class="booking-property-img">';
                if( has_post_thumbnail( $listing_id ) ) {
                    echo get_the_post_thumbnail( $listing_id, 'homey-listing-thumb',  array('class' => 'img-responsive' ) );
                }else{
                    homey_image_placeholder( 'homey-listing-thumb' );
                }
            echo '</div>
        </div>
        <div class="block-body">
            <div class="booking-data clearfix">
                <div class="booking-data-block booking-data-arrive">
                    <div class="booking-data-top">
                        <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>'; echo esc_attr(homey_option('srh_arrive_label'));
                    echo '</div>
                    <div class="booking-data-bottom">';
                        if(!empty($check_in_date)) { echo esc_attr($check_in_date); }
                    echo '</div>
                </div>
                <div class="booking-data-block booking-data-depart">
                    <div class="booking-data-top">
                        <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>'; echo esc_attr(homey_option('srh_depart_label'));
                    echo '</div>
                    <div class="booking-data-bottom">';
                        if(!empty($check_out_date)) { echo esc_attr($check_out_date); }
                    echo '</div>
                </div>';
                if($booking_hide_fields['guests'] != 1) {
                    echo '<div class="booking-data-block booking-data-guests">
                        <div class="booking-data-top">
                            <i class="fa fa-user" aria-hidden="true"></i>'; echo esc_attr(homey_option('srh_guests_label'));
                        echo '</div>
                        <div class="booking-data-bottom">';
                            echo esc_attr($guests_label);
                        echo '</div>
                    </div>';
                 }
            echo '</div>';
        echo '</div><!-- block-body -->
    </div><!-- block -->
</div><!-- booking-sidebar -->
<div style="clear: both;"></div>
';
    }

    new Homey_WooCommerce_Payment();

}
