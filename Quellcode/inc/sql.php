<?php

require_once 'inc/dbconnect.php';

class sql
{
    // Funktion AnAus Eintrag holen
public static function getAnAus() : int
{
        GLOBAL $db;
        $sql = 'Select bool from AnAus'; //SQL Befehl
        $q = $db->query($sql); // Ausführen
        $d = $q->fetchColumn(); // Erste Zeile->erste Spalte
        return $d;
}

// Funktion AnAus Eintrag setzen
public static function setAnAus(int $einaus) : bool
{
        GLOBAL $db;
        $sql = 'Update AnAus set bool = ?'; // ? = Platzhalter
        $q = $db->prepare($sql); // Vorbereiten
        $q->bindParam(1,$einaus);
        return $q->execute(); // Ausführen
}

// Funktion alle Türbewegungen
public static function getTB() : array
{
        GLOBAL $db;
        $sql = 'Select Datum, Uhrzeit from Türbewegung order by ID DESC';
        $q = $db->query($sql);
        return $q->fetchAll(); // Alle zurückgeben

}

// Funktion alle Türbewegungen mit von/bis
public static function getTBvb($von, $bis) : array
{
        GLOBAL $db;
        $sql = "SELECT Uhrzeit, Datum FROM Türbewegung where Datum between cast('".$von."' as date) and cast('".$bis."' as date) order by ID DESC";
        $d = $db->query($sql);
        return $d->fetchAll();
}
}

?>