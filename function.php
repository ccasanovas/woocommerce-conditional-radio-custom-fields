add_action('woocommerce_after_order_notes', 'custom_checkout_field');
function custom_checkout_field( $checkout ) {
    echo '<div id="custom_checkout_field">
                
    <h3>'.__('Hora de envío').'</h3>';    ?>
    <style>label.radio { display:inline-block;          
                padding: 7px 7px;         
                font-family:Arial;     
                font-size:16px;              
                }
            label.daypart     
                {
                    visibility: hidden;
                        display: none;
                }
            .daypart
                {
                    display: none;
                    visibility: hidden;
                }
            .daypart2
                {
                    display: none;
                    visibility: hidden;
                }
</style>
    <?php
    //////// PERSONALIZO LOS CAMBIOS DEL BOTON 
    woocommerce_form_field( 'shipping_type', array(
        'type' => 'radio',
        'class' => array( '.radio' ),
        'label' => __('Eligió un fin de semana?'),
        'required' => true ,
        'options' => array(
            'shipping_1' => __('Si'),
            'shipping_3' => __('No'),
        ),
    ));

    woocommerce_form_field('daypart2', array(
    'type'          => 'select',
      'class'         => array( 'wps-drop' ),
      'options'       => array(
        'No seleccionado'   => __( '' ),
        '10 a 11am'   => __( '10 a 11 am', '10A11AM' ),
          '11 a 12 am' => __( '11 a 12 am', '11A12AM' ),
      )
 ),
  $checkout->get_value( 'daypart2' ));
woocommerce_form_field('daypart', array(
'type'          => 'select', 
      'class'         => array( 'wps-drop' ),
      'options'       => array(
        'No seleccionado'   => __( '' ),
        '10 a 11 am'   => __( '10 a 11 am', '10A11AM' ),
          '11 a 12 am' => __( '11 a 12 am', '11A12AM' ),
          '15 a 16 pm' => __( '15 a 16 pm', '15A16AM' ),
          '16 a 17 pm'   => __( '16 a 17 pm', '16A17AM' )
      )
 ),
  $checkout->get_value( 'daypart' ));
echo "</div>";
   ///// LLAMO UNA FUNCIÓN DE JQUERY QUE DESPLIEGUE HORARIOS SEGÚN SI/NO 
    ?>
    <script type="text/javascript">
        jQuery(function($){
            $("input[name=shipping_type]").click(function(){
                if($("#shipping_type_shipping_1").is(':checked')){
                    $("#daypart2").css('display','block');
                    $("#daypart").css('display','none');
                } 
                if($("#shipping_type_shipping_3").is(':checked')){
                    $("#daypart").css('display','block');
                    $("#daypart2").css('display','none');
                }
                   

            })
            if(!$("input[name=shipping_type]").is(':checked')){
                    $("#daypart2").css('display','none');
                    $("#daypart").css('display','none');
                } ;
        });


    </script>


<?php
}

/**
/// ACTUALIZO EL VALOR DADO EN EL CARRITO 
*/
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta1');
function custom_checkout_field_update_order_meta1($order_id)
{
if (!empty($_POST['daypart'])) {
update_post_meta($order_id, 'Hora de envío día de semana seleccionado',sanitize_text_field($_POST['daypart']));
}};

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta2');
function custom_checkout_field_update_order_meta2($order_id)
{
if (!empty($_POST['daypart2'])) {
update_post_meta($order_id, 'Hora de envío fín de semana seleccionado',sanitize_text_field($_POST['daypart2']));
}};

 
add_filter( 'woocommerce_order_details_after_order_table', 'add_delivery_date_to_order_received_page1', 10 , 2 );
function add_delivery_date_to_order_received_page1 ( $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }
    $delivery_hour = get_post_meta( $order_id, 'Hora de envío día de semana seleccionado', true );
    
    if ( '' != $delivery_hour ) {
        echo '<p><strong>' . __( 'Hora de envío día de semana seleccionado', 'add_extra_fields' ) . ':</strong> ' . $delivery_hour;
    }
}

add_filter( 'woocommerce_order_details_after_order_table', 'add_delivery_date_to_order_received_page2', 10 , 3 );
function add_delivery_date_to_order_received_page2 ( $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }
    $delivery_hour1 = get_post_meta( $order_id, 'Hora de envío fín de semana seleccionado', true );
    
    if ( '' != $delivery_hour1 ) {
        echo '<p><strong>' . __( 'Hora de envío fín de semana seleccionado', 'add_extra_fields' ) . ':</strong> ' . $delivery_hour1;
    }
}

add_filter( 'woocommerce_email_order_meta_fields', 'add_delivery_date_to_emails1' , 10, 4 );
function add_delivery_date_to_emails1 ( $fields, $sent_to_admin, $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }
    $delivery_hour = get_post_meta( $order_id, 'Hora de envío día de semana seleccionado', true );
    if ( '' != $delivery_hour ) {
        $fields[ 'daypart' ] = array(
        'label' => __( 'Hora de envío día de semana seleccionado', 'add_extra_fields' ),
        'value' => $delivery_hour,
        );
     }
    return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'add_delivery_date_to_emails2' , 10, 5 );
function add_delivery_date_to_emails2 ( $fields, $sent_to_admin, $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }
    $delivery_hour1 = get_post_meta( $order_id, 'Hora de envío fín de semana seleccionado', true );
    if ( '' != $delivery_hour1 ) {
        $fields[ 'daypart2' ] = array(
        'label' => __( 'Hora de envío fín de semana seleccionado', 'add_extra_fields' ),
        'value' => $delivery_hour1,
        );
     }
    return $fields;
}
