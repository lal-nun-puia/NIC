<?php
$host="localhost";
$user="root";
$pass="";
$db="advertisements";
$conn=mysqli_connect($host,$user,$pass,$db);

if(!$conn){
	die("Connection failed: ". mysqli_connect_error());
}
try{
    $title = $_POST["title"];
    $description = $_POST["description"];
    $view_count = $_POST["view_count"];
    $kilogram = $_POST["kilogram"];
  
    
    // $sql= "INSERT INTO post(title,description,view_count,kilogram) VALUES ('$title','$description','$view_count','$kilogram')";
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}
$conn->query("INSERT INTO post (title,description,view_count,kilogram) VALUES ('$title','$description','$view_count','$kilogram')");
echo "New record created sucessfully";
//header with message
header("Location: /");
exit;
}catch (\Throwable $th){
    // header("Location: create.php?message=" . $th->getMessage());
    echo $th->getMessage();
}
mysqli_close($conn);
?>
<br>
<br>
<button onclick="history.back()">Go Back</button>
