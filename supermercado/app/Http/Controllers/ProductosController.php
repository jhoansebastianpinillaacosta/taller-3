<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Productos; 

class ProductosController extends Controller
{
    public function index(){
        $datos = DB::table('productos')->paginate(5);
        return view("welcome")->with("datos", $datos);
    }

    public function create(Request $request){
        try {
                // Verificar si hay una imagen cargada
                if($request->hasFile('txtmarca') && $request->file('txtmarca')->isValid()) {
                    // Obtener la instancia del archivo de imagen
                    $imagen = $request->file('txtmarca');
                    // Guardar la imagen con un nombre único en la carpeta 'public/carteles'
                    $nombre_imagen = time().'_'.$imagen->getClientOriginalName();
                    $imagen->move(public_path('producto'), $nombre_imagen);
                } else {
                    // Si no se proporciona una imagen o la imagen no es válida, asignar un valor predeterminado
                    $nombre_imagen = 'default.jpg'; // Cambia 'default.jpg' al nombre de tu imagen predeterminada
                }
    
            // Insertar los datos en la base de datos incluyendo el nombre de la imagen
            $sql = DB::insert('insert into productos (nombre, `fechadevencimiento`, descripcion, precio, marca) values (?, ?, ?, ?, ?)', [
                $request->txtnombre,
                $request->fechavencimiento,
                $request->txtdescripcion,
                $request->intprecio,
                $nombre_imagen, // Insertamos el nombre de la imagen
            ]);
        }
            catch (\Throwable $th) {
                $sql = 0;
            }
            if ($sql == true) {
                return back()->with("correcto","producto Registrado");
            } else {
                return back()->with("incorrecto","Error al registrar el producto");
            }           
    }
    

    public function update(Request $request)
    {
        try{
            // Verificar si hay una imagen cargada
            if($request->hasFile('txtmarca') && $request->file('txtmarca')->isValid()) {
                // Obtener la instancia del archivo de imagen
                $imagen = $request->file('txtmarca');
                // Guardar la imagen con un nombre único en la carpeta 'public/carteles'
                $nombre_imagen = time().'_'.$imagen->getClientOriginalName();
                $imagen->move(public_path('producto'), $nombre_imagen);
            } else {
                // Si no se proporciona una imagen, mantener la imagen existente
                $nombre_imagen = $request->txtmarca; // Mantenemos el nombre de la imagen existente
            }
    
            $sql = DB::update('update productos set nombre=?, descripcion=?, precio=?,marca=? where id=?',
                [
                    $request->txtnombre,
                    $request->txtdescripcion,
                    $request->intprecio,
                    $nombre_imagen,
                    $request->txtcodigo,
                ]);
            } catch (\Throwable $th) {
                    $sql = 0;
                }
                if ($sql == true) {
                    return back()->with("correcto","se ha modificado el producto");
                } else {
                    return back()->with("incorrecto","no se ha modificado el producto");
                }
    }
    
    public function delete($id)
    {
        try {
            $sql=DB::delete("delete from productos where id=$id");
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == true) {
            return back()->with("correcto","el producto se elimino exitosamente");
        } else {
            return back()->with("incorrecto","Error al eliminar el producto");
        }

    }

    public function buscar(Request $request)
    {
        // Obtén el término de búsqueda del formulario
        $searchTerm = $request->input('searchTerm');
    
        // Realiza la consulta a la base de datos utilizando Eloquent y aplica paginación
        $resultados = Productos::where('nombre', 'like', '%' . $searchTerm . '%')
                               ->paginate(5);
    
        // Verifica si se encontraron resultados
        if ($resultados->isEmpty()) {
            return back()->with("correcto", "No se encontraron resultados para '$searchTerm'")->withInput();
        } else {
            return view('welcome')->with("datos", $resultados);
        }
    }
}