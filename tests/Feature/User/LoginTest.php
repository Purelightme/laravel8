<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->postJson('/api/user/users',[
            'phone' => '',
            'code' => '',
            'password' => '',
            'password_confirmation' => ''
        ],[
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $response->assertJson(['errcode' => 2001]);
    }

    public function testLogin()
    {
        $response = $this->postJson('/api/user/login_by_password',[
            'phone' => '16601126817',
            'password' => '123456'
        ],[
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $response->assertJsonFragment(['errcode' => 0]);
        return $response->decodeResponseJson()['data']['token'];
    }

    /**
     * @depends testLogin
     *
     * @param string $token
     */
    public function testGetBaseInfo(string $token)
    {
        $response = $this->get('/api/user/users',[
            'Authorization' => 'Bearer '.$token
        ]);
        $response->assertJsonFragment(['errcode' => 0]);
    }
}
