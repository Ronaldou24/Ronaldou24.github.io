function obtenerSubtotal(){
    var pre1 = document.getElementById('precio1').value;
    var pre2 = document.getElementById('precio2').value;
    var pre3 = document.getElementById('precio3').value;

    var cant1 = document.getElementById('cant1').value;
    var cant2 = document.getElementById('cant2').value;
    var cant3 = document.getElementById('cant3').value;

    
    var subtotal = (pre1 * cant1) + (pre2 * cant2) + (pre3 * cant3);
    document.getElementById('subtotal').value = subtotal;
}

function pagar(){
    var sub = document.getElementById('subtotal').value;
    var sub = parseInt(sub);
    var saldo = document.getElementById('saldo').value;
    var saldo = parseInt(saldo)
    var total = saldo - sub
    document.getElementById('total').value = total
}
