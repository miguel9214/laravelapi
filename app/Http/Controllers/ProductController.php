<?php

namespace App\Http\Controllers;
// Para poder utlizar la tablas debo llamar el modelo correspondiente
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();
        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $respuesta = [];
        $validar = $this->validar($request->all());
        if (!is_array($validar)) {
            Product::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        ;

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $respuesta=[];

        $validar = $this->validar($request->all());
        if (!is_array($validar)) {

            $product = Product::find($id);

            if ($product) {
                $product->fill($request->all())->save();
                array_push($respuesta, ['status' => 'success']);
            } else {
                array_push($respuesta, ['status' => 'error']);
                array_push($respuesta, ['errors' => 'No existe el ID']);
            }
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $respuesta=[];
        $product =Product::find($id);

        if($product){
            $product->delete();
            array_push($respuesta, ['status' => 'success']);
        }else{
            array_push($respuesta, ['errors' => 'No existe']);
        }
        return response()->json($respuesta);
    }

    public function validar($parametros)
    {
        $respuesta = [];

        $messages = [
            'max' => 'Excede el tamaño',
            'required' => 'Campo requerido',
            'price.numeric' => 'Excede el precio maximo'
        ];

        $validacion = Validator::make(
            $parametros,
            [
                'name' => 'required|max:80',
                'description' => 'required|max:150',
                'price' => 'required|numeric|max:10'

            ],
            $messages
        );

        if ($validacion->fails()) {
            array_push($respuesta, ['status' => 'error']);
            array_push($respuesta, ['errors' => $validacion->errors()]);
            return $respuesta;
        } else {
            return true;
        }
    }
}
