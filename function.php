				///////////////////////////////////////////////////////////// NUEVO BLOQUE CAMPOS CONDICIONALES HORARIO ///////////////////////////////////////////////////////////////




            ///////////////////////////////////////////////////////////// LLAMO UN NUEVO PERSONALIZADO RADIO BUTTON//////////////////////////////////////////////////////////////////
add_action('woocommerce_after_order_notes', 'custom_checkout_field');
function custom_checkout_field( $checkout ) {

    echo '<div id="custom_checkout_field">
    <h3>'.__('Hora de envío').'</h3>';    ?>
    <style>label.radio { display:inline-block;          padding: 7px 7px;         font-family:Arial;     font-size:16px;              }
label.daypart     
    {
        visibility: hidden;
            display: none;
    }
    select
    {
                    display: none;

        -webkit-appearance: none;
        -moz-appearance: none;      
        appearance: none;
        padding: 2px 30px 2px 2px;
        /*border: none; - if you want the border removed*/
    }
</style>
    <?php

    ////////////////////////////////////////////////////////////////////// PERSONALIZO LOS CAMBIOS DEL BOTON /////////////////////////////////////////////////////////////////////
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
        'Ningun horario'   => __( '', 'wps' ),
        '10 a 11 am'   => __( '10 a 11 am', 'wps' ),
          '11 a 12 am' => __( '11 a 12 am', 'wps' ),
      )
 ),
  $checkout->get_value( 'daypart2' ));

woocommerce_form_field('daypart', array(

'type'          => 'select',
      'class'         => array( 'wps-drop' ),
      'options'       => array(
        'Ningun horario'   => __( '', 'wps' ),
        '10 a 11 am'   => __( '10 a 11 am', 'wps' ),
          '11 a 12 am' => __( '11 a 12 am', 'wps' ),
          '15 a 16 pm' => __( '15 a 16 pm', 'wps' ),
          '16 a 17 pm'   => __( '16 a 17 pm', 'wps' )
      )
 ),
  $checkout->get_value( 'daypart' ));
echo "</div>";
   ///////////////////////////////////////////////////////// LLAMO UNA FUNCIÓN DE JQUERY QUE DESPLIEGUE HORARIOS SEGÚN SI/NO /////////////////////////////////////////////////////////////////
    ?>
    <script type="text/javascript">
        jQuery(function($){
            $("input[name=shipping_type]").on("change",function(){
                if($("#shipping_type_shipping_1").is(":checked")) {
                    $("#daypart2").show();
                } else {
                    $("#daypart2").hide();
                    $("#daypart").hide();
                }
        
                if($("#shipping_type_shipping_3").is(":checked")) {
                    $("#daypart").show();
                } else {
                    $("#daypart").hide();
                    $("#daypart").hide();
                }
            });
        });
    </script>

<?php
}
add_action('woocommerce_checkout_process', 'customised_checkout_field_process');

function customised_checkout_field_process()

{

////////////////////////////////////////////////////////////////// ESTO ES UNA PRUEBA QUE ARROJA ERROR SI ALGUNO DE LOS CAMPOS NO ESTA LLENO ///////////////////////////////////////////////////

 if (!$_POST['daypart'])
         wc_add_notice( '<strong>Seleccionar hora de envío</strong> ' . __( 'es un campo requerido.', 'woocommerce' ), 'error' );
}

/**

//////////////////////////////////////////////////////////////////////////////// ACTUALIZO EL VALOR DADO EN EL CARRITO //////////////////////////////////////////////////////////////////////////////////

*/

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id)

{

$safe_id = $_POST['daypart2'] || $_POST['daypart'];

if (!empty($_POST['daypart2']) || ($_POST['daypart2'])) {

update_post_meta($order_id, 'Some Field',sanitize_text_field($safe_id));
}};

 
