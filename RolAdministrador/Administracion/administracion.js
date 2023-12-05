/*Aerolineas*/ 
// Variable para almacenar la lista de aerolíneas
var aerolineas = [];

// Función para borrar o limpiar las cajas de texto y la imagen
function borrarDatos() {
    codigo.value = "";
    nombre.value = "";
    imagen.src = "";
}

// Función para mostrar un mensaje de éxito al guardar los datos
function mostrarMensaje() {
    alert("Se han guardado con éxito los datos solicitados");
}

// Función para redireccionar a la lista de las agencias
function mostrarLista() {
    window.location.href = "lista.html"; // Aquí se debe poner la dirección de la página de la lista
}

// Función para cargar una imagen desde el directorio local
function cargarImagen() {
    var input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";
    input.onchange = function (e) {
        var file = e.target.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            imagen.src = e.target.result;
        };
        reader.readAsDataURL(file);
    };
    input.click();
}

// Función para guardar los datos de la aerolínea en la lista
function guardarDatos() {
    var cod = codigo.value;
    var nom = nombre.value;
    var img = imagen.src;
    if (cod && nom && img) {
        var aerolinea = {
            codigo: cod,
            nombre: nom,
            imagen: img
        };
        aerolineas.push(aerolinea);
        mostrarMensaje();
        borrarDatos();
        mostrarGrid();
    } else {
        alert("Por favor, complete todos los campos");
    }
}

// Función para mostrar la lista de aerolíneas en el grid
function mostrarGrid() {
    grid.innerHTML = "";
    for (var i = 0; i < aerolineas.length; i++) {
        var item = document.createElement("div");
        item.className = "grid-item";
        var img = document.createElement("img");
        img.src = aerolineas[i].imagen;
        var cod = document.createElement("p");
        cod.textContent = aerolineas[i].codigo;
        var nom = document.createElement("p");
        nom.textContent = aerolineas[i].nombre;
        item.appendChild(img);
        item.appendChild(cod);
        item.appendChild(nom);
        grid.appendChild(item);
    }
}

// Eventos para los botones
borrar.addEventListener("click", borrarDatos);
aceptar.addEventListener("click", guardarDatos);
cerrar.addEventListener("click", mostrarLista);
cargar.addEventListener("click", cargarImagen);