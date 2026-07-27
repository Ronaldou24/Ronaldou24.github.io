function seCorre() {
    var cinicial    = parseInt(document.getElementById('inicio').value); 
    var apuesta     = parseInt(document.getElementById('apuesta').value);
    var num_jugador = parseInt(document.getElementById('numero').value); 
    
    var mensaje = "Mensaje";
    var elganador = parseInt(Math.random() * 6);
    if(num_jugador == elganador){
        cinicial = cinicial + apuesta;
        mensaje = "En hora buena, ganaste";
    }else{
        cinicial = cinicial - apuesta;
        mensaje = "Suerte para la próxima";
    }
    
    document.getElementById('inicio').value = cinicial;
    document.getElementById('numero').value = "";
    document.getElementById('resultado').innerHTML = elganador;
    document.getElementById('mensaje').innerHTML = mensaje;
}