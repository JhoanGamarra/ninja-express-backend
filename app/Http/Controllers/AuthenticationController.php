<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Client;
use App\Models\Courier;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Validator;

class AuthenticationController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'resetPassword']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        $user = auth()->user();

        if ($user->device_token != $request->device_token) {
            $user->device_token = $request->device_token;
            $user->save();
        }


        return response()->json(["token"  => $token], 200);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $type = $request->type;

        if ($type == "client" || $type == "business" || $type == "courier") {

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:100|unique:users',
                'device_token' => 'required|unique:users',
                'password' => 'required|string|min:6',
            ]);


            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 432);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)],
            ));


            if ($type == "client") {

                Client::create(['name' => $request->name, 'email' => $user->email, 'user_id'  => $user->id]);
            }

            if ($type == "business") {
                Business::create(['name' => $request->name, 'user_id'  => $user->id,  'email' => $user->email]);
            }

            if ($type == "courier") {
                Courier::create(['name' => $request->name, 'user_id'  => $user->id]);
            }

            return response()->json($user, 201);
        }

        return response()->json("Invalid Type", 502);
    }


    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $user->password =  bcrypt($request->password);
        $user->save();
        return response()->json(['message' => 'Password Successfully changed'], 211);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }



    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


    public function resetPassword(Request $request)
    {


        try {
            $user = User::where('email', '=', $request->email)->firstOrFail();
            $password = $this->randomPassword();
            $user->password = bcrypt($password);
            $user->save();
            $data = array("name" => $user->name, "body" => "Tu nueva contraseña temporal es : ", "password" => $password, "warning" => "Recuerda acualizar tu contraseña una vez inicies sesion.");
            Mail::send('reset-password', $data, function ($message) use ($user) {
                $message->to($user->email, $user->name)->subject("Reestablecimiento de contraseña Ninja Express");
                $message->from("support@domiciliariosninjaexpress.com", "NinjaExpress Support");
            });

            return response()->json(['message' => 'Password Changed'], 200);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Failed Mail Delivery'], 404);
        }
    }




    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request)
    {


        $type = $request->type;

        $user = auth()->user();

        if ($type == "client") {

            try {
                $client = Client::where('user_id', '=', $user->id)->firstOrFail();
                return response()->json($client, 200);
            } catch (\Throwable $th) {
                return response()->json("This user don't have a client", 436);
            }
        }

        if ($type == "business") {

            try {
                $business = Business::where('user_id', '=', $user->id)->firstOrFail();
                return response()->json($business, 200);
            } catch (\Throwable $th) {
                return response()->json("This user don't have a business", 436);
            }
        }

        if ($type == "courier") {
            try {
                $courier = Courier::where('user_id', '=', $user->id)->firstOrFail();
                return response()->json($courier, 200);
            } catch (\Throwable $th) {
                return response()->json("This user don't have a courier", 436);
            }
        }

        return response()->json("Invalid Type", 508);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
