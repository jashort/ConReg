<?php

/* Optional include a different badge stylesheet depending on the client's IP address */

$PRINTERS = array(
    "default" => "badge.css",
    "10.0.2.2" => "newbadge.css"
);


/**
 * Return the name of the badge stylesheet in the array $PRINTERS based on
 *
 * @return string
 */
function getBadgeStylesheet() {
    global $PRINTERS;

    if (array_key_exists($_SERVER['REMOTE_ADDR'], $PRINTERS)) {
        return $PRINTERS[$_SERVER['REMOTE_ADDR']];
    } else {
        if (array_key_exists("default", $PRINTERS)) {
            return $PRINTERS["default"];
        } else {
            return "badge.css";
        }
    }
}

?>