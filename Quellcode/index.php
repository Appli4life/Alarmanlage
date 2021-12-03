<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titel -->
    <title>Alarmanlage</title>

<!-- Include Bootstrap --> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="inc/bg.css">
</head>

<!-- Js and bodystyle -->
<body background="http://img.webme.com/pic/m/minesofpanem/background1.jpg; capacity">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<center>
<!-- Überschrift -->
<div id='content'>
<h1>Alarmanlage</h1>

<?php

// File für Datenbankverbindung
require_once 'inc/sql.php';
require_once 'inc/helpers.php';

// Datum von/bis hole falls gesetzt
$Datum_von = $_GET["Datum_von"] ?? '';
$Datum_bis = $_GET["Datum_bis"] ?? '';
$einaus = "Ausschalten";

$ea = sql::getAnAus(); // Überprüfen ob Alarmanlage ein aus ist

// Falls Alarmanlage aus ist
if($ea === 0)
{
        // Anzeigen Einschalten für später
        $einaus = "Einschalten";
}

// Wenn zurücksetzen gedrück wurde
if(isset($_GET["back"]))
{
        // Daten leeren
        $Datum_von = '';
        $Datum_bis = '';
}

// Wenn Daten nicht leer sind
if($Datum_von != '' && $Datum_bis != '')
{
        // Alle Türbewegungen mit Parameter
        $erg = sql::getTBvb($Datum_von, $Datum_bis);
}
else
{
        // Sonst ohne Parameter
        $erg = sql::getTB();
}

echo "<p>Einträge:</p>";

if($erg != "")
{
        echo "<div class='border'>
        <div class='row justify-content-center'>
        <div class='col-2'> 
        <table class='table table-striped table-borderd table-hover'>
        <thead>
        <tr>
          <th>".$erg[0]['Datum']."</th>
        </tr>
      </thead>
      <tbody>";
        for ($i=1; $i < count($erg); $i++) 
        {
                if($erg[$i]['Datum'] == $erg[$i-1]['Datum'])
                {
                        echo "         
                        <tr>
                        <td class='text-center'>".$erg[$i]["Uhrzeit"]."</td>
                        </tr>";
                }
                else
                {
                        echo "</tbody>
                        </table>
                        <table class='table table-striped table-borderd table-hover'>
                        <thead>
                        <tr>
                        <th>".$erg[$i]['Datum']."</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td class='text-center'>".$erg[$i]['Uhrzeit']."</td>
                        </tr>";
                }
                
  

        }
        echo "</tbody></table></div></div></div>";
}
else
{
        echo "Keine Einträge";
}

?>

<br>
     
<h3>Filter:</h2>
<form action="<?php echo $_SERVER["SCRIPT_NAME"];?>" mehode="get">
        <div class="row-md">
        <label class="form-label" for="von">Von:</label>
        <div class="col-4">
        <input class="form-control"  type="date" id="von" name="Datum_von" required value = "<?php echo $Datum_von;?>">
        </div>
        </div>
        <label class="form-label" for="bis">Bis:</label>
        <div class="col-4">
        <input class="form-control" type="date" id="bis" name="Datum_bis" required value = "<?php echo $Datum_bis;?>">
        </div>
        <br>
        <div class="form-group">
        <input class="btn btn-primary" type="submit" name="send" value="Anwenden">
        <input class="btn btn-secondary" type="submit" name="back" value="Filter Entfernen">
        </div>
</form>
</div>
</body>
</html>