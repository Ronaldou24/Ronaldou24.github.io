    </div>

        <footer>
            
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script>
            function confirma_eliminar(){
                var respuesta = confirm("¿Deseas dar de baja el registro?"); //se usa para confirmaciones con valores de tipo verdadero- falso 
                if (respuesta== true){ //Retornamos true o false dependiendo la funcion 
                    return true;
                }else{
                    return false;
                }
            }
            function confirma_editar(){
                var respuesta = confirm("¿Deseas editar el registro?"); //se usa para confirmaciones con valores de tipo verdadero- falso 
                if (respuesta== true){ //Retornamos true o false dependiendo la funcion 
                    return true;
                }else{
                    return false;
                }
            }
        </script>
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
