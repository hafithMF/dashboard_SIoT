#perangkat
- servo
- lcd i2c
- dht
- esp32

#CODE WOKWI

#include <WiFi.h>
#include <MQTT.h>
#include "DHTesp.h"
#include <NusabotSimpleTimer.h>
#include <ESP32Servo.h>
#include <LiquidCrystal_I2C.h>

const int DHT_PIN = 14;
const int SERVO_PIN = 18;

const char ssid[] = "Wokwi-GUEST";
const char pass[] = "";

WiFiClient net;
MQTTClient client;
DHTesp dhtSensor;
NusabotSimpleTimer timer;
Servo servo;
LiquidCrystal_I2C lcd(0x27, 16, 2);

String temp, humid;
int posServo = 0;

// Untuk teks berjalan di LCD
String lcdText = "";
String scrollText = "";
int scrollIndex = 0;
unsigned long lastScrollTime = 0;
const unsigned long scrollDelay = 300;
bool scrolling = false;

void publishDHT() {
  TempAndHumidity data = dhtSensor.getTempAndHumidity();
  temp = String(data.temperature, 2);
  humid = String(data.humidity, 1);

  client.publish("nusabot/suhu", temp, true, 1);
  client.publish("nusabot/kelembapan", humid, true, 1);
}

void subscribe(String &topic, String &data) {
  if (topic == "nusabot/servo") {
    posServo = data.toInt();
    servo.write(posServo);
  }

  if (topic == "nusabot/lcd") {
    lcdText = data;
    scrollText = lcdText + "                "; 
    scrollIndex = 0;
    scrolling = scrollText.length() > 16;

    if (!scrolling) {
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print(lcdText);
    }
  }
}

void connect() {
  Serial.print("checking wifi...");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }

  Serial.print("\nconnecting...");
  while (!client.connect("123456789", "dashboard-iot", "zMFFSQNxLvQ29Alg")) {
    Serial.print(".");
    delay(1000);
  }

  Serial.println("\nconnected!");
  client.publish("nusabot/serial_number/123456789", "Online", true, 1);
  client.subscribe("nusabot/#", 1);
}

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, pass);
  dhtSensor.setup(DHT_PIN, DHTesp::DHT22);
  client.begin("dashboard-iot.cloud.shiftr.io", net);
  timer.setInterval(2000, publishDHT);
  client.onMessage(subscribe);
  servo.attach(SERVO_PIN, 500, 2400);
  lcd.init();
  lcd.backlight();
  servo.write(posServo);

  client.setWill("nusabot/serial_number/123456789", "Offline", true, 1);
  connect();
}

void loop() {
  timer.run();
  client.loop();

  if (!client.connected()) {
    connect();
  }

  // Scroll text mulus
  if (scrolling && millis() - lastScrollTime >= scrollDelay) {
    lcd.setCursor(0, 0);
    lcd.print(scrollText.substring(scrollIndex, scrollIndex + 16));
    scrollIndex++;
    if (scrollIndex > scrollText.length() - 16) {
      scrollIndex = 0;
    }
    lastScrollTime = millis();
  }

  delay(10);
}
