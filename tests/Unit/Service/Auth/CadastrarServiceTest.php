<?php

namespace Tests\Unit\Service\Auth;

use App\Exception\Usuario\UsuarioEmailInvalidoException;
use App\Repository\UsuarioRepository;
use App\Service\Auth\CadastrarService;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class CadastrarServiceTest extends TestCase
{
    private MockInterface $usuarioRepositoryMock;
    private CadastrarService $cadastrarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->usuarioRepositoryMock = Mockery::mock(UsuarioRepository::class);
        $this->cadastrarService = new CadastrarService($this->usuarioRepositoryMock);
        
        Hash::shouldReceive('make')->andReturn('hashed_password');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveCadastrarUsuarioComSucesso()
    {
        $dados = [
            'email' => 'teste@email.com',
            'nome' => 'Usuario Teste',
            'senha' => 'senha123'
        ];

        $usuarioMock = Mockery::mock(Usuario::class);
        $usuarioMock->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $usuarioMock->shouldReceive('getAttribute')->with('email')->andReturn($dados['email']);
        $usuarioMock->shouldReceive('getAttribute')->with('nome')->andReturn($dados['nome']);

        $this->usuarioRepositoryMock
            ->shouldReceive('buscarPeloEmail')
            ->with($dados['email'])
            ->once()
            ->andReturnNull();

        $this->usuarioRepositoryMock
            ->shouldReceive('criar')
            ->with($dados['email'], $dados['nome'], 'hashed_password')
            ->once()
            ->andReturn($usuarioMock);

        Auth::shouldReceive('login')
            ->with($usuarioMock)
            ->once()
            ->andReturn('jwt_token');

        Auth::shouldReceive('factory->getTTL')
            ->once()
            ->andReturn(60);

        $resultado = $this->cadastrarService->handle($dados);

        $this->assertEquals([
            'token_acesso' => 'jwt_token',
            'tipo_token' => 'bearer',
            'expira_em' => 3600
        ], $resultado);
    }

    public function testDeveLancarExcecaoQuandoEmailJaExiste()
    {
        $dados = [
            'email' => 'existente@email.com',
            'nome' => 'Usuario Teste',
            'senha' => 'senha123'
        ];

        $usuarioExistente = Mockery::mock(Usuario::class);
        $usuarioExistente->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $usuarioExistente->shouldReceive('getAttribute')->with('email')->andReturn($dados['email']);
        $usuarioExistente->shouldReceive('getAttribute')->with('nome')->andReturn('Outro Usuario');

        $this->usuarioRepositoryMock
            ->shouldReceive('buscarPeloEmail')
            ->with($dados['email'])
            ->once()
            ->andReturn($usuarioExistente);

        $this->expectException(UsuarioEmailInvalidoException::class);

        $this->cadastrarService->handle($dados);
    }
}