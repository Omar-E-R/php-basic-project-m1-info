<?php

error_reporting(1);

class SqlFunctions{

    private $DBHOST = 'localhost';
    private $DBNAME = 'pagination';
    private $DBUSER = 'mysql';
    private $DBPASS = '';
    public $conn;

    public function __construct(){
        try{
            $this->conn = mysqli_connect($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
            if(!$this->conn){
                throw new Exception('NO Connection was established');
            }
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

    }

    public function show_rec($tbl_name, $offset, $limit){

        $select = "SELECT * FROM $tbl_name LIMIT $offset, $limit";
        $se_fire = mysqli_query($this->conn ,$select);
        if(mysqli_num_rows($se_fire) > 0){
            $se_fetch = mysqli_fetch_all($se_fire, MYSQLI_ASSOC);
            if($se_fetch){
                return $se_fetch;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }

    public function rec_count($tbl_name){

        $count = "SELECT COUNT(*) AS NumberOfRecords FROM $tbl_name";
        $count_fire = mysqli_query($this->conn, $count);
        if($count_fire){
            $count_rec = mysqli_fetch_assoc($count_fire);
            if($count_rec){
                return $count_rec['NumberOfRecords'];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }

}


?>
