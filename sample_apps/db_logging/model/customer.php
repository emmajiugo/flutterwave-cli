<?php
class Customer {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addCustomer($data) {

        // //prepare query
        $this->db->query('INSERT INTO customers (id, name, phone_number, email, address) VALUES(:id, :name, :phone_number, :email, :address)');

        //bind values 
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':address', $data['address']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }

    }
    
    public function getCustomers() {
        $this->db->query('SELECT * FROM customers ORDER BY created_at DESC');
        $results = $this->db->resultset();
        return $results;
    }
}