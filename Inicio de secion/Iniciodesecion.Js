function mostrarPassword(){

let pass = document.getElementById("password");

if(pass.type === "password"){
pass.type = "text";
}else{
pass.type = "password";
}

}

function login(){

let correo = document.getElementById("correo").value;
let pass = document.getElementById("password").value;

if(correo === "" || pass === ""){
alert("Por favor llena todos los campos");
return;
}

alert("Intentando iniciar sesión con: " + correo);

}

function recuperar(){
alert("Se enviará un correo para recuperar tu contraseña");
}
function mostrarPassword(){

let pass = document.getElementById("password");

if(pass.type === "password"){
pass.type = "text";
}else{
pass.type = "password";
}

}

function login(){

let correo = document.getElementById("correo").value;
let pass = document.getElementById("password").value;

if(correo === "" || pass === ""){
alert("Por favor llena todos los campos");
return;
}

alert("Intentando iniciar sesión con: " + correo);

}

function recuperar(){
alert("Se enviará un correo para recuperar tu contraseña");
}


/* LOGIN CON GOOGLE */

function loginGoogle(){
alert("Aquí se conectaría con la autenticación de Google");
}