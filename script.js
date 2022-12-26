async function registrovat() {
  //priprava vstupnich parametru
  let body = {};
  body.akce = "registrace";
  body.prihlasovacijmeno = document.getElementById("reg_prihljm").value;
  body.heslo = document.getElementById("reg_heslo").value;
  body.email = document.getElementById("reg_email").value;
  
  //odeslani vstupnch parametru na server a prijem dat
  let url = location.href + "api-mysql.php"; //toto je PHPko na serveru
  let response = await fetch(url, {method: "POST", body: JSON.stringify(body)});
  let data = await response.json();
  console.log(data); //data, ktera vratil v odpovedi serveri

  //zpracovani vracenych dat a vystup do stranky
  if (data.status == "OK") {
    document.getElementById("info").style.color = "blue";
  } else {
    document.getElementById("info").style.color = "red";
  }
  document.getElementById("info").innerHTML = data.status_message;
}

async function prihlasit() {
  //priprava vstupnich parametru
  let body = {};
  body.akce = "prihlaseni";
  body.prihlasovacijmeno = document.getElementById("prihljm").value;
  body.heslo = document.getElementById("heslo").value;
  
  //odeslani vstupnch parametru na server a prijem dat
  let url = location.href + "api-mysql.php"; //toto je PHPko na serveru
  let response = await fetch(url, {method: "POST", body: JSON.stringify(body)});
  let data = await response.json();
  console.log(data); //data, ktera vratil v odpovedi serveri

  //zpracovani vracenych dat a vystup do stranky
  if (data.status == "OK") {
    document.getElementById("info").style.color = "blue";
  } else {
    document.getElementById("info").style.color = "red";
  }
  document.getElementById("info").innerHTML = data.status_message;
}