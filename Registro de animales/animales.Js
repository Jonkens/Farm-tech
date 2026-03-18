let tabla = document.getElementById("tabla").getElementsByTagName("tbody")[0]

function guardarAnimal(){

let nombre = document.getElementById("nombre").value
let peso = document.getElementById("peso").value
let raza = document.getElementById("raza").value
let edad = document.getElementById("edad").value

let fila = tabla.insertRow()

fila.insertCell(0).innerText = nombre
fila.insertCell(1).innerText = raza
fila.insertCell(2).innerText = peso
fila.insertCell(3).innerText = edad

}

function eliminarAnimal(){

let tabla = document.getElementById("tabla")

if(tabla.rows.length > 1){

tabla.deleteRow(tabla.rows.length-1)

}

}

function editarAnimal(){

alert("Selecciona un animal para editar (puedes programarlo después)")

}

function buscarAnimal(){

let input = document.getElementById("buscar").value.toLowerCase()

let filas = document.querySelectorAll("#tabla tbody tr")

filas.forEach(fila=>{

let texto = fila.innerText.toLowerCase()

fila.style.display = texto.includes(input) ? "" : "none"

})

}

function abrirSalud(){

window.location.href = "salud.html"

}