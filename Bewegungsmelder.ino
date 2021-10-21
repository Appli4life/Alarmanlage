int ledPin = 4;
int Motion = 7;

#include <Arduino.h>
#include <U8x8lib.h>

U8X8_SSD1306_128X64_ALT0_HW_I2C u8x8(U8X8_PIN_NONE);

void setup() 
{
   pinMode(ledPin, OUTPUT);
   pinMode(Motion, INPUT); 
  
   Serial.begin(9600);
   u8x8.begin();
   u8x8.setPowerSave(0);
   u8x8.setFlipMode(1);
   u8x8.setFont(u8x8_font_chroma48medium8_r);
   u8x8.setCursor(0, 33);
   digitalWrite(ledPin, HIGH);
   u8x8.println("Eingeschalten");
}

void loop() 
{
 bewegungchecken();
 delay(500);
}

// Ueberpruefen auf bewegung alle 0,5 Sekunden
void bewegungchecken()
{
  if(Serial.available())
  {
    // Falls das Skript 1 schickt, heisst dies, dass es auschalten muss
    if(Serial.read() == 48)
    {
      ausgeschalten();
    }

    byte i = digitalRead(Motion); // DigitalRead vom Bewegungsmelder
        
    Serial.println(i, DEC); // Antwort geben (1 = Bewegung, 0 = keine Bewegung)
    
    if(i == 1)
    {
    // 30 Sekunden warten damit es nicht so viele Eintraege gibt
    for(int l = 0;l < 30;l++)
    {
      delay(1000);
    }
    }
  }
}


void ausgeschalten()
{
  u8x8.clear();
  u8x8.println("Ausschalten...");
  delay(4000);
  u8x8.clear();
  u8x8.println("Ausgeschalten");
  digitalWrite(ledPin, LOW);

  while(true)
  { 
    if(Serial.read() == 49)
    {
      Serial.println(0, DEC);
      u8x8.clear();
      u8x8.println("Einschalten..");
      break;
    }
    delay(2000);
  }
   u8x8.clear();
   u8x8.println("Eingeschalten");
   digitalWrite(ledPin, HIGH);
   delay(1000);
}
