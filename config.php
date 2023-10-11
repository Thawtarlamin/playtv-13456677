<?php

class config{
    const HOST_NAME = "localhost";

    const USERNAME = "starstar_noti";
    const PASSWORD ="Kwt@014180";
    const DB_NAME = "starstar_noti";
    
    private $conn;
    public function __construct(){
        $this->conn = new mysqli(self::HOST_NAME,self::USERNAME,self::PASSWORD,self::DB_NAME);

    }
    public function Insetdata($name){
        $stmt = $this->conn->prepare("INSERT INTO `noti`(`name`) VALUES (?)");
        $stmt->bind_param("s",$name);
        $result = $stmt->execute();
        $return = $result ? "success" : "failed";
        return $return;
    }

    public function getAllData(){
        $ary = array();
        $stmt = $this->conn->query("SELECT * FROM `noti`");
        while ($row = $stmt->fetch_object())
        {
            array_push($ary,$row);
        }
        return $ary;
    }
    public function Check($name){
        $query = "SELECT * FROM `noti` WHERE name='$name'";
        $uuid = mysqli_query($this->conn,$query);
        if($uuid->num_rows){
            return true;
        }else{
            return false;
        }
    }
    
    
}

?>