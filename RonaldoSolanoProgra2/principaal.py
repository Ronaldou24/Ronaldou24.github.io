from estructura import Pila

def esta_equilibrada(expresion):
    pila = Pila()
    pares = {')': '(', '}': '{', ']': '['}
    
    for caracter in expresion:
        if caracter in '({[':
            pila.empujar(caracter)
        elif caracter in ')}]':
            if pila.esta_vacia() or pila.sacar() != pares[caracter]:
                return False
    return pila.esta_vacia()

def infija_a_postfija(expresion):
    prioridad = {'+': 1, '-': 1, '*': 2, '/': 2, '%': 2, '(': 0}
    pila = Pila()
    salida = []
    
    for caracter in expresion:
        if caracter.isalnum():
            salida.append(caracter)
        elif caracter == '(':
            pila.empujar(caracter)
        elif caracter == ')':
            while not pila.esta_vacia() and pila.ver_tope() != '(':
                salida.append(pila.sacar())
            pila.sacar()
        else: 
            while (not pila.esta_vacia() and 
                   prioridad.get(pila.ver_tope(), 0) >= prioridad.get(caracter, 0)):
                salida.append(pila.sacar())
            pila.empujar(caracter)
            
    while not pila.esta_vacia():
        salida.append(pila.sacar())
    return "".join(salida)

def validar_formato_xy(cadena):
    if '&' not in cadena:
        return False
    
    parte_x, parte_y = cadena.split('&', 1)
    pila = Pila()
    
    for caracter in parte_x:
        pila.empujar(caracter)
        
    for caracter in parte_y:
        if pila.esta_vacia() or pila.sacar() != caracter:
            return False
            
    return pila.esta_vacia()

if __name__ == "__main__":
    print("1. Equilibrio de símbolos")
    print("2. Infija a Postfija")
    print("3. Validar formato X & Y")
    opcion = input("Seleccione una opción: ")

    if opcion == "1":
        exp = input("Ingrese la expresión: ")
        print("Equilibrada" if esta_equilibrada(exp) else "No equilibrada")
    elif opcion == "2":
        exp = input("Ingrese expresión infija (ej: (A+B)*C): ")
        print("Postfija:", infija_a_postfija(exp))
    elif opcion == "3":
        exp = input("Ingrese cadena (ej: abc&cba): ")
        print("Formato correcto" if validar_formato_xy(exp) else "Formato incorrecto")