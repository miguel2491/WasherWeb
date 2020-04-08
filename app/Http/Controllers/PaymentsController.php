<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Stripe\Stripe;

use Stripe\Charge;

use DB;

use App;

use Auth;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;

use Image;

use App\Http\Controllers\Controller;

use App\Models\Apis\Autos;

use Illuminate\Support\Facades\Hash;



class PaymentsController extends Controller

{

    public function pay(Request $request)

    {

        Stripe::setApiKey(config('services.stripe.secret'));

 

        $token = request('stripeToken');

 

        $charge = Charge::create([

            'amount' => 1000,

            'currency' => 'mxn',

            'description' => 'Test Washer',

            'source' => $token,

        ]);

 

        return 'Payment Success!';

    }



    public function pay_prueba(Request $request)

    {

    	Stripe::setApiKey(config('services.stripe.secret'));

        $email = request('email');

        $token = request('stripeToken');

        $charge = Charge::create([

            'amount' => 1000,

            'currency' => 'mxn',

            'description' => 'Test Washer',

            'source' => $token,

        ]);

        $msg = ['status' => 'ok', 'token' => $token, 'email' => $email];

        return response()->json($msg);

    }

    public function login_app(Request $request){

        $usuario = request('usuario');

        $pass = request('pass');

        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {

            $msg = ['status' => 'ok', 'msg' => 'Bienvenido'];

        } else {

            $msg = ['status' => 'fail', 'msg' => 'Negado'];

        }



        return response()->json($msg);    

    }   



    



    public function store(Request $request) {

        $id_usuario = $_POST["id_usuario"];

        $placas = $_POST["placas"];

        $modelo = $_POST["modelo"];

        $ann = $_POST["ann"];

        $marca = $_POST["marca"];

        $color = $_POST["color"];

        $filename = time()."-".basename($_FILES['imagen']['name']);

            $upload = $request->file('imagen')->storeAs(

                'uploads/autos/', $filename

            );

            $msg = ['status' => 'ok', 'mensaje' => $upload];

            if($upload){

                $charge = Autos::create([

                    'id_usuario' => $id_usuario,

                    'placas' => $placas,

                    'modelo' => $modelo,

                    'ann' => $ann,

                    'marca' => $marca,

                    'color' => $color,

                    'imagen' => $upload,

                ]);

                $msg = ['status' => 'ok', 'mensaje' => 'El fichero es válido y se subió con éxito'];    

            }else{

                $msg = ['status' => 'fail', 'mensaje' => '¡Posible ataque de subida de ficheros!'];

            }

        return response()->json($msg);

    }



    public function imagen(Request $request){
        $filename = time()."-".basename($request->file('imagen'));
        $foto_extension = File::extension($request->file('imagen')->getClientOriginalName());
            $upload = $request->file('imagen')->storeAs(
                'uploads/autos', $filename.'.'.$foto_extension
            );
            $msg = ['status' => 'ok', 'mensaje' => $filename];
        return response()->json($msg);
    }



}

