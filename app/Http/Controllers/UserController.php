<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Users
 *
 * API для работы с пользователями
 */
class UserController extends Controller
{
    /**
     * Get token for user
     *
     * @param UserRequest $request
     * @return JsonResponse
     * @bodyParam email string required Email
     * @bodyParam password string required Пароль
     */
    public function token(UserRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => implode(",",$validator->messages()->all())
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => __('http.incorrect_credentials')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Hash::check($request->password, $user->password)) {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.incorrect_credentials')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'token'  => $user->createToken($user->id)->plainTextToken
        ], Response::HTTP_CREATED);
    }

    /**
     * Login user
     *
     * @param UserRequest $request
     * @return JsonResponse
     * @bodyParam email string Email
     * @bodyParam password string Пароль
     */
    public function login(UserRequest $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email','password'))) {
            $user = Auth::user();
            return response()->json([
                'user'  => $user,
                'token' => $user->createToken($user->id)->plainTextToken
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => __('http.incorrect_credentials')
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Logout user
     * @authenticated
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function logout(UserRequest $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->where('tokenable_id', $user->id)->delete();

            return response()->json([
                'status'  => 'ok',
                'message' => __('http.removed')
            ]);
        } else {

            return response()->json([
                'status'  => 'error',
                'message' => __('http.unauthorized')
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
