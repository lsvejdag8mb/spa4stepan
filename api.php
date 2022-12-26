<?php

//neuklada se citelne heslo, ale jeho hash
function zaheshujHeslo($heslo) {
  //return password_hash($heslo, PASSWORD_BCRYPT);
  return crypt($heslo, "#osolim@to!");
}

//nacteni vstupnich parametru (JSONu v body z POST metody)
$json = file_get_contents('php://input');
$data = json_decode($json);

//nacteni seznamu uzivatelu ze souboru
define("SOUBOR_UZIVATELE", "uzivatele.json");
$uzivatele = [];
if (file_exists(SOUBOR_UZIVATELE)) {
  $uzivatele = json_decode(file_get_contents(SOUBOR_UZIVATELE));
}

//rozliseni funkcnosti podle akce...
if ($data->akce == "registrace") {
  $out = ["status" => "OK", "status_message" => "Registrace provedena."];
  for ($i=0; $i<count($uzivatele); $i++) {
    if ($uzivatele[$i]->prihlasovacijmeno == $data->prihlasovacijmeno) {
      $out = ["status" => "Chyba", "status_message" => "Uzivatel s timto prihlasovacim jmenem jiz existuje."];
      break;
    }
  }
  if ($out["status"] == "OK") {
    unset($data->akce); //z prijatych dat ulozime vse krome akce
    $data->heslo = zaheshujHeslo($data->heslo);
    array_push($uzivatele, $data);
    file_put_contents(SOUBOR_UZIVATELE, json_encode($uzivatele));      
  }

} else if ($data->akce == "prihlaseni") {
  $out = ["status" => "Chyba", "status_message" => "Prihlasovaci jmeno nebo heslo nesouhlasi."];
  for ($i=0; $i<count($uzivatele); $i++) {
    if ($uzivatele[$i]->prihlasovacijmeno == $data->prihlasovacijmeno 
        //&& hash_equals($uzivatele[$i]->heslo, zaheshujHeslo($data->heslo))) {
        && $uzivatele[$i]->heslo == zaheshujHeslo($data->heslo)) {
      $out = ["status" => "OK", "status_message" => "Prihlaseni uspesne."];
      break;
    }
  }

} else {
  $out = ["status" => "Chyba", "status_message" => "Neznama akce!"];
}

header("Content-Type: application/json");
echo json_encode($out);
exit();
