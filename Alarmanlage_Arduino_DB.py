import serial
import time
import mariadb as mdb
import sys
import os

# Methode System AnAus Schalten
def getAnAus():
    f = "Select * from AnAus;"
    curs.execute("COMMIT;") # Damit der letzte SELECT geloescht wird
    curs.execute(f) # SELECT Ausfuehren
    i = curs.fetchone()[0] #Der erste Eintrag das Erste Feld nehmen
    return i # Feld zurueckgeben

arduino = serial.Serial('/dev/ttyUSB0', 9600) # Verbindung zu Arduino
arduino.isOpen() # Verbindung Oeffnen

print("Arduino Verbinden...")

time.sleep(5) # 5 Sekunden warten bis die Verbindung offen ist

print("Arduino Verbunden")

try:
   # Datenbank Verbindung aufbauen
    db = mdb.connect(
        user="arduino",
        password="fjwogu-joy9@-kfjf@2",
        host="localhost",
        port=3306,
        database="Alarmsystem")
    db.autocommit = False

    print("Datenbank Verbunden")

    # Endlosschleife zur Kommunikation mit dem Arduino 
    while True:
        curs = db.cursor()
        i = getAnAus() # Methoden Aufruf
        print(f"Arduino schreiben mit {i}")
        arduino.write(i.encode()) # Arduino schreiben (1 = Eingeschalten lassen, 0 = Ausschalten)
        if(i == '0'): # Wenn Ausschalten
            print("System ausgeschalten")
            time.sleep(4) # Arduino Synch
            while True:
                # Ueberpruefen auf einschalten
                i = getAnAus()
                print(f"Arduino schreiben mit: {i}")
                arduino.write(i.encode()) # Arduino einschalten/ausgeschalten lassen
                if(i == '1'): # Wenn in der Datenbank 1 steht --> Einschalten
                    print("System eingeschalten") 
                    time.sleep(4)
                    break # Aus der Endlosschleife raus
                time.sleep(2)
                    
        print("Warten auf Antwort...")
        antwort = arduino.readline() # Antwort des Arduino (1 = Bewegung, 0 = keine Bewegung)
        print(f"Arduino sagt: {antwort}")
                    
        if(antwort == b'1\r\n'): # Anwtwort checken
            print("Bewegung registriert...")
            curs.execute("INSERT INTO Türbewegung (Uhrzeit, Datum) VALUES (NOW(), CURRENT_DATE())")
            db.commit() 
            print("In Datenbank geschrieben")
            time.sleep(30) # 30 Sekunden warten
        else:
            print("Keine Bewegung")
        time.sleep(0.5) # Synchronisation mit Arudino
        curs.close()

# Exceptions
except mdb.Error as e:
    print(f"Error: {e}")
    arduino.close()
    sys.exit(1)

except KeyboardInterrupt:
    print("Schliessen")
    arduino.close()
    sys.exit(1)


