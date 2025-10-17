// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarID(e) {
    /**
     * Revisar la siguiente información para entender porqué usar event.preventDefault();
     * http://qbit.com.mx/blog/2013/01/07/la-diferencia-entre-return-false-preventdefault-y-stoppropagation-en-jquery/#:~:text=PreventDefault()%20se%20utiliza%20para,escuche%20a%20trav%C3%A9s%20del%20DOM
     * https://www.geeksforgeeks.org/when-to-use-preventdefault-vs-return-false-in-javascript/
     */
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    var id = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);    // similar a eval('('+client.responseText+')');
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

function buscarNombre(e) {
    e.preventDefault();

    // SE OBTIENE EL NOMBRE A BUSCAR
    var nombre = document.getElementById('searchName').value.trim();

    if (nombre === "") {
        alert("Por favor, ingresa un nombre para buscar");
        return;
    }

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n' + client.responseText);
            
            let productos = JSON.parse(client.responseText);
            let template = "";

            // Si hay resultados
            if (productos.length > 0) {
                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                });
            } else {
                template = `<tr><td colspan="3">No se encontraron productos con ese nombre.</td></tr>`;
            }

            document.getElementById("productos").innerHTML = template;
        }
    };
    client.send("nombre=" + encodeURIComponent(nombre));
}

// FUNCIÓN PARA CREAR OBJETO XMLHttpRequest
function getXMLHttpRequest() {
  if (window.XMLHttpRequest) {
    return new XMLHttpRequest();
  } else {
    // Para navegadores antiguos
    return new ActiveXObject("Microsoft.XMLHTTP");
  }
}

// FUNCIÓN PARA AGREGAR PRODUCTO
function agregarProducto(e) {
  e.preventDefault();

  // --- VALIDACIONES BÁSICAS ---
  const nombre = document.getElementById("name").value.trim();
  const descripcion = document.getElementById("description").value.trim();

  if (nombre === "") {
    alert("El nombre del producto es obligatorio.");
    return;
  }

  if (nombre.length > 100) {
    alert("El nombre del producto no debe superar los 100 caracteres.");
    return;
  }

  if (descripcion === "") {
    alert("Debes ingresar un JSON con los datos del producto.");
    return;
  }

  // Intentar parsear el JSON
  let datosProducto;
  try {
    datosProducto = JSON.parse(descripcion);
  } catch (error) {
    alert("El formato del JSON es inválido. Corrígelo antes de enviar.");
    return;
  }

  // Agregar el nombre al objeto final
  datosProducto["nombre"] = nombre;

  // Convertir nuevamente a JSON string
  const productoJsonString = JSON.stringify(datosProducto, null, 2);

  // --- ENVÍO AL SERVIDOR ---
  const client = getXMLHttpRequest();
  client.open("POST", "./backend/create.php", true);
  client.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

  client.onreadystatechange = function () {
    if (client.readyState === 4) {
      if (client.status === 200) {
        // Mostrar mensaje de éxito o error
        alert(client.responseText);
        // Limpiar campos si se insertó correctamente
        if (client.responseText.includes("exitosamente")) {
          document.getElementById("task-form").reset();
        }
      } else {
        alert("Error de conexión con el servidor.");
      }
    }
  };

  client.send(productoJsonString);
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}