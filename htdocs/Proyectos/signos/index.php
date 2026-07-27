<?php   # verificar que se presiono el boton
    if(isset($_GET['buscar'])) {
        $_option = $_GET['signo'];
        switch ($_option){
            case 1:
                echo "Leo";
                header('location: pages/leo.php');
                break;
            case 2:
                echo "Piscis";
                header('location: pages/piscis.php');
                break;
            case 3:
                echo "Acuario";
                header('location: pages/acuario.php');
                break;
            
            case 4:
                echo "Capricornio";
                header('location: pages/capricornio.php');
                break;

            case 5:
                echo "Cancer";
                header('location: pages/cancer.php');
                break;

                case 5:
                    echo "Cancer";
                    header('location: pages/cancer.php');
                    break;

                case 6:
                    echo "Tauro";
                    header('location: pages/tauro.php');
                    break;

                case 7:
                    echo "Sagitario";
                    header('location: pages/sagitario.php');
                    break;

                case 8:
                    echo "Scorpio";
                    header('location: pages/scorpio.php');
                    break;
                        

                case 9:
                    echo "Libra";
                    header('location: pages/libra.php');
                    break;

                case 10:
                    echo "Virgo";
                    header('location: pages/virgo.php');
                    break;

                case 11:
                    echo "Aries";
                    header('location: pages/aries.php');
                    break;

                case 12:
                    echo "Geminis";
                    header('location: pages/geminis.php');
                    break;

                    
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signos Zodiacales</title>
</head>
<link rel="stylesheet" href="../css/index.css">
<style>  
  body {
    font-family: Arial, sans-serif;
    background-color: #007bff;
    margin: 0;
    padding: 0;

}

.container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: black;
    height: 50px;
    font-size: 70px;
}

form {
    text-align: center;
}


select, input[type="submit"] {
    padding: 10px;
    margin: 10px;
    border: 1px solid #ccc;
    
    border-radius: 5px;
    font-size: 30px;

}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;

}

input[type="submit"]:hover {
    background-color: #0056b3;
}
</style>
<body>
    <center>
    <h1>Casa</h1>
    <form action="" method="GET">
        <select name="signo" id="">
            <option value="1">Leo</option>
            <option value="2">Piscis</option>
            <option value="3">Acuario</option>
            <option value="4">Capricornio</option>
            <option value="5">Cancer</option>
            <option value="6">Tauro</option>
            <option value="7">Sagitario</option>
            <option value="8">Scorpio</option>
            <option value="9">Libra</option>
            <option value="10">Virgo</option>
            <option value="11">Aries</option>
            <option value="12">Geminis</option>
            
        </select>
        <input type="submit" value="buscar" name="buscar">
    </form>
    </center>
</body>
</html>