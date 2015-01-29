<?php

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
        try {
            $date = new DateTime($this->birthdate);
            return $date->format('m/d/Y');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Returns day of birthdate, or empty string if unset
     *
     * @return string
     */
    public function getBirthDay() {
        try {
            $date = new DateTime($this->birthdate);
            return $date->format('d');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Returns month of birthdate, or empty string if unset
     *
     * @return string
     */
    public function getBirthMonth() {
        try {
            $date = new DateTime($this->birthdate);
            return $date->format('m');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Returns year of birthdate, or empty string if unset
     *
     * @return string
     */
    public function getBirthYear() {
        try {
            $date = new DateTime($this->birthdate);
            return $date->format('Y');
        } catch (Exception $e) {
            return '';
        }
    }
    
    /**
     * Returns true if attendee is < 18 years old
     *
     * @return bool
     */
    public function isMinor() {
        if ($this->getAge() < 18) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Attendee age in years
     *
     * @return int
     */
    public function getAge() {
        try {
            $date = new DateTime($this->birthdate);
            $now = new DateTime();
            return $now->diff($date)->y;
        } catch (Exception $e) {
            return -1;
        }
    }

    /**
     * Loads data in to this object from the given array. Usually used with a $_POST object.
     *
     * @param Array $array
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


class Staff {
    public $staff_id;
    public $username;
    public $password;
    public $enabled;
    public $first_name;
    public $last_name;
    public $initials;
    public $phone_number;
    public $last_badge_number;
    public $access_level;
    
    public function setPassword($pass) {
        $this->password = crypt($pass);
    }

    public function fromArray($array) {
        if (isset($array['staff_id'])) {
            $this->staff_id = $array['staff_id'];
        }
        if (isset($array['password'])) {
            $this->setPassword($array['password']);
        }
        $this->username = $array['username'];
        $this->enabled = $array['enabled'];
        $this->first_name = $array['first_name'];
        $this->last_name = $array['last_name'];
        $this->initials = $array['initials'];
        $this->phone_number = $array['phone_number'];
        $this->access_level = $array['access_level'];
    }
}