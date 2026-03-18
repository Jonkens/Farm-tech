let tabla = document.querySelector("#tablaSalud tbody")

function guardarSalud(){

let nombre = document.getElementById("nombre").value
let peso = document.getElementById("peso").value
let temp = document.getElementById("temperatura").value
let estado = document.getElementById("estado").value
let enfermedad = document.getElementById("enfermedad").value
let vacuna = document.getElementById("vacuna").value

if(nombre === ""){
alert("Ingrese nombre del animal")
return
}

let fila = tabla.insertRow()

fila.insertCell(0).innerText = nombre
fila.insertCell(1).innerText = peso
fila.insertCell(2).innerText = temp
fila.insertCell(3).innerText = estado
fila.insertCell(4).innerText = enfermedad
fila.insertCell(5).innerText = vacuna

limpiar()

}

function limpiar(){

document.getElementById("nombre").value = ""
document.getElementById("peso").value = ""
document.getElementById("temperatura").value = ""
document.getElementById("vacuna").value = ""

}

function buscar(){

let texto = document.getElementById("buscar").value.toLowerCase()
let filas = document.querySelectorAll("#tablaSalud tbody tr")

filas.forEach(fila=>{
fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none"
})

}

function volver(){

window.location.href = "index.html"

}