<?php
     require_once "func.php";


     $errors = array();

   if(isset($_GET["id"]))
    {   header('Content-Type: application/json');
        echo getMunicipality($_GET["id"]);
    }

  function getMunicipality($districtID)
  {
    $con = dbConnect();
    if(!$con)
    {
        return false;
    }

    $stmt = $con->prepare("SELECT * FROM municipalities where district_id = ?");
    $stmt->bind_param("i",$districtID);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc())
    {
        $data[] = $row;

    }
    $stmt->close();
    return json_encode($data);
  }    




    