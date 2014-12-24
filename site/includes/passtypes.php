<?php
/*
 * Pass types
 */


/**
 * Calculates a person's age.
 * @param string $BirthDate Date as string in YYYY-MM-DD format
 * @param string $Today Date to calculate from as string in YYYY-MM-DD format. Defaults to current date.
 * @return int
 */
function calculateAge($BirthDate, $Today = "") {
    if ($Today == '') {
        $now = new DateTime();
    } else {
        $now = new Datetime($Today);
    }
    $date = new DateTime($BirthDate);
    $interval = $now->diff($date);
    return $interval->format('%y');
}


/**
 * Calculates payment amount based on age and pass type
 * @param int $Age in years
 * @param string $PassType One of "Weekend", "Friday", "Saturday", "Sunday", "Monday"
 * @return int
 */
function calculatePassCost($Age, $PassType) {
    if ($Age <= 5) {
        $Weekend = 0;
        $Friday = 0;
        $Saturday = 0;
        $Sunday = 0;
        $Monday = 0;
    } else if (($Age > 5) && ($Age <= 12)){
        $Weekend = 35;
        $Friday = 15;
        $Saturday = 25;
        $Sunday = 25;
        $Monday = 15;
    } else {                // 13 and up
        $Weekend = 55;
        $Friday = 25;
        $Saturday = 35;
        $Sunday = 35;
        $Monday = 25;
    }

    switch ($PassType) {
        case "Weekend":
            $PaidAmount = $Weekend;
            break;
        case "Friday":
            $PaidAmount = $Friday;
            break;
        case "Saturday":
            $PaidAmount = $Saturday;
            break;
        case "Sunday":
            $PaidAmount = $Sunday;
            break;
        case "Monday":
            $PaidAmount = $Monday;
            break;
    }
    return $PaidAmount;

}
