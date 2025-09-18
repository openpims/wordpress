<?php

/**
 * @package OpenPIMS
 */

/*
Plugin Name: OpenPIMS
Plugin URI: https://openpims.de/
Description: OpenPIMS plugin
Version: 0.0.1
Author: Stefan Böck
Author URI: https://stefan.boeck.name
License: GPLv2 or later
*/

$show = true;

$data = [];

$url_data = parse_url( home_url() );
$host = $url_data['host'];
$param = "?url=https://" . $host . "/openpims.json";

//Fallback Host
$openpims_host = 'openpims.de';

$headers = array_change_key_case(getallheaders(), CASE_LOWER);
if(array_key_exists('x-openpims', $headers)) {

    $url = $headers['x-openpims'];

    if($url!='') {

        //extract host from user-url
        $url_user_data = parse_url( $url );
        $user_host = $url_user_data['host'];
        $parts = explode('.', $user_host);
        array_shift($parts);
        $openpims_host = implode('.', $parts);

        $show = false;
        $request = wp_remote_get($url.$param);
    }

}
?>
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 10px solid orange;
        width: 80%; /* Could be more or less, depending on screen size */
    }

    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<!-- Trigger/Open The Modal -->
<!--button id="myBtn"></button-->

<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <!--span class="close">&times;</span-->
        <p>
            Wir nutzen einen externen Service, der Dir ermöglicht, die Verwaltung von von uns eingesetzten Cookies und anderen Tracking-Tools zu steuern.
        </p>
        <p>
            Bei <a href="https://<?php echo esc_url($openpims_host); ?>/register?url=<?php echo esc_url($host); ?>" target="_blank">OpenPIMS</a> kannst Du einmalig alle benötigten Einstellungen vornehmen und dann hierher zurückkehren. Wir freuen uns bereits auf Deinen nächsten Besuch.
        </p>
        <p>
            Sobald dies erledigt ist, wird diese Benachrichtigung nicht mehr für Dich sichtbar sein.
        </p>
        <center>
            <a href="https://<?php echo esc_url($openpims_host); ?>/register?url=<?php echo esc_url($host); ?>" target="_blank">
                🍪 OpenPIMS
            </a>
        </center>
    </div>

</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    //var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    //btn.onclick = function() {
        //modal.style.display = "block";
    //}

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        //modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            //modal.style.display = "none";
        }
    }
</script>
<?php
if( !is_admin() ) {
    //if (!in_array('marketing', $data)) {
    if ($show) {
        //var_dump($data);
    ?>
    <script>
        modal.style.display = "block";
    </script>
<?php
    }
}
