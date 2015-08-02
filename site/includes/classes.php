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
    public $pass_type;      // weekend, friday, saturday, vip, etc
    public $pass_type_id;   // References pass_types table
    public $reg_type;      // Registration type. Reg or PreReg
    public $order_id;
    public $checked_in;
    public $notes;
    public $added_by;
    public $created;


    /**
     * Returns the badge name if set, or Firstname Lastname otherwise
     *
     * @return string Name
     */
    public function getNameForBadge() {
        if (trim($this->badge_name) != '') {
            return $this->badge_name;
        } else {
            return $this->first_name . ' ' . $this->last_name;
        }
    }


    /**
     * If badge name is set, returns Firstname Lastname. If badge name is empty, return an empty string
     * because the real name will have been printed in place of the badge name (via getNameForBadge())
     *
     * @return string Name
     */
    public function getSmallNameForBadge() {
        if (trim($this->badge_name) != '') {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return "";
        }
    }


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
     * Returns true if attendee is < 18 years old or birthdate is not set
     *
     * @return bool
     */
    public function isMinor() {
        if ($this->getBirthDate() == '' or $this->getBirthMonth() == '' or $this->getBirthYear() == '') {
            return true;
        } elseif ($this->getAge() < 18) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Attendee age in years or -1 if birthdate isn't set
     *
     * @return int
     */
    public function getAge() {
        if ($this->birthdate == null) {
            return -1;
        }
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
        if (array_key_exists('pass_type', $array)) {
            $this->pass_type = $array['pass_type'];
        }
        $this->pass_type_id = $array['pass_type_id'];
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

class PassType {
    public $id;
    public $name;
    public $stripe_color;  // RGB color for badge stripe, without "#". Ex: 323E99
    public $stripe_text;   // Text to print in badge stripe (Adult, youth, child)
    public $day_text;      // Friday, saturday, vip, etc. Used when printing badges
    public $visible;       // Is this type visible in the UI?
    public $min_age;       // Minimum age >= years for this pass type.
    public $max_age;       // Maximum age <= years for this pass type.
    public $cost;

    /**
     * Loads data in to this object from the given array. Usually used with a $_POST object.
     *
     * @param Array $array
     */
    public function fromArray($array) {
        if (isset($array['id'])) {
            $this->id = $array['id'];
        }
        $this->name = $array['name'];
        $this->stripe_color = $array['stripe_color'];
        $this->stripe_text = $array['stripe_text'];
        $this->day_text = $array['day_text'];
        $this->visible = $array['visible'];
        $this->min_age = $array['min_age'];
        $this->max_age = $array['max_age'];
        $this->cost = $array['cost'];
    }

    /**
     * Returns a more friendly age range string. For example, "13+" instead of "13 - 255". Treats
     * 255 as the max age for "+" substitutions.
     *
     * @return String ageRange
     */
    public function getAgeRange() {
        $ageRange = $this->min_age;

        if ($this->max_age == 255 && $this->max_age > 0) {
          $ageRange .= "+";
        } else {
          $ageRange .= " - " . $this->max_age;
        }
        return $ageRange;
    }
    
}