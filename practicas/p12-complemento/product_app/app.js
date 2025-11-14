// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

$(document).ready(function(){
    let edit = false;

    function validarCampo(idCampo) {
  const valor = $(idCampo).val().trim();
        let valido = true;
        let mensaje = "";
        let mensajeOk = "Campo válido.";

  switch (idCampo) {
    case "#name":
      if (valor === "") { mensaje = "El nombre es obligatorio."; valido = false; }
      break;
    case "#price":
      if (valor === "" || parseFloat(valor) <= 0) { mensaje = "El precio debe ser mayor a 0."; valido = false; }
      break;
    case "#units":
      if (valor === "" || parseInt(valor) <= 0) { mensaje = "Las unidades deben ser mayores a 0."; valido = false; }
      break;
    case "#model":
      if (valor === "") { mensaje = "El modelo es obligatorio."; valido = false; }
      break;
    case "#brand":
      if (valor === "") { mensaje = "La marca es obligatoria."; valido = false; }
      break;
    case "#details":
      if (valor === "") { mensaje = "Los detalles son obligatorios."; valido = false; }
      break;
  }

  // Muestra mensaje visual
  if (!valido) {
    $(idCampo).addClass("is-invalid");
    if ($(idCampo).next(".invalid-feedback").length === 0) {
      $(idCampo).after(`<div class="invalid-feedback">${mensaje}</div>`);
    } else {
      $(idCampo).next(".invalid-feedback").text(mensaje);
    }
  } else {
    // ✅ Campo válido
    $(idCampo).removeClass("is-invalid").addClass("is-valid");
    $(idCampo).after(`<div class="valid-feedback">${mensajeOk}</div>`);
  }

  return valido;
}

// --- Validar cada campo al perder el foco (3.1) ---
$("#name, #price, #units, #model, #brand, #details").on("blur", function() {
  validarCampo("#" + $(this).attr("id"));
});

// --- Validar todo antes de enviar (3.2) ---
function validarFormulario() {
  let valido = true;
  const campos = ["#name", "#price", "#units", "#model", "#brand", "#details"];
  campos.forEach(id => {
    if (!validarCampo(id)) valido = false;
  });
  return valido;
}

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                const productos = JSON.parse(response);
            
                // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                if(Object.keys(productos).length > 0) {
                    // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                    let template = '';

                    productos.forEach(producto => {
                        // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                        let descripcion = '';
                        descripcion += '<li>precio: '+producto.precio+'</li>';
                        descripcion += '<li>unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>marca: '+producto.marca+'</li>';
                        descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger" onclick="eliminarProducto()">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                    $('#products').html(template);
                }
            }
        });
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                        const productos = JSON.parse(response);
                        
                        // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                        if(Object.keys(productos).length > 0) {
                            // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                            let template = '';
                            let template_bar = '';

                            productos.forEach(producto => {
                                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                                let descripcion = '';
                                descripcion += '<li>precio: '+producto.precio+'</li>';
                                descripcion += '<li>unidades: '+producto.unidades+'</li>';
                                descripcion += '<li>modelo: '+producto.modelo+'</li>';
                                descripcion += '<li>marca: '+producto.marca+'</li>';
                                descripcion += '<li>detalles: '+producto.detalles+'</li>';
                            
                                template += `
                                    <tr productId="${producto.id}">
                                        <td>${producto.id}</td>
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                        <td><ul>${descripcion}</ul></td>
                                        <td>
                                            <button class="product-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;

                                template_bar += `
                                    <li>${producto.nombre}</il>
                                `;
                            });
                            // SE HACE VISIBLE LA BARRA DE ESTADO
                            $('#product-result').show();
                            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
                            $('#container').html(template_bar);
                            // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                            $('#products').html(template);    
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
        }
    });

    $('#product-form').submit(e => {
  e.preventDefault();

  // --- Validar todos los campos antes de continuar ---
  if (!validarFormulario()) {
    alert("⚠️ Por favor corrige los errores antes de agregar el producto.");
    return; // ← Detiene el envío completamente
        }
        
    // --- VALIDAR NOMBRE EN EL SERVIDOR (asíncrono) ---
let nombreTimeout; // para evitar muchas peticiones mientras escribe

$('#name').on('keyup blur', function () {
  clearTimeout(nombreTimeout);
  const nombre = $(this).val().trim();

  // No consultar si está vacío
  if (nombre === "") {
    $('#name').removeClass("is-valid is-invalid");
    $(this).next(".valid-feedback, .invalid-feedback").remove();
    return;
  }

  // Esperar 500 ms después de que deje de escribir
  nombreTimeout = setTimeout(() => {
    $.ajax({
      url: './backend/product-check.php',
      type: 'GET',
      data: { nombre: nombre },
      success: function (response) {
        const data = JSON.parse(response);
        $('#name').next(".valid-feedback, .invalid-feedback").remove();

        if (data.existe) {
          // ❌ El nombre ya existe
          $('#name').removeClass("is-valid").addClass("is-invalid");
          $('#name').after('<div class="invalid-feedback">⚠️ Este nombre ya está registrado.</div>');
        } else {
          // ✅ Nombre disponible
          $('#name').removeClass("is-invalid").addClass("is-valid");
          $('#name').after('<div class="valid-feedback">✅ Nombre disponible.</div>');
        }
      },
      error: function () {
        console.error("Error al validar el nombre en el servidor.");
      }
    });
  }, 500); // medio segundo de espera
});


  // --- Si todo es válido, crear el JSON para enviar ---
  let postData = {
    nombre: $('#name').val().trim(),
    precio: parseFloat($('#price').val()),
    unidades: parseInt($('#units').val()),
    modelo: $('#model').val().trim(),
    marca: $('#brand').val().trim(),
    detalles: $('#details').val().trim(),
    imagen: $('#image').val() || "img/default.png",
    id: $('#productId').val()
  };

  const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';

  $.post(url, postData, (response) => {
    let respuesta = JSON.parse(response);
    let template_bar = `
      <li style="list-style: none;">status: ${respuesta.status}</li>
      <li style="list-style: none;">message: ${respuesta.message}</li>
    `;

    $('#name').val('');
    $('#price').val('');
    $('#units').val('');
    $('#model').val('');
    $('#brand').val('');
    $('#details').val('');
    $('#image').val('');
    $('#productId').val('');

    $('#product-result').show();
    $('#container').html(template_bar);
    listarProductos();
    edit = false;
  });
});

    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', {id}, (response) => {
                $('#product-result').hide();
                listarProductos();
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        $.post('./backend/product-single.php', {id}, (response) => {
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let product = JSON.parse(response);
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#name').val(product.nombre);
            // EL ID SE INSERTA EN UN CAMPO OCULTO PARA USARLO DESPUÉS PARA LA ACTUALIZACIÓN
            $('#productId').val(product.id);
            // SE ELIMINA nombre, eliminado E id PARA PODER MOSTRAR EL JSON EN EL <textarea>
            delete(product.nombre);
            delete(product.eliminado);
            delete(product.id);
            // SE CONVIERTE EL OBJETO JSON EN STRING
            let JsonString = JSON.stringify(product,null,2);
            // SE MUESTRA STRING EN EL <textarea>
            $('#description').val(JsonString);
            
            // SE PONE LA BANDERA DE EDICIÓN EN true
            edit = true;
        });
        e.preventDefault();
    });    
});