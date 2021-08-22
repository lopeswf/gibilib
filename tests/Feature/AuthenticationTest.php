<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Registry a new user.
     * 
     * @return void
     */
    public function test_user_registration_with_authentication()
    {
        /** @var TestResponse */
        $authRequest = $this->getJson('/sanctum/csrf-cookie');

        /** @var array */
        $cookies = $authRequest->headers->getCookies();

        /** @var string */
        $auth = collect($cookies)->first(fn($cookie) => $cookie->getName() ?? '' === config('sanctum.token_name',''))?->getValue();

        if(empty($auth)){
            $this->fail('Authentication failed!');
        }

        /** @var array */
        $user = User::factory()->make()->toArray();
        $user['password_confirmation'] = $user['password'] = '12345678';

        /** @var TestResponse */
        $response = $this->postJson('/register',$user,['X-XSRF-TOKEN' => $auth]);

        $response->assertCreated();
    }
}
