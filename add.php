<?php
$active = "add";
include 'header.php';

?>
<div class="side_index">
<?php


$mu = 0;
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get JSON data from form
    $jsonData = $_POST['json_data'];

    // Decode JSON data
    $data = json_decode($jsonData, true);

   





    // Check if JSON decoding was successful
    if ($data === null) {
        die("Error decoding JSON data.");
    }

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Loop through JSON data
    foreach ($data as $item) {

    $date=date_create($item['date']);

    
    $d =  date_format($date,"d");
    $m =  date_format($date,"m");
    $y =  date_format($date,"Y");
    $day_name = date_format($date,"l");
    $date2 =  date_format($date,"Y-m-d");
     $date2.'<br>';

            // Check if id exists in the table
            $idExistsQuery = "SELECT id FROM main";
            $result = $conn->query($idExistsQuery);

            if ($result->num_rows > 0) {
                // Id exists, get the maximum id value and increment it by 1
                $maxIdQuery = "SELECT MAX(id) AS max_id FROM main";
                $result = $conn->query($maxIdQuery);
                $row = $result->fetch_assoc();
                $newId = $row['max_id'] + 1;
            } else {
                // Id doesn't exist, use the provided id
                $newId = 1;
            }
        
  $username = $item['username'];
  $tableName = "main"; // Change this to your actual table name
          if ($item['type'] == 'z2u') {
              
$order_net = $item['order_net'];
$order_not_useable = round($order_net*0.091,2);
$order_useable = round($order_net*0.819,2);
 $order_service = $item['order_service'];
        
$insertDataQuery = "INSERT INTO $tableName (id, username, order_net, order_useable,order_not_useable,order_service,d,day_name,m,y,date,type) VALUES ($newId,'$username',$order_net,$order_useable,$order_not_useable,'$order_service', $d, '$day_name', $m, $y, '$date2','z2u')";
  
//$insertDataQuery = "INSERT INTO $tableName (id) VALUES ($newId)";           
              
          }
          else{

  $username_value = $item['username_value'];
  $give = $item['give'];
$value = round($item['value'],2); 
if ($give == "") {
    if ($value < 0) {
       $give = 1;
    }
    else {
        $give = 0;
    }
}
$name = $item['name'];
if (!$item['lost'] == "") {
    $lost = $item['lost'];
} else {
   $lost = 0;
}



$insertDataQuery = "INSERT INTO $tableName (id, value, username,username_value,name,give,lost,d,day_name,m,y,date) VALUES ($newId, $value, '$username','$username_value','$name',$give, $lost,$d, '$day_name', $m, $y, '$date2')";
          }
   



        if ($stmt = $conn->query($insertDataQuery)=== TRUE) {
                 
                    $mu ++;
        } else {
            echo "Error inserting data: " . $conn->error . "<br>";
        }
    }
    
    
echo "Data inserted $mu<br>";
    // Close connection
    $conn->close();
}

?>
   <div class="container">
    <h2 class="mb_15">Add</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <textarea id="json_data" name="json_data" rows="10" class="mb_15"></textarea><br>
        <button type="submit" name="submit">Submit</button>
    </form>
    </div>
      </div>
</body>
</html>