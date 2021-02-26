<?php

namespace Tests\Unit;

use App\Exceptions\ReportableException;
use App\Services\AuthService;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    public function testBasicLogin()
    {
        $userToken = $this->authService->login('loja1@lojas.com', '123456');
        $this->assertNotNull($userToken);
        $this->assertIsObject($userToken);
        $this->assertNotNull($userToken->token);
        $this->assertNotNull($userToken->expire_date);
    }

    public function testFailedRegistration()
    {
        $this->expectException(ReportableException::class);
        $user = $this->authService->register(
            'User Name Test',
            'usertest@test.com',
            '12345678911',
            '123456'
        );
    }

    public function testBasicRegistration()
    {
        $user = $this->authService->register(
            'User Name Test',
            'usertest@test.com',
            '08888758003',
            '123456'
        );
        $this->assertNotNull($user);
        $this->assertNotNull($user->id);
    }
}
