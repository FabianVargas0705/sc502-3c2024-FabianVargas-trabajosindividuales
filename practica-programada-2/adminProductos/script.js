const obProductos = [
    {
        id: 1,
        nombre: "Arroz",
        precio: 1000,
        categoria: "alimentos"
    },
    {
        id: 2,
        nombre: "Papa",
        precio: 500,
        categoria: "alimentos"
    },
    {
        id: 3,
        nombre: "Leche",
        precio: 800,
        categoria: "alimentos"
    },
    {
        id: 4,
        nombre: "Huevos",
        precio: 800,
        categoria: "alimentos"
    },
    {
        id: 5,
        nombre: "Azúcar",
        precio: 800,
        categoria: "alimentos"
    },
    {
        id: 6,
        nombre: "Frijoles",
        precio: 800,
        categoria: "alimentos"
    },
    {
        id: 7,
        nombre: "Microondas",
        precio: 10000,
        categoria: "electrodomesticos"
    },
    {
        id: 8,
        nombre: "Lavadora",
        precio: 50000,
        categoria: "electrodomesticos"
    },
    {
        id: 9,
        nombre: "Televisor",
        precio: 80000,
        categoria: "electrodomesticos"
    },
    {
        id: 10,
        nombre: "Licuadora",
        precio: 50000,
        categoria: "electrodomesticos"
    },
    {
        id: 11,
        nombre: "Camisa Azul",
        precio: 800,
        categoria: "ropa"
    },
    {
        id: 12,
        nombre: "Camisa Roja",
        precio: 800,
        categoria: "ropa"
    }
];

document.addEventListener('DOMContentLoaded', function () {
    loadProductos();
    document.querySelector('#product-form').addEventListener('submit', function (e) {
        e.preventDefault();
        agregarProducto(e);
    });

    document.querySelector('#edit-product-form').addEventListener('submit', function (e) {
        e.preventDefault();
        let id = document.querySelector('#edit-product-id').value;
        let nombre = document.querySelector('#edit-product-name').value;
        let precio = document.querySelector('#edit-product-price').value;
        let categoria = document.querySelector('#edit-product-category').value;

        obProductos.forEach(function (producto) {
            if (producto.id == id) {
                producto.nombre = nombre;
                producto.precio = precio;
                producto.categoria = categoria;
            }
        });

        loadProductos();
        document.querySelector('.btn-close').click();
    });

    document.querySelector('#filtro-categoria').addEventListener('change', function (e) {
        let categoriaFiltro = e.target.value;
        loadProductos(categoriaFiltro);
    });
});

function agregarProducto(e) {
    let id = document.querySelector('#product-id').value;
    let nombre = document.querySelector('#nombre').value;
    let precio = document.querySelector('#precio').value;
    let categoria = document.querySelector('#categoria').value;



    id = Math.floor(Math.random() * 1000);
    let producto = { id, nombre, precio, categoria };
    obProductos.push(producto);

    loadProductos();
    document.querySelector('#product-form').reset();
    document.querySelector('#product-id').value = '';
    document.querySelector('.btn-close').click();
}
function loadProductos(categoriaFiltro) {
    let tbProducto = document.getElementById('tbProducto').getElementsByTagName('tbody')[0];
    tbProducto.innerHTML = '';

    if (!categoriaFiltro) {
        obProductos.forEach(function (producto) {
            let row = tbProducto.insertRow();
            row.setAttribute('data-id', producto.id);
            row.innerHTML = `
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td>${producto.precio}</td>
                <td>${producto.categoria}</td>
                <td>
                    <button class="btn btn-warning edit-producto" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${producto.id}">Editar</button>
                    <button class="btn btn-danger delete-producto" data-id="${producto.id}">Eliminar</button>
                </td>
            `;

            row.querySelector('.edit-producto').addEventListener('click', editarProducto);
            row.querySelector('.delete-producto').addEventListener('click', eliminarProducto);

        });
    } else {
        
    

    obProductos.forEach(function (producto) {
        if (producto.categoria === categoriaFiltro) {
            let row = tbProducto.insertRow();
            row.setAttribute('data-id', producto.id);
            row.innerHTML = `
            <td>${producto.id}</td>
            <td>${producto.nombre}</td>
            <td>${producto.precio}</td>
            <td>${producto.categoria}</td>
            <td>
                <button class="btn btn-warning edit-producto" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${producto.id}">Editar</button>
                <button class="btn btn-danger delete-producto" data-id="${producto.id}">Eliminar</button>
            </td>
        `;

            row.querySelector('.edit-producto').addEventListener('click', editarProducto);
            row.querySelector('.delete-producto').addEventListener('click', eliminarProducto);
        }
    });
}
};



function eliminarProducto(event) {
    let id = event.target.dataset.id;
    let row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        row.remove();
    }
    const index = obProductos.findIndex(producto => producto.id == id);
    if (index !== -1) {
        obProductos.splice(index, 1);
    }
    alert('Producto eliminado con ID: ' + id);
}

function editarProducto(event) {
    let id = event.target.dataset.id;
    let producto = obProductos.find(producto => producto.id == id);
    document.querySelector('#edit-product-id').value = producto.id;
    document.querySelector('#edit-product-name').value = producto.nombre;
    document.querySelector('#edit-product-price').value = producto.precio;
    document.querySelector('#edit-product-category').value = producto.categoria;
}