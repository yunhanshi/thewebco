<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $token = $user->createToken('thewebco');

        return response()->json(new UserResource($user), Response::HTTP_OK)->header('Authorization', $token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json((new JsonResponse())->success([]), Response::HTTP_OK);
    }

    public function user()
    {
        return new UserResource(Auth::user());
    }

    public function register(Request $request)
    {
        $errors = $this->signUpValidate($request->all());
        if (empty($errors) == false) {
            return $this->json(Response::HTTP_BAD_REQUEST, null, ['errors' => $errors]);
        } else {
            $params = $request->all();
            $user = User::create([
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
            ]);

            $token = $user->createToken('thewebco');
            $role = Role::findByName('admin');
            $user->syncRoles($role);
            return response()->json(new UserResource($user), Response::HTTP_OK)->header('Authorization', $token->plainTextToken);
        }
    }

    private function signUpValidate($data)
    {
        $messages = [
            'email.required' => 'Please enter your email',
            'email.unique' => 'Email is already exists',
            'name.required' => 'Please enter your name',
            'password.required' => 'Please enter your password',
            'password.confirmed' => 'Confirmation password is invalid',
            'confirmation_password.required' => 'Please enter your password again',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users|min:5|max:50',
            'password' => 'required|confirmed|min:5|max:20',
            'password_confirmation' => 'required'
        ], $messages);
        return $validator->errors()->messages();
    }
}
