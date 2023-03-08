#include <Pushbutton.h>
#include <avdweb_Switch.h>

const int nbrOfButtons = 8;
int nbrOfLeds = 8;
int nbrRound = 20;
int PUNISHMENT = 1000;

int pinButton1 = 23;
int pinButton2 = 25;
int pinButton3 = 27;
int pinButton4 = 29;
int pinButton5 = 31;
int pinButton6 = 33;
int pinButton7 = 35;
int pinButton8 = 37;
int pinButton9 = 22;
int pinButton10 = 24;
int pinButton11 = 26;
int pinButton12 = 28;
int pinButton13 = 30;
int pinButton14 = 32;
int pinButton15 = 34;
int pinButton16 = 36;

int buttonsPin1[] = {pinButton1, pinButton2, pinButton3, pinButton4, pinButton5, pinButton6, pinButton7, pinButton8};
int buttonsPin2[] = {pinButton9, pinButton10, pinButton11, pinButton12, pinButton13, pinButton14, pinButton15, pinButton16};

int pinLed1 = 39;
int pinLed2 = 41;
int pinLed3 = 43;
int pinLed4 = 45;
int pinLed5 = 47;
int pinLed6 = 49;
int pinLed7 = 51;
int pinLed8 = 53;
int pinLed9 = 38;
int pinLed10 = 40;
int pinLed11 = 42;
int pinLed12 = 44;
int pinLed13 = 46;
int pinLed14 = 48;
int pinLed15 = 50;
int pinLed16 = 52;

int leds1[] = {pinLed1, pinLed2, pinLed3,pinLed4, pinLed5, pinLed6, pinLed7, pinLed8};
int leds2[] = {pinLed9, pinLed10, pinLed11,pinLed12, pinLed13, pinLed14, pinLed15, pinLed16};

int randomLed1;
int timeStart1 = 0;
int timeEnd1 = 0;
int nbrRoundPlayed1 = 0;
int isGameOver1 = 0;
int gamingLeds1[] = {-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,};
int ledStarted1 = -1;

int randomLed2;
int timeStart2 = 0;
int timeEnd2 = 0;
int nbrRoundPlayed2 = 0;
int isGameOver2 = 0;
int gamingLeds2[] = {-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,-1, -1,};
int ledStarted2 = -1;

Pushbutton button1(pinButton1);
Pushbutton button2(pinButton2);
Pushbutton button3(pinButton3);
Pushbutton button4(pinButton4);
Pushbutton button5(pinButton5);
Pushbutton button6(pinButton6);
Pushbutton button7(pinButton7);
Pushbutton button8(pinButton8);
Pushbutton button9(pinButton9);
Pushbutton button10(pinButton10);
Pushbutton button11(pinButton11);
Pushbutton button12(pinButton12);
Pushbutton button13(pinButton13);
Pushbutton button14(pinButton14);
Pushbutton button15(pinButton15);
Pushbutton button16(pinButton16);

Pushbutton buttons1[] = {button1, button2, button3, button4, button5, button6, button7, button8};
Pushbutton buttons2[] = {button9, button10, button11, button12, button13, button14, button15, button16};

int winner = 0;

int canPlay1 = 0;
int canPlay2 = 0;

int lastBlink1 = 0;
int lastBlink2 = 0;

int lastBlinkState1 = 0;
int lastBlinkState2 = 0;

Switch toggleSwitch = Switch(1); 


int on()
{
  return LOW;
}

int off()
{
  return HIGH;
}

void setup()
{
  

  Serial.begin(9600);
  randomSeed(analogRead(A5));

  setupGame();

  startGame(); 

}

void loop()
{
  //testButtons2();

   readIncomingDatas();


  if(canPlay1 == 1) {
    startLeds1();
    for (int i = 0; i < nbrOfButtons; i++)
    {
      if (buttons1[i].getSingleDebouncedPress())
      {
        checkIfWellPlayedPlayer1(i);
      }
    }
  }

  if(canPlay2 == 1) {
    startLeds2();

    for (int i = 0; i < nbrOfButtons; i++)
    {
      if (buttons2[i].getSingleDebouncedPress())
      {
        checkIfWellPlayedPlayer2(i);
      }
    }  
  }

  endGame();  
  
}

void checkIfWellPlayedPlayer1(int i) {
  if(i == gamingLeds1[nbrRoundPlayed1]) {
    nbrRoundPlayed1++;
    return 1;
  } else {
    sendCommand("error:1");
    return 0;
  }
}

void checkIfWellPlayedPlayer2(int i) {
  if(i == gamingLeds2[nbrRoundPlayed2]) {
    nbrRoundPlayed2++;
    return 1;
  } else {
    sendCommand("error:2");
    return 0;
  }
}


