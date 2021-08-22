<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    const EMAIL_FAIL = 'fail@email';
    const PASSWORD_FAIL = 'fail';
    const DEFAULT_PASSWORD = 'password';

    /**
     * Registry a new user.
     * 
     * @return void
     */
    public function test_user_registration()
    {
        /** @var array */
        $user = User::factory()->make()->toArray();
        $user['password_confirmation'] = $user['password'] = Self::DEFAULT_PASSWORD;

        /** @var TestResponse */
        $response = $this->postJson('/register',$user);

        $response->assertCreated();
    }


    /**
     * User login.
     * 
     * @return void
     */
    public function test_user_login_succeeds()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var TestResponse */
        $response = $this->postJson('/login',[
            'email' => $user->email,
            'password' => Self::DEFAULT_PASSWORD
        ]);

        $response->assertOk();
    }

    /**
     * User login.
     * 
     * @return void
     */
    public function test_user_login_fails()
    {
        /** @var TestResponse */
        $response = $this->postJson('/login',[
            'email' => Self::EMAIL_FAIL,
            'password' => Self::PASSWORD_FAIL
        ]);

        $response->assertJsonValidationErrors('email');
    }
}
