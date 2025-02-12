<?php

namespace Tests\Unit\Service\Auth;

use App\Exception\Usuario\UsuarioCredenciaisInvalidoException;
use App\Repository\UsuarioRepository;
use App\Service\Auth\LoginService;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class LoginServiceTest extends TestCase
{
    private MockInterface $usuarioRepositoryMock;
    private LoginService $loginService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->usuarioRepositoryMock = Mockery::mock(UsuarioRepository::class);
        $this->loginService = new LoginService($this->usuarioRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveRealizarLoginComSucesso()
    {
        $dados = [
            'email' => 'teste@email.com',
            'senha' => 'senha123'
        ];

        $usuarioMock = Mockery::mock(Usuario::class);
        $usuarioMock->shouldReceive('getAttribute')->with('senha')->andReturn('hashed_password');
        $usuarioMock->shouldReceive('getAttribute')->with('email')->andReturn($dados['email']);

        $this->usuarioRepositoryMock
            ->shouldReceive('buscarPeloEmail')
            ->with($dados['email'])
            ->once()
            ->andReturn($usuarioMock);

        Hash::shouldReceive('check')
            ->with($dados['senha'], 'hashed_password')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('login')
            ->with($usuarioMock)
            ->once()
            ->andReturn('jwt_token');

        Auth::shouldReceive('factory->getTTL')
            ->once()
            ->andReturn(60);

        $resultado = $this->loginService->handle($dados);

        $this->assertEquals([
            'token_acesso' => 'jwt_token',
            'tipo_token' => 'bearer',
            'expira_em' => 3600
        ], $resultado);
    }

    public function testDeveLancarExcecaoQuandoEmailNaoExiste()
    {
        $dados = [
            'email' => 'inexistente@email.com',
            'senha' => 'senha123'
        ];

        $this->usuarioRepositoryMock
            ->shouldReceive('buscarPeloEmail')
            ->with($dados['email'])
            ->once()
            ->andReturnNull();

        $this->expectException(UsuarioCredenciaisInvalidoException::class);

        $this->loginService->handle($dados);
    }

    public function testDeveLancarExcecaoQuandoSenhaIncorreta()
    {
        $dados = [
            'email' => 'teste@email.com',
            'senha' => 'senha_incorreta'
        ];

        $usuarioMock = Mockery::mock(Usuario::class);
        $usuarioMock->shouldReceive('getAttribute')->with('senha')->andReturn('hashed_password');
        $usuarioMock->shouldReceive('getAttribute')->with('email')->andReturn($dados['email']);

        $this->usuarioRepositoryMock
            ->shouldReceive('buscarPeloEmail')
            ->with($dados['email'])
            ->once()
            ->andReturn($usuarioMock);

        Hash::shouldReceive('check')
            ->with($dados['senha'], 'hashed_password')
            ->once()
            ->andReturn(false);

        $this->expectException(UsuarioCredenciaisInvalidoException::class);

        $this->loginService->handle($dados);
    }
}