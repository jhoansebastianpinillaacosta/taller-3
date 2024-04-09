<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\ProductoFactory;

class ProductosControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testListar()
    {
        // Insertar algunos datos de prueba en la tabla de productos
        DB::table('productos')->insert([
            ['nombre' => 'Producto 1', 'descripcion' => 'Descripción del producto 1', 'precio' => '10', 'marca' => 'Marca 1', 'fechadevencimiento' => now()],
            ['nombre' => 'Producto 2', 'descripcion' => 'Descripción del producto 2', 'precio' => '20', 'marca' => 'Marca 2', 'fechadevencimiento' => now()],
            // Insertar más datos según sea necesario
        ]);

        // Simular una solicitud HTTP GET a la ruta 'crud.index'
        $response = $this->get(route('crud.index'));

        // Verificar que la respuesta tenga un estado HTTP 200 (OK)
        $response->assertStatus(200);

        // Verificar que los datos de los productos están presentes en la vista
        $response->assertSee('Producto 1');
        $response->assertSee('Producto 2');
        // Agregar más verificaciones según sea necesario
    }

    public function testCreate()
{
    // Simular una imagen cargada
    $imagen = UploadedFile::fake()->image('Marca.jpg');

    // Simular datos de producto utilizando Faker
    $datosProducto = [
        'txtnombre' => $this->faker->word,
        'txtdescripcion' => $this->faker->sentence,
        'intprecio' => $this->faker->randomNumber(2),
        'txtmarca' => $imagen, // Utiliza el nombre de campo que esperas en tu controlador para la imagen
        'fechavencimiento' => $this->faker->date,
    ];

    // Realizar una solicitud HTTP POST a la ruta de creación de productos
    $response = $this->post(route('crud.create'), $datosProducto);

    // Verificar que la respuesta tenga un estado HTTP 302 (redirección después de la creación)
    $response->assertStatus(302);

    // Verificar que el producto se haya registrado correctamente en la base de datos
    $this->assertDatabaseHas('productos', [
        'nombre' => $datosProducto['txtnombre'],
        'descripcion' => $datosProducto['txtdescripcion'],
        'precio' => $datosProducto['intprecio'],
        'fechadevencimiento' => $datosProducto['fechavencimiento'],
    ]);
}
public function testActualizarProducto()
{
    // Crear un producto de ejemplo en la base de datos
    $producto = DB::table('productos')->insertGetId([
        'nombre' => 'Producto de prueba',
        'descripcion' => 'Descripción del producto de prueba',
        'precio' => '100',
        'marca' => 'Marca de prueba',
        'fechadevencimiento' => now(),
    ]);

    // Simular datos actualizados del producto utilizando Faker
    $datosActualizados = [
        'txtnombre' => $this->faker->word,
        'txtdescripcion' => $this->faker->sentence,
        'intprecio' => $this->faker->randomNumber(2),
    ];

    // Verificar si se proporciona una nueva imagen en la solicitud
    if ($this->faker->boolean) {
        // Simular una imagen cargada
        $imagen = UploadedFile::fake()->image('nuevo_producto.jpg');
        $datosActualizados['marca'] = $imagen;
    }

    // Realizar una solicitud HTTP POST a la ruta de actualización de productos
    $response = $this->post(route('crud.update', $producto), $datosActualizados);

    // Verificar que la respuesta tenga un estado HTTP 302 (redirección después de la actualización)
    $response->assertStatus(302);

}

    public function testDelete()
    {
        // Obtener un producto existente de la base de datos
        $producto = DB::table('productos')->insertGetId([
            'nombre' => 'Producto de prueba',
            'descripcion' => 'Descripción del producto de prueba',
            'precio' => '100',
            'marca' => 'Marca de prueba',
            'fechadevencimiento' => now(),
        ]);

        // Realizar una solicitud HTTP GET a la ruta de eliminación de productos
        $response = $this->get(route('crud.delete', ['id',$producto]));

        // Verificar que la respuesta tenga un estado HTTP 302 (redirección después de la eliminación)
        $response->assertStatus(302);

        // Verificar que el producto se haya eliminado correctamente de la base de datos
        $this->assertDatabaseMissing('productos', ['id' => $producto]);
    }
    public function testBuscarProducto()
    {
        // Crear algunos productos de ejemplo en la base de datos
        DB::table('productos')->insert([
            ['nombre' => 'Producto 1', 'descripcion' => 'Descripción del producto 1', 'precio' => '10', 'marca' => 'Marca 1', 'fechadevencimiento' => now()],
            ['nombre' => 'Producto 2', 'descripcion' => 'Descripción del producto 2', 'precio' => '20', 'marca' => 'Marca 2', 'fechadevencimiento' => now()],
            ['nombre' => 'Otro producto', 'descripcion' => 'Descripción de otro producto', 'precio' => '15', 'marca' => 'Marca 3', 'fechadevencimiento' => now()],
        ]);
    
        // Realizar una solicitud HTTP POST a la ruta de búsqueda de productos con un término de búsqueda
        $response = $this->post(route('buscar'), ['searchTerm' => 'Producto 1']);
    
        // Verificar que la respuesta tenga un estado HTTP 200 (OK)
        $response->assertStatus(200);
    
        // Verificar que los datos del producto buscado estén presentes en la vista
        $response->assertSee('Producto 1');
        $response->assertDontSee('Producto 2');
        $response->assertDontSee('Otro producto');
    }
    public function testCrearProductoFallido()
    {
        // Simular datos de producto incompletos o inválidos
        $datosProducto = [
            // Falta el nombre del producto (clave 'nombre' en lugar de 'txtnombre')
            'txtnombre' => '', // Ejemplo de nombre de producto inválido (vacio)
            'txtdescripcion' => $this->faker->sentence,
            'intprecio' => $this->faker->randomNumber(2),
            'fechavencimiento' => $this->faker->date,
        ];
    
        // Realizar una solicitud HTTP POST a la ruta de creación de productos
        $response = $this->post(route('crud.create'), $datosProducto);
    
        // Verificar que la respuesta tenga un estado HTTP 302 (redirección después del intento de creación fallido)
        $response->assertStatus(302);
    
        // Verificar que el producto no se haya registrado en la base de datos
        $this->assertDatabaseMissing('productos', [
            'nombre' => $datosProducto['txtnombre'],
            'descripcion' => $datosProducto['txtdescripcion'],
            'precio' => $datosProducto['intprecio'],
            'fechadevencimiento' => $datosProducto['fechavencimiento'],
        ]);
    }
    public function testStress()
{

    // Simular la creación de 100 productos en la base de datos
    for ($i = 0; $i < 100; $i++) {

       $imagen = UploadedFile::fake()->image('Marca' . $i . '.jpg');

    // Simular datos de producto utilizando Faker
    $datosProducto = [
        'txtnombre' => $this->faker->word,
        'txtdescripcion' => $this->faker->sentence,
        'intprecio' => $this->faker->randomNumber(2),
        'txtmarca' => $imagen, // Utiliza el nombre de campo que esperas en tu controlador para la imagen
        'fechavencimiento' => $this->faker->date,
    ];

    // Realizar una solicitud HTTP POST a la ruta de creación de productos
    $response = $this->post(route('crud.create'), $datosProducto);

    // Verificar que la respuesta tenga un estado HTTP 302 (redirección después de la creación)
    $response->assertStatus(302);

    // Verificar que el producto se haya registrado correctamente en la base de datos
    $this->assertDatabaseHas('productos', [
        'nombre' => $datosProducto['txtnombre'],
        'descripcion' => $datosProducto['txtdescripcion'],
        'precio' => $datosProducto['intprecio'],
        'fechadevencimiento' => $datosProducto['fechavencimiento'],
    ]);
    }
}
public function testRutaPrincipal()
{
    // Realizar una solicitud HTTP GET a la ruta principal de la aplicación
    $response = $this->get('/');

    // Verificar que la respuesta tenga un estado HTTP 200 (OK)
    $response->assertStatus(200);

    // Verificar que el contenido de la respuesta incluya el texto "Bienvenido"
    $response->assertSee('Bienvenido');
}
}