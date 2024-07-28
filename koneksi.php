<?php 
class koneksiDB{
    function getKoneksi(){
        $host = "localhost";
        $username = "root";
        $pass = "";
        $db = "universitas";
        $konek = mysqli_connect($host, $username, $pass, $db) or die("Gagal Koneksi" . mysqli_connect_error());
        if(mysqli_connect_errno()){
            exit();
        }
        return $konek;
    }
}



?>