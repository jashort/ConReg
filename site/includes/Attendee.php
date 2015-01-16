<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 1/12/15
 * Time: 10:06 PM
 */

class Attendee {
    public $id;
    public $first_name;
    public $last_name;
    public $badge_name;
    public $badge_number;
    public $zip;
    public $country;
    public $phone;
    public $email;
    public $birthdate;        // Birthdate in YYYY-MM-DD format
    public $ec_fullname;
    public $ec_phone;
    public $ec_same;
    public $parent_fullname;
    public $parent_phone;
    public $parent_form;
    public $paid;
    public $paid_amount;
    public $pass_type;
    public $reg_type;      // Registration type. Reg or PreReg
    public $order_id;
    public $checked_in;
    public $notes;
    public $added_by;
    public $created;

    /**
     * @return string Birthdate in MM/DD/YYYY format
     */
    public function getBirthDate() {
        $date = new DateTime($this->birthdate);
        return $date->format('m/d/Y');
    }

    /**
     * @return bool Returns true if < 18 years old
     */
    public function isMinor() {
        if ($this->getAge() < 18) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return int Returns age in years
     */
    public function getAge() {
        $date = new DateTime($this->birthdate);
        $now = new DateTime();
        return $now->diff($date)->y;
    }
}