void setupGame()
{

  //set variables default
  
  canPlay1 = 0;
  canPlay2 = 0;

  lastBlink1 = 0;
  lastBlink2 = 0;

  lastBlinkState1 = 0;
  lastBlinkState2 = 0;

  nbrRoundPlayed1 = 0;
  nbrRoundPlayed2 = 0;

  ledStarted1 = -1;
  ledStarted2 = -1;



  //turn off the lights
  for (int i = 0; i < nbrOfLeds; i++)
  {
    pinMode(leds1[i], OUTPUT);
    pinMode(leds2[i], OUTPUT);

    digitalWrite(leds1[i], off());
    digitalWrite(leds2[i], off());
  }

  //set the lights to play for each player
  for (int i = 0; i < nbrRound; i++)
  {
    int _randomLed1;
    do
    {
      _randomLed1 = random(0, nbrOfLeds);
    } while (_randomLed1 == randomLed1);
    randomLed1 = _randomLed1;
    gamingLeds1[i] = randomLed1;

    int _randomLed2;
    do
    {
      _randomLed2 = random(0, nbrOfLeds);
    } while (_randomLed2 == randomLed2);
    randomLed2 = _randomLed2;
    gamingLeds2[i] = randomLed2;
  }

  /* for (int i = 0; i < nbrRound; i++)
  {
    Serial.print(gamingLeds1[i]);
    Serial.print(" ");
    Serial.println(gamingLeds2[i]);
  } */
  
}

void startLeds1() {
  int led1 = gamingLeds1[nbrRoundPlayed1];
  
  if(ledStarted1 != led1) {
    digitalWrite(leds1[ledStarted1], off());
    digitalWrite(leds1[led1], on());
    ledStarted1 = led1;
  } 

  
}

void startLeds2() {
  int led2 = gamingLeds2[nbrRoundPlayed2];
  if(ledStarted2 != led2) {
      digitalWrite(leds2[ledStarted2], off());
      digitalWrite(leds2[led2], on());
      ledStarted2 = led2;
  } 
}

void endGame() {
  if(nbrRoundPlayed1 == nbrRound) {
    winner += 1;
    /* for(int j = 0; j < nbrOfLeds; j++) {
      digitalWrite(leds1[j], off());
    } */
    if(canPlay1 == 1) {
      sendCommand("stop:1");
      blinkLightWinner1();
    }
    canPlay1 = 0;

  } 
  
  if(nbrRoundPlayed2 == nbrRound) {
    winner += 2;
    /* for(int j = 0; j < nbrOfLeds; j++) {
      digitalWrite(leds2[j], off());
    } */
    if(canPlay2 == 1) {
      sendCommand("stop:2");
      blinkLightWinner2();
    }
    canPlay2 = 0;
  }
}

void blinkLightWinner1() {
  /* for(int i = 0; i < 10; i++) {
    if(millis() - lastBlink1 > 100 ) {
      for(int j = 0; j < nbrOfLeds; j++) {
        digitalWrite(leds1[j], lastBlinkState1 == 0 ? on() : off());
      }
      lastBlink1 = millis();
      lastBlinkState1 = lastBlinkState1 == 0 ? 1 : 0;
    } 
  } */
  for(int j = 0; j < nbrOfLeds; j++) {
    digitalWrite(leds1[j], off());
  }

}

void blinkLightWinner2() {
  /* for(int i = 0; i < 10; i++) {
    if(millis() - lastBlink2 > 100 ) {
      for(int j = 0; j < nbrOfLeds; j++) {
        digitalWrite(leds2[j], lastBlinkState2 == 0 ? on() : off());
      }
      lastBlink2 = millis();
      lastBlinkState2 = lastBlinkState2 == 0 ? 1 : 0;
    } 
  } */
    for(int j = 0; j < nbrOfLeds; j++) {
      digitalWrite(leds2[j], off());
    }
}

void startGame() {
  canPlay1 = 1;
  canPlay2 = 1;
}

void readIncomingDatas() {
  if(Serial.available() > 0){
      String s = Serial.readStringUntil("\n");
      Serial.println((String) "Received : " + s);

      int delim = s.indexOf(':');
      String command = s.substring(0,delim);
      String params = "";
      if(delim != s.length() - 1) {
        params = s.substring(delim + 1, s.length());
      }

      Serial.println("Command : " + command + ", params : " + params);

      if(command == "start") {
        startGame();
      } 

      if(command == "reset") {
        setupGame();
      }

      if(command == "nbrrounds") {
        setupNbrRound(params.toInt());
      }
      
  }
}

void setupNbrRound(int nbr) {
  nbrRound = nbr;
  setupGame();
}

void sendCommand(String command) {
  Serial.println((String) "c|" + command);
}


void testButtons1() {
  for(int i = 0; i < nbrOfButtons; i++) {
    if(buttons1[i].isPressed()) {
      digitalWrite(leds1[i], off());
    }
  }
}

void testButtons2() {
  for(int i = 0; i < nbrOfButtons; i++) {
    if(buttons2[i].isPressed()) {
      digitalWrite(leds2[i], on());
    }
  }
}