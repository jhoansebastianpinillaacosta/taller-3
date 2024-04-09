<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>supermercado</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

    <script src="https://kit.fontawesome.com/c494e3bce7.js" crossorigin="anonymous"></script>
</head>
<body>

        <script>
        document.getElementById('searchButton').addEventListener('click', function() {
            var searchTerm = document.getElementById('searchInput').value.toLowerCase();
            var searchResults = document.getElementById('searchResults');
            var found = false;

            searchResults.innerHTML = '';

            var elements = document.querySelectorAll('#container-to-search [data-searchable]');

            elements.forEach(function(element) {
                var text = element.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    searchResults.appendChild(element.cloneNode(true));
                    found = true;
                }
            });

            if (!found) {
                searchResults.innerHTML = '<p>No se encontraron resultados.</p>';
            }
        });
        </script>
        
        <nav class="navbar">
            <div class="container-fluid">
                <h1 style="color: white;">Bienvenido</h1>
                <form action="{{ route('buscar') }}" method="GET">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="buscar un producto" name="searchTerm">
                        <button class="btn custom-btn" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
        </nav>


    @if(session("correcto"))
        <div class="alert alert-success">{{session("correcto")}}</div>
    @endif

    @if(session("incorrecto"))
        <div class="alert alert-danger">{{session("incorrecto")}}</div>
    @endif

    <script>
        var res = function() {
            var not = confirm("¿Estás seguro de eliminar la película?");
            return not;
        };
    </script>

    <div class="modal fade" id="ModalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar una película</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('crud.create') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="txtnombre">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción del producto</label>
                            <input type="text" class="form-control" id="descripcion" name="txtdescripcion">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio del producto</label>
                            <input type="number" class="form-control" id="precio" name="intprecio">
                        </div>
                        <div class="mb-3">
                            <label for="marca" class="form-label">imagen del producto</label>
                            <input type="file" class="form-control" id="marca" name="txtmarca">
                        </div>
                        <div class="mb-3">
                            <label for="fechavencimiento" class="form-label">fecha de vencimiento</label>
                            <input type="date" class="form-control" id="fechavencimiento" name="fechavencimiento">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5">
    <button>
        <span class="button_top" data-bs-toggle="modal" data-bs-target="#ModalCrear"> Añadir producto
        </span>
    </button>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach ($datos as $item)
            <div class="col mb-4">
                <div class="card">
                    <div class="card-img">
                        @if ($item->marca)
                            <img src="{{ asset('producto/' . $item->marca) }}" alt="producto" style="max-width: 100px;">
                        @else
                            No hay imagen disponible
                        @endif
                    </div>
                    <div class="card-info">
                        <p class="text-title">{{$item->nombre}}</p>
                        <p class="text-body">{{$item->descripcion}}</p>
                        <p class="text-body">{{$item->fechadevencimiento}}</p>
                    </div>
                    <div class="card-footer">
                        <div class="card-button">
                            <a href="{{route('crud.delete', $item->id)}}" onclick="return res()" class="btn btn-sm"><i class="fa-solid fa-trash"></i></a>
                        </div>
                        <span class="text-title">${{$item->precio}}</span>
                        <div class="card-button">
                            <a href="" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$item->id}}"><i class="fa-solid fa-bars"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ModalEditar{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar datos del producto</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{route('crud.update')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="txtcodigo" class="form-label">Código del producto</label>
                                    <input type="text" class="form-control" id="txtcodigo" name="txtcodigo" value="{{$item->id}}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="txtnombre" class="form-label">Nombre del producto</label>
                                    <input type="text" class="form-control" id="txtnombre" name="txtnombre" value="{{$item->nombre}}">
                                </div>
                                <div class="mb-3">
                                    <label for="txtdescripcion" class="form-label">Descripción del producto</label>
                                    <input type="text" class="form-control" id="txtdescripcion" name="txtdescripcion" value="{{$item->descripcion}}">
                                </div>
                                <div class="mb-3">
                                    <label for="intprecio" class="form-label">Precio del producto</label>
                                    <input type="number" class="form-control" id="intprecio" name="intprecio" value="{{$item->precio}}">
                                </div>
                                <div class="mb-3">
                                    <label for="txtmarca" class="form-label">Imagen del producto</label>
                                    <img src="{{ asset('producto/' . $item->marca) }}" alt="marca" style="max-width: 100px;">
                                    <input type="file" class="form-control mt-2" id="txtmarca" name="txtmarca">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Modificar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="{{ $datos->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Anterior</span>
                </a>
            </li>

            @for ($i = 1; $i <= $datos->lastPage(); $i++)
                <li class="page-item {{ $i == $datos->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $datos->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item">
                <a class="page-link" href="{{ $datos->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

