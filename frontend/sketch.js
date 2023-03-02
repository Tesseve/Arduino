let serial; // variable to hold an instance of the serialport library
let portName = "/dev/tty.usbmodem11101"; // fill in your serial port name here

let width = window.innerWidth;
let height = window.innerHeight;

let millisecs = [];
let secs = [];
let mins = [];

let errors = [0,0];

let timers = [];

const PUNISHMENT = 2;

const apiUrl = "https://arduino.test/api/";

async function setup() {
  //createCanvas(width, height);

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

  //remove element from dom
  /* const main = document.getElementsByTagName("main");
  main[0].remove(); */
}

function draw() {
  renderErrors();
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
  if(getIsInit() == false){
    fetchScores();
    setIsInit();
  }
}

function portOpen() {
  console.log("the serial port is opened.");
}

function serialEvent() {
  let data = serial.readLine();
  if (data) {
    console.log(data);
    if(data.startsWith("c|")){
      const datas = data.split("|");
      const command = datas[1].split(":")[0];
      const params = datas[1].split(":")[1].split("&");
      console.log("Got command: " + command + " with params: " + params);
     

      parseCommand(command, params);
    }
  }
}

function serialError(err) {
  console.log("Something went wrong with the serial port. " + err);
}

function portClose() {
  console.log("The serial port is closed.");
}



function init() {
  timers = [];
  addChronos();
}

function addChronos() {
  addChrono(0);
  addChrono(1);
}

function startCounters() {
  write("start");

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
  write("reset");
}

function resetCounter(id) {
  clearTimeout(timers[id]);
  mins[id] = 0;
  secs[id] = 0;
  millisecs[id] = 0;
  document.getElementById("chrono" + id).innerHTML = "00:00:00";
}

function addChrono(id) {
  let chrono = document.createElement("div");
  chrono.id = "chrono" + id;
  chrono.className = "chrono";
  chrono.innerHTML = "00:00:00";
  document.getElementById("section-chrono" + id).appendChild(chrono);
}

function tick(id) {
  millisecs[id]++;
  if (millisecs[id] >= 60) {
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
  document.getElementById("chrono" + id).innerHTML =
    (mins[id] > 9 ? mins[id] : "0" + mins[id]) +
    ":" +
    (secs[id] > 9 ? secs[id] : "0" + secs[id]) +
    ":" +
    (millisecs[id] > 9 ? millisecs[id] : "0" + millisecs[id]);
  timer(id);
}

function timer(id) {
  timers[id] = setTimeout(() => add(id), 10);
}

async function getHttp(url) {
  const res = await fetch(apiUrl + url, {
    headers: {
      "Content-Type": "application/json",
      apikey: "AlrU5O9IyTmHw712sR8Wf2EisV0Ichd",
    },
  });
  return await res.json();
}

async function postHttp(url, data) {
  const res = await fetch(apiUrl + url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      apikey: "AlrU5O9IyTmHw712sR8Wf2EisV0Ichd",
    },
    body: JSON.stringify(data),
  });

  return await res.json();
}

async function fetchScores() {
  const json = await getHttp("scores/top");

  console.log(json.data);

  renderTableTopScores(json.data);
}

function renderTableTopScores(datas) {
  const section = document.getElementById("section-scores");


  const table = document.createElement("table");
  table.className = "table";
  table.id = "table-scores";

  const thead = document.createElement("thead");
  const tr = document.createElement("tr");

  for (const mode of datas) {
    const th = document.createElement("th");
    th.innerHTML = mode[0].mode;
    tr.appendChild(th);
  }

  const tbody = document.createElement("tbody");

  for (let i = 0; i < 10; i++) {
    const tr = document.createElement("tr");
    for (const mode of datas) {
      const td = document.createElement("td");
      const score = mode[i];
      const divContent = document.createElement("div");
      divContent.className = "td-content";
      const pName = document.createElement("div");
      pName.innerHTML = score?.player.name ?? "";
      const pValue = document.createElement("div");
      pValue.textContent = score?.value ?? "";

      divContent.appendChild(pName);
      divContent.appendChild(pValue);

      td.appendChild(divContent);
      tr.appendChild(td);
    }
    tbody.appendChild(tr);
  }

  thead.appendChild(tr);
  table.appendChild(tbody);
  table.appendChild(thead);
  section.querySelector("#table-scores")?.remove();

  section.appendChild(table);
}

async function saveScore(name, value, mode) {
  const data = {
    name,
    value,
    mode: mode.toString(),
  };
  try {
    const res = await postHttp("scores", data);
    console.log(res);
    if (res) {
      fetchScores();
    }
  } catch (e) {
    console.log(e);
  }
}



function write(command, params) {
  const query = buildQuery(command, params);
  console.log("sending: " + query);

  serial.write(query + "\n");
}

function buildQuery(command, params) {
  let query = command + ":";
  if (params) {
    query += params.join("&");
  }
  return query;
}


function parseCommand(command, params) {
  if(command == "stop"){
    stopCounter(params[0] - 1);
  } 

  if(command == "error"){
    console.log("error: " + params[0]);
    secs[params[0] - 1] += PUNISHMENT;
    errors[params[0] - 1]++;
  }
}

function renderErrors() {
  for (let i = 0; i < 2; i++) {
    document.getElementById("errors_" + i).innerHTML = "Erreurs : " + errors[i];
  }
}


function getIsInit() {
  const value = localStorage.getItem("init");
  return value == "true";
}

function setIsInit() {
  localStorage.setItem("init", true);
}