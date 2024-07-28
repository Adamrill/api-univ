<?php 
include_once("koneksi.php");
$db = new koneksiDB();
$koneksi = $db->getKoneksi();
$request = $_SERVER['REQUEST_METHOD'];
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segment = explode('/', $uri_path);
// var_dump($uri_segment);
// die;

switch($request){
    case 'GET' :
        if(!empty($uri_segment[3])){
            $nim = intval($uri_segment[3]);
            get_mahasiswa($nim);
        } else {
            get_mahasiswa();
        }
        break;
    case 'PUT' :
        $nim = intval($uri_segment[3]);
        update_mahasiswa($nim);
        break;
    case 'POST' :
        insert_mahasiswa();
        break;
    case 'DELETE' :
        $nim = intval($uri_segment[3]);
        delete_mahasiswa($nim);
        break;
    default :
    header("HTTP/1.0 405 Method Tidak Terdaftar");
    break;
}

function get_mahasiswa($nim=""){
    global $koneksi;
    $query = "SELECT * FROM mahasiswa";
    if(!empty($nim)){
        $query .=" WHERE nim=$nim LIMIT 1";
    }
    $respon = array();
    $result = mysqli_query($koneksi, $query);
    $i = 0;
    if($result){
        $respon['status'] = "sukses";
        $respon['pesan'] = "Data berhasil diambil";
        while($row = mysqli_fetch_array($result)){
            $respon['data'][$i]['NIM Mahasiswa'] = $row['nim'];
            $respon['data'][$i]['Nama Mahasiswa'] = $row['nama'];
            $respon['data'][$i]['Angkatan'] = $row['angkatan'];
            $respon['data'][$i]['Semester'] = $row['semester'];
            $respon['data'][$i]['IPK Mahasiswa'] = $row['ipk'];
            $respon['data'][$i]['email Mahasiswa'] = $row['email'];
            $respon['data'][$i]['telepon Mahasiswa'] = $row['telepon'];
            $i++;
        } 
    } else {
        $respon['status'] = "gagal";
        $respon['pesan'] = "Data tidak berhasil diambil";
    }
    header('Content-type: application/json');
    echo json_encode($respon);
}

function insert_mahasiswa(){
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);
    $nim = $data['nim'];
    $nama = $data['nama'];
    $angkatan = $data['angkatan'];
    $semester = $data['semester'];
    $ipk = $data['ipk'];
    $email = $data['email'];
    $telepon = $data['telepon'];
    $query = "INSERT INTO mahasiswa SET nim = '".$nim."', nama = '".$nama."', angkatan = '".$angkatan."', semester = '".$semester."', ipk = '".$ipk."', email = '".$email."', telepon = '".$telepon."'";
    if(mysqli_query($koneksi, $query)){
        $respon = [
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil ditambahkan!'
        ];
    } else {
        $respon = [
            'status' => 'gagal',
            'pesan' => 'Data mahasiswa gagal ditambahkan!'
        ];
    }
    header('Content-type: application/json');
    echo json_encode($respon);
}

function update_mahasiswa(){
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);
    $nim = $data['nim'];
    $nama = $data['nama'];
    $angkatan = $data['angkatan'];
    $semester = $data['semester'];
    $ipk = $data['ipk'];
    $email = $data['email'];
    $telepon = $data['telepon'];
    $query = "UPDATE mahasiswa SET nim = '".$nim."', nama = '".$nama."', angkatan = '".$angkatan."', semester = '".$semester."', ipk = '".$ipk."', email = '".$email."', telepon = '".$telepon."'";
    if(mysqli_query($koneksi, $query)){
        $respon = [
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil diperbarui!'
        ];
    } else {
        $respon = [
            'status' => 'gagal',
            'pesan' => 'Data mahasiswa gagal diperbarui!'
        ];
    }
    header('Content-type: application/json');
    echo json_encode($respon);
}

function delete_mahasiswa($id){
    global $koneksi;
    $query = "DELETE FROM mahasiswa WHERE nim = '$id'";
    if(mysqli_query($koneksi, $query)){
        $respon = [
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil dihapus!'
        ];
    } else {
        $respon = [
            'status' => 'gagal',
            'pesan' => 'Data mahasiswa gagal dihapus!'
        ];
    }
    header('Content-type: application/json');
    echo json_encode($respon);
}
?>