<?php
include "./dbconnect.php";

//neuklada se citelne heslo, ale jeho hash
function zaheshujHeslo($heslo) {
  return password_hash($heslo, PASSWORD_BCRYPT);
  //return crypt($heslo, "#osolim@to!");
  //return $heslo;
}

//nacteni vstupnich parametru (JSONu v body z POST metody)
$json = file_get_contents('php://input');
$data = json_decode($json);

//rozliseni funkcnosti podle akce...
if (!isset($data->akce)) $data->akce = "";
if ($data->akce == "registrace") {
  $hashHesla = zaheshujHeslo($data->heslo);
  try {
    $sql = "INSERT INTO uzivatele (prihlasovacijmeno, heslo, email) VALUES ('$data->prihlasovacijmeno','$hashHesla','$data->email');";
    mysqli_query($conn, $sql);
    $out = ["status" => "OK", "status_message" => "Registrace provedena."];

  } catch (exception $e) {
    $out = ["status" => "Chyba", "status_message" => "Uzivatel s timto prihlasovacim jmenem jiz existuje."];

  }

} else if ($data->akce == "prihlaseni") {
  $hashHesla = zaheshujHeslo($data->heslo);
  $sql = "SELECT * FROM uzivatele WHERE prihlasovacijmeno = '$data->prihlasovacijmeno'";
  $result = mysqli_query($conn, $sql);
  if (($row  = mysqli_fetch_assoc($result)) && password_verify($data->heslo, $row["heslo"])) {
    $msg = "Přihlášení úspěšné.";
    if ($row["posledniprihlaseni"] != 0) {
      $msg .= " Předchozí přihlášení " . date("d.m.Y H:i:s", $row["posledniprihlaseni"]);
    }
    $out = ["status" => "OK", "status_message" => $msg, "email" => $row["email"], "predchoziprihlaseni" => intval($row["posledniprihlaseni"])];
    //aktualizace posledního přihlášení
    $tm = time();
    mysqli_query($conn, "UPDATE uzivatele SET posledniprihlaseni = $tm WHERE prihlasovacijmeno = '$data->prihlasovacijmeno'");
  } else {
    $out = ["status" => "Chyba", "status_message" => "Prihlasovaci jmeno nebo heslo nesouhlasi."];
  }
  mysqli_free_result($result);

} else {
  $out = ["status" => "Chyba", "status_message" => "Neznama akce!"];
}

header("Content-Type: application/json");
echo json_encode($out);

include "./dbclose.php";
?>