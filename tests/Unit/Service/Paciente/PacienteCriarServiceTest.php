<?php

namespace Tests\Unit\Service\Paciente;

use App\Exception\Paciente\PacienteCpfInvalidoException;
use App\Models\Paciente;
use App\Repository\PacienteRepository;
use App\Service\Paciente\PacienteCriarService;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class PacienteCriarServiceTest extends TestCase
{
    private MockInterface $pacienteRepositoryMock;
    private PacienteCriarService $pacienteCriarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pacienteRepositoryMock = Mockery::mock(PacienteRepository::class);
        $this->pacienteCriarService = new PacienteCriarService($this->pacienteRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveCriarPacienteComSucesso(): void
    {
        $dados = [
            'nome' => 'João Silva',
            'cpf' => '12345678900',
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $pacienteMock = Mockery::mock(Paciente::class);

        $this->pacienteRepositoryMock
            ->shouldReceive('existeCpf')
            ->with($dados['cpf'])
            ->once()
            ->andReturnFalse();

        $this->pacienteRepositoryMock
            ->shouldReceive('criar')
            ->with($dados)
            ->once()
            ->andReturn($pacienteMock);

        $resultado = $this->pacienteCriarService->executar($dados);

        $this->assertInstanceOf(Paciente::class, $resultado);
    }

    public function testDeveLancarExcecaoQuandoCpfJaExiste(): void
    {
        $dados = [
            'nome' => 'João Silva',
            'cpf' => '12345678900',
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $this->pacienteRepositoryMock
            ->shouldReceive('existeCpf')
            ->with($dados['cpf'])
            ->once()
            ->andReturnTrue();

        $this->expectException(PacienteCpfInvalidoException::class);

        $this->pacienteCriarService->executar($dados);
    }
}