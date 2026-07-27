// Funcion Para calcular edad
function Muestra(){
    var dato = prompt("ingresa el año de nacimiento de tu novia")
    var edad = 2024 - parseInt(dato);
    document.getElementById('dato').innerHTML = "Tu novia tiene " + edad + " años";

}