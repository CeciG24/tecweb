// JSON BASE
var baseJSON = {
  "precio": 0.0,
  "unidades": 1,
  "modelo": "XX-000",
  "marca": "NA",
  "detalles": "NA",
  "imagen": "img/default.png"
};

var edit = false; // Bandera para saber si estamos editando

// CUANDO EL DOCUMENTO ESTÉ LISTO
$(document).ready(function () {
  // Mostrar JSON base
  $("#description").val(JSON.stringify(baseJSON, null, 2));

  // Cargar lista inicial
  listarProductos();

  // Vincular eventos
  $("#product-form").submit(agregarOEditarProducto);
  $(".form-inline").submit(buscarProducto);

  // Delegar evento de eliminar
  $(document).on("click", ".product-delete", function () {
    eliminarProducto(this);
  });
    
  // Delegar evento de editar (cuando se hace clic en el nombre del producto)
  $(document).on("click", ".product-item", function (e) {
    e.preventDefault(); // Prevenir navegación del enlace
    editarProducto(this);
  });
});

// LISTAR PRODUCTOS
function listarProductos() {
  $.ajax({
    url: './backend/product-list.php',
    type: 'GET',
    dataType: 'json',
    success: function (productos) {
      if (productos && Object.keys(productos).length > 0) {
        let template = '';

        productos.forEach(producto => {
          let descripcion = `
            <li>precio: ${producto.precio}</li>
            <li>unidades: ${producto.unidades}</li>
            <li>modelo: ${producto.modelo}</li>
            <li>marca: ${producto.marca}</li>
            <li>detalles: ${producto.detalles}</li>
          `;

          template += `
            <tr productId="${producto.id}">
              <td>${producto.id}</td>
              <td><a href="#" class="product-item">${producto.nombre}</a></td>
              <td><ul>${descripcion}</ul></td>
              <td>
                <button class="product-delete btn btn-danger">Eliminar</button>
              </td>
            </tr>
          `;
        });

        $('#products').html(template);
      } else {
        $('#products').html('<tr><td colspan="4" class="text-center">No hay productos disponibles</td></tr>');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error al listar productos:', error);
    }
  });
}

// BUSCAR PRODUCTOS
function buscarProducto(e) {
  e.preventDefault();

  const search = $('#search').val() ? $('#search').val().trim() : '';

  $.ajax({
    url: './backend/product-search.php',
    type: 'GET',
    data: { search: search },
    dataType: 'json',
    success: function (productos) {
      if (!productos || Object.keys(productos).length === 0) {
        $('#product-result').removeClass('d-block').addClass('d-none');
        $('#container').html('<li>No se encontraron productos</li>');
        $('#products').html('<tr><td colspan="4" class="text-center">No hay resultados</td></tr>');
        return;
      }

      let template = '';
      let template_bar = '';

      productos.forEach(producto => {
        let descripcion = `
          <li>precio: ${producto.precio}</li>
          <li>unidades: ${producto.unidades}</li>
          <li>modelo: ${producto.modelo}</li>
          <li>marca: ${producto.marca}</li>
          <li>detalles: ${producto.detalles}</li>
        `;

        template += `
          <tr productId="${producto.id}">
            <td>${producto.id}</td>
            <td><a href="#" class="product-item">${producto.nombre}</a></td>
            <td><ul>${descripcion}</ul></td>
            <td><button class="product-delete btn btn-danger">Eliminar</button></td>
          </tr>
        `;

        template_bar += `<li>${producto.nombre}</li>`;
      });

      $('#product-result').removeClass('d-none').addClass('d-block');
      $('#container').html(template_bar);
      $('#products').html(template);
    },
    error: function (xhr, status, error) {
      console.error('[buscarProducto] error:', status, error);
      $('#product-result').removeClass('d-none').addClass('d-block');
      $('#container').html('<li style="color:red">Error en la búsqueda.</li>');
    }
  });
}

// AGREGAR O EDITAR PRODUCTO
function agregarOEditarProducto(e) {
  e.preventDefault();

  // Obtener y validar el nombre
  var nombre = $('#name').val().trim();
  if (!nombre) {
    alert('El nombre del producto es obligatorio');
    return;
  }

  // Obtener y validar el JSON
  var productoJsonString = $('#description').val();
  var finalJSON;
  
  try {
    finalJSON = JSON.parse(productoJsonString);
  } catch (error) {
    alert('El JSON de la descripción no es válido');
    return;
  }

  // Validaciones de campos obligatorios
  if (!finalJSON.marca || finalJSON.marca.trim() === '') {
    alert('La marca es obligatoria');
    return;
  }

  if (!finalJSON.modelo || finalJSON.modelo.trim() === '') {
    alert('El modelo es obligatorio');
    return;
  }

  if (!finalJSON.precio || isNaN(finalJSON.precio) || finalJSON.precio <= 0) {
    alert('El precio debe ser un número mayor a 0');
    return;
  }

  if (!finalJSON.unidades || isNaN(finalJSON.unidades) || finalJSON.unidades < 0) {
    alert('Las unidades deben ser un número mayor o igual a 0');
    return;
  }

  // Validación de longitud de campos
  if (nombre.length > 100) {
    alert('El nombre no puede exceder 100 caracteres');
    return;
  }

  if (finalJSON.marca.length > 25) {
    alert('La marca no puede exceder 25 caracteres');
    return;
  }

  if (finalJSON.modelo.length > 25) {
    alert('El modelo no puede exceder 25 caracteres');
    return;
  }

  if (finalJSON.detalles && finalJSON.detalles.length > 250) {
    alert('Los detalles no pueden exceder 250 caracteres');
    return;
  }

  // Agregar el nombre al JSON
  finalJSON['nombre'] = nombre;
  
  // Si estamos en modo edición, agregar el ID
  if (edit) {
    finalJSON['id'] = $('#productId').val();
  }
  
  productoJsonString = JSON.stringify(finalJSON, null, 2);

  // Determinar URL según si es agregar o editar
  const url = edit ? './backend/product-update.php' : './backend/product-add.php';

  $.ajax({
    url: url,
    type: 'POST',
    contentType: 'application/json;charset=UTF-8',
    data: productoJsonString,
    dataType: 'json',
    success: function (respuesta) {
      console.log(respuesta);
      
      let template_bar = `
        <li style="list-style: none;">status: ${respuesta.status}</li>
        <li style="list-style: none;">message: ${respuesta.message}</li>
      `;

      $('#product-result').removeClass('d-none').addClass('d-block');
      $('#container').html(template_bar);
      
      // Solo limpiar y recargar si fue exitoso
      if (respuesta.status === 'success') {
        listarProductos();
        
        // Limpiar formulario y resetear modo edición
        $('#name').val('');
        $('#description').val(JSON.stringify(baseJSON, null, 2));
        $('#productId').val('');
        edit = false;
        
        // Cambiar texto del botón
        $('#product-form button[type="submit"]').text('Agregar Producto');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error al procesar producto:', error);
      console.log('Respuesta del servidor:', xhr.responseText);
      
      let template_bar = `
        <li style="list-style: none; color: red;">status: error</li>
        <li style="list-style: none; color: red;">message: Error al procesar la solicitud</li>
      `;
      $('#product-result').removeClass('d-none').addClass('d-block');
      $('#container').html(template_bar);
    }
  });
}

// ELIMINAR PRODUCTO
function eliminarProducto(button) {
  if (confirm("¿De verdad deseas eliminar el Producto?")) {
    var id = $(button).closest('tr').attr('productId');
    
    console.log('ID a eliminar:', id);

    $.ajax({
      url: './backend/product-delete.php',
      type: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function (respuesta) {
        console.log(respuesta);
        
        let template_bar = `
          <li style="list-style: none;">status: ${respuesta.status}</li>
          <li style="list-style: none;">message: ${respuesta.message}</li>
        `;

        $('#product-result').removeClass('d-none').addClass('d-block');
        $('#container').html(template_bar);
        listarProductos();
      },
      error: function (xhr, status, error) {
        console.error('Error al eliminar producto:', error);
        console.log('Respuesta del servidor:', xhr.responseText);
      }
    });
  }
}

// EDITAR PRODUCTO - Cargar datos en el formulario
function editarProducto(button) { 
  // Obtener el ID del producto
  let id = $(button).closest('tr').attr('productId');
  
  console.log('Editando producto ID:', id);

  // Obtener los datos del producto desde el servidor
  $.ajax({
    url: './backend/product-single.php',
    type: 'GET',
    data: { id: id },
    dataType: 'json',
    success: function (producto) {
      console.log('Datos del producto:', producto);
      
      // Llenar el formulario con los datos del producto
      $('#name').val(producto.nombre);
      
      // Crear el JSON con los detalles del producto
      let productoJSON = {
        "precio": parseFloat(producto.precio),
        "unidades": parseInt(producto.unidades),
        "modelo": producto.modelo,
        "marca": producto.marca,
        "detalles": producto.detalles,
        "imagen": producto.imagen
      };
      
      $('#description').val(JSON.stringify(productoJSON, null, 2));
      $('#productId').val(producto.id);
      
      // Activar modo edición
      edit = true;
      
      // Cambiar el texto del botón
      $('#product-form button[type="submit"]').text('Actualizar Producto');
      
      // Hacer scroll al formulario
      $('html, body').animate({
        scrollTop: $("#product-form").offset().top
      }, 500);
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener producto:', error);
      console.log('Respuesta del servidor:', xhr.responseText);
      alert('Error al cargar los datos del producto');
    }
  });
}
