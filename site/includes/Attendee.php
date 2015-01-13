<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 1/12/15
 * Time: 10:06 PM
 */

class Attendee {
    public $kumo_reg_data_id;
    public $kumo_reg_data_fname;
    public $kumo_reg_data_lname;
    public $kumo_reg_data_bname;
    public $kumo_reg_data_bnumber;
    public $kumo_reg_data_zip;
    public $kumo_reg_data_country;
    public $kumo_reg_data_phone;
    public $kumo_reg_data_email;
    public $kumo_reg_data_bdate;        // Birthdate in YYYY-MM-DD format
    public $kumo_reg_data_ecfullname;
    public $kumo_reg_data_ecphone;
    public $kumo_reg_data_same;
    public $kumo_reg_data_parent;
    public $kumo_reg_data_parentphone;
    public $kumo_reg_data_parentform;
    public $kumo_reg_data_paid;
    public $kumo_reg_data_paidamount;
    public $kumo_reg_data_passtype;
    public $kumo_reg_data_regtype;
    public $kumo_reg_data_orderid;
    public $kumo_reg_data_checkedin;
    public $kumo_reg_data_notes;
    public $kumo_reg_data_staff_add;
    public $kumo_reg_data_timestamp;

    public $birthMonth;
    public $birthDay;
    public $birthYear;

    public function getBirthDate() {
        $date = new DateTime($this->kumo_reg_data_bdate);
        return $date->format('m/d/Y');
    }
    
    public function getAge() {
        $date = new DateTime($this->kumo_reg_data_bdate);
        $now = new DateTime();
        return $now->diff($date)->y;
    }
}
