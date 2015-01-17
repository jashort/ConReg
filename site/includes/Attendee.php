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

    public function getBirthDay() {
        $date = new DateTime($this->birthdate);
        return $date->format('d');
    }

    public function getBirthMonth() {
        $date = new DateTime($this->birthdate);
        return $date->format('m');
    }

    public function getBirthYear() {
        $date = new DateTime($this->birthdate);
        return $date->format('Y');
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

    /**
     * @param $array Loads object properties from the given array. Usually used with a $_POST object.
     */
    public function fromArray($array) {
        $this->id = $array['id'];
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->badge_name = $array['badge_name'];
        $this->badge_number = $array['badge_number'];
        $this->zip = $array['zip'];
        $this->country = $array['country'];
        $this->phone = $array['phone'];
        $this->email = $array['email'];
        $this->birthdate = $array['birth_year'] . '-' . $array['birth_month'] . '-' . $array['birth_day'];
        $this->ec_fullname = $array['ec_fullname'];
        $this->ec_phone = $array['ec_phone'];
        $this->ec_same = $array['ec_same'];
        $this->parent_fullname = $array['parent_fullname'];
        $this->parent_phone = $array['parent_phone'];
        $this->parent_form = $array['parent_form'];
        $this->paid = $array['paid'];
        $this->paid_amount = $array['paid_amount'];
        $this->pass_type = $array['pass_type'];
        $this->reg_type = $array['reg_type'];
        $this->order_id = $array['order_id'];
        $this->checked_in = $array['checked_in'];
        $this->notes = $array['notes'];
    }
}
