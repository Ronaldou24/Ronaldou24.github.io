<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
#chartContainer {
  width: 60%;
  height: 400px;
}
    </style>
</head>
<body>
    <!-- En esta ocasión si vamos a mostrar desde el inicio el "producto final" -->
<div id="chartContainer">
</div>
<!-- ¿Y esto de abajo qué era? -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
// ¿Qué es chart? Es un ...
// ¿y qué es .Chart ?
// ¿y qué hay adentro de .Chart()?
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true, // ¿Qué pasa si lo cambias a false?
	exportEnabled: true, // ¿Qué pasa si lo cambias a false?
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	
  title:{
		text: "Inventario Ropa" //Intenta cambiarlo
	},
  
  axisY: {
      includeZero: false,  //Intenta cambiando a false
      title: "Piezas" //Intenta cambiarlo
    },
  
  axisX: {
      title: "Tipo de Ropa", //Intenta cambiarlo
      labelAngle: 0 //Intenta cambiar a cero
    },
	
  data: [{
		type: "column", //intenta cambier el type a bar, line, area, pie, doughnut, stepLine etc
		//indexLabel: "{y}", //Checa que pasa quitando el comentario o agregando "${y}"
		indexLabelFontColor: "gray", //Intenta cambiar
    indexLabelFontSize: 20, //Intenta cambiar
		indexLabelPlacement: "outside", //Intenta cambiando a inside o auto
		//¿Qué es dataPoints? ¿Y qué contiene?
    dataPoints: [
			{ label: "Camisetas", y: 50 }, // Intenta cambiando valores
			{ label: "Zapatos", y: 60}, //Intenta cambiando a \u2506
			{ label: "Pantalones", y: 20 },
			{ label: "Corbatas", y: 30 },
			{ label: "Bufandas", y: 20 }
		]
	}]
});

// ¿Que era chart? , ¿De qué tipo? , ¿Qué es render()?
chart.render();

</script>
</body>
</html>