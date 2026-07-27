import json
robot ={
  "robot_id": 101,
  "sensores": ["temperatura", "proximidad", "temperatura"],
  "lecturas": [25.5, 1.2]
}
json.dumps(robot) # diccionario a json
set(robot["sensores"]) # El set elimina los duplicados, pero no mantiene el orden. Si quieres mantener el orden, puedes usar un diccionario con claves únicas:
new_id = 102
robot["robot_id"] = new_id #ACTUALIZA EL ID
robot["falla"] = 99.9 #AGREGA UN NUEVO ELEMENTO 

