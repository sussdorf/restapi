<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            //'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public function authenticate(User $user) {

        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute field is required.',
        ];

        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ], $messages);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $user->token
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function register()
    {

        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute field is required.',
            'unique' => 'The :attribute already exists.',
        ];

        $this->validate($this->request, [
            'name'     => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ], $messages);

        $user = User::query()->create([
           'name' => $this->request->name,
           'email' => $this->request->email,
           'password' => app('hash')->make($this->request->password),
        ]);

        $user->update(['token' => $this->jwt($user)]);

        return $user;
    }

    public function update()
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute field is required.',
        ];

        $this->validate($this->request, [
            'name'     => 'required',
            'email'     => 'required|email'

        ], $messages);

        $user = User::where('email', $this->request->email)->first();

        if (!$user)
        {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }

        $user->update([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'token' => $this->jwt($user),
        ]);

        return $user;
    }

    public function createToken()
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute field is required.',
        ];

        $this->validate($this->request, [
            'email'     => 'required|email',
            'token'     => 'required'

        ], $messages);

        $user = User::query()->where('email', $this->request->email)->update([
            'token' => $this->request->token,
        ]);

        return $user;
    }
}
