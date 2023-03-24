<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\User;
use App\Notifications\UserRegister;
use App\Repositories\UserRepositories;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    private $userRepositories;

    public function __construct(UserRepositories $userRepositories)
    {
        $this->userRepositories = $userRepositories;
    }

    /**
     * Registro de usuario
     * 
     * @autor(a) Airaly Cañizales
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($request->password);
            $user = new User($validatedData);
            $user = $this->userRepositories->save($user);
            $user->assignRole(User::ROLES['lead']);

            $user->notify(new UserRegister);
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $response = $this->buildCredentials($user, $tokenResult);
            return $this->okResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Inicio de sesión y creación de token
     * 
     * @autor(a) Airaly Cañizales
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            if (!Auth::attempt($credentials))
                return $this->errorResponse('Unauthorized', 401);

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $response = $this->buildCredentials($user, $tokenResult);
            return $this->okResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Cierre de sesión (anular el token)
     *
     *  @autor(a) Airaly Cañizales
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->token()->revoke();
            return $this->okResponse('Logged out successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Obtener el usuario autenticado como json
     * 
     *  @autor(a) Airaly Cañizales
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        try {
            $me = [
                'user' => auth()->user(),
                'role' => auth()->user()->getRoleNames()[0],
                'permissions' => auth()->user()->getAllPermissions()
            ];
            return $this->okResponse($me);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Crendenciales del usuario
     * 
     * @autor(a) Airaly Cañizales
     * @param User $user
     * @return Array
     */
    public function buildCredentials($user, $tokenResult): Array
    {
        $permissions = $user->getAllPermissions()->pluck('name');

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => [
                'permissions' => $permissions ?? []
            ]
        ];
    }
}
