function validarFormulario(event) {
      // Obtener los elementos
      const inputNombre = document.getElementById("nombre");
      const inputMarca = document.getElementById("marca");
      const inputModelo = document.getElementById("modelo");
      const inputPrecio = document.getElementById("precio");
      const inputDetalles = document.getElementById("detalles");
      const inputUnidades = document.getElementById("unidades");
      const inputImagen = document.getElementById("imagen");

      // Obtener valores
      const nombre = inputNombre.value.trim();
      const marca = inputMarca.value;
      const modelo = inputModelo.value.trim();
      const precio = parseFloat(inputPrecio.value);
      const detalles = inputDetalles.value.trim();
      const unidades = parseInt(inputUnidades.value);
      const imagen = inputImagen.value.trim();

      // Lista de marcas válidas
      const marcasValidas = ["Samsung", "Xiaomi", "Apple", "Sony"];

      // a. Nombre requerido y <= 100 caracteres
      if (nombre === "" || nombre.length > 100) {
        alert("El nombre es requerido y debe tener 100 caracteres o menos.");
        inputNombre.focus();
        event.preventDefault();
        return false;
      }

      // b. Marca requerida y seleccionada de lista
      if (!marcasValidas.includes(marca)) {
        alert("La marca seleccionada no es válida. Selecciona una opción de la lista.");
        inputMarca.focus();
        event.preventDefault();
        return false;
      }

      // c. Modelo requerido, alfanumérico, <= 25 caracteres
      const modeloRegex = /^[a-zA-Z0-9\s-]+$/;
      if (modelo === "" || modelo.length > 25 || !modeloRegex.test(modelo)) {
        alert(" El modelo es requerido, debe ser alfanumérico y tener 25 caracteres o menos.");
        inputModelo.focus();
        event.preventDefault();
        return false;
      }

      // d. Precio requerido y > 99.99
      if (isNaN(precio) || precio <= 99.99) {
        alert("El precio es requerido y debe ser mayor a 99.99.");
        inputPrecio.focus();
        event.preventDefault();
        return false;
      }

      // e. Detalles opcional pero <= 250 caracteres
      if (detalles.length > 250) {
        alert(" Los detalles no deben exceder 250 caracteres.");
        inputDetalles.focus();
        event.preventDefault();
        return false;
      }

      // f. Unidades requeridas y >= 0
      if (isNaN(unidades) || unidades < 0) {
        alert("Las unidades deben ser un número mayor o igual a 0.");
        inputUnidades.focus();
        event.preventDefault();
        return false;
      }

      // g. Imagen opcional → si no se registra, usar imagen por defecto
      if (imagen === "") {
        const defaultImage = "img/default.png";
        alert("No se seleccionó imagen. Se usará la imagen por defecto: " + defaultImage);
        // Crear un input oculto con la ruta por defecto para enviarla al servidor
        let hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "imagen";
        hiddenInput.value = defaultImage;
        document.getElementById("formularioProducto").appendChild(hiddenInput);
      }

      // Si todas las validaciones pasan
      return true;
    }