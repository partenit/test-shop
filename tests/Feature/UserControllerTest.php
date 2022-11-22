<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testTokenAction()
    {
        $password = Str::random(10);
        $user = User::factory()
            ->create([
                'password' => Hash::make($password)
            ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->postJson("api/v1/token", $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'token'
            ]);
    }

    public function testLoginAction()
    {
        $password = Str::random(10);
        $user = User::factory()
            ->create([
                'password' => Hash::make($password)
            ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->postJson("api/v1/login", $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user',
                'token'
            ]);
    }
}
