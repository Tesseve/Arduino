let serial; // variable to hold an instance of the serialport library
let portName; // fill in your serial port name here

let width = window.innerWidth;
let height = window.innerHeight;

let millisecs = [];
let secs = [];
let mins = [];

let timers = [];

function setup() {
  createCanvas(width, height);

  // serial
  serial = new p5.SerialPort(); // make a new instance of the serialport library
  serial.on("list", printList); // set a callback function for the serialport list event
  serial.on("connected", serverConnected); // callback for connecting to the server
  serial.on("open", portOpen); // callback for the port opening
  serial.on("data", serialEvent); // callback for when new data arrives
  serial.on("error", serialError); // callback for errors
  serial.on("close", portClose); // callback for the port closing

  serial.list(); // list the serial ports
  serial.open(portName); // open a serial port

  init();
}

// get the list of ports:
function printList(portList) {
  // portList is an array of serial port names
  for (let i = 0; i < portList.length; i++) {
    console.log(i + ": " + portList[i]);
  }
}

function serverConnected() {
  console.log("connected to server.");
}

function portOpen() {
  console.log("the serial port is opened.");
}

function serialEvent() {}

function serialError(err) {
  console.log("Something went wrong with the serial port. " + err);
}

function portClose() {
  console.log("The serial port is closed.");
}

function draw() {}

function init() {
  timers = [];
  addChronos();
}

function addChronos() {
  addChrono(0);
  addChrono(1);
}

function startCounters() {
  startCounter(0);
  startCounter(1);
}

function stopCounters() {
  stopCounter(0);
  stopCounter(1);
}

function startCounter(id) {
  timer(id);
}

function stopCounter(id) {
    clearTimeout(timers[id]);
}

function resetCounters() {
  resetCounter(0);
  resetCounter(1);
}

function resetCounter(id) {
    clearTimeout(timers[id]);
    mins[id] = 0;
    secs[id] = 0;
    millisecs[id] = 0;
    document.getElementById('chrono'+ id).innerHTML = "00:00:00";
}


function addChrono(id) {
    let chrono = document.createElement("div");
    chrono.id = "chrono" + id;
    chrono.className = "chrono";
    chrono.innerHTML = "00:00:00";
    document.getElementById("section-chrono"+id).appendChild(chrono);
}

function tick(id){
  millisecs[id]++;
  if ( millisecs[id] >= 60) {
    millisecs[id] = 0;
    secs[id]++;
    if (secs[id] >= 60) {
      secs[id] = 0;
      mins[id]++;
    }
  }
}
function add(id) {
  mins[id] = mins[id] || 0;
  secs[id] = secs[id] || 0;
  millisecs[id] = millisecs[id] || 0;
  tick(id);
  document.getElementById('chrono'+ id).innerHTML = (mins[id] > 9 ? mins[id] : "0" + mins[id])
      + ":" + (secs[id] > 9 ? secs[id] : "0" + secs[id])
      + ":" + (millisecs[id] > 9 ? millisecs[id] : "0" + millisecs[id]);
  timer(id);
}
function timer(id) {
  timers[id] = (setTimeout(() => add(id), 10));
}
