<?php

namespace Tests\Unit\Service\Paciente;

use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Models\Paciente;
use App\Repository\PacienteRepository;
use App\Service\Paciente\PacienteAtualizarService;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class PacienteAtualizarServiceTest extends TestCase
{
    private MockInterface $pacienteRepositoryMock;
    private PacienteAtualizarService $pacienteAtualizarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pacienteRepositoryMock = Mockery::mock(PacienteRepository::class);
        $this->pacienteAtualizarService = new PacienteAtualizarService($this->pacienteRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveAtualizarPacienteComSucesso(): void
    {
        $pacienteId = 1;
        $dados = [
            'nome' => 'João Silva',
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $pacienteMock = Mockery::mock(Paciente::class);
        $pacienteAtualizadoMock = Mockery::mock(Paciente::class);

        $this->pacienteRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($pacienteId)
            ->once()
            ->andReturn($pacienteMock);

        $this->pacienteRepositoryMock
            ->shouldReceive('atualizar')
            ->with($pacienteId, $dados)
            ->once()
            ->andReturn($pacienteAtualizadoMock);

        $resultado = $this->pacienteAtualizarService->executar($dados, $pacienteId);

        $this->assertInstanceOf(Paciente::class, $resultado);
    }

    public function testDeveLancarExcecaoQuandoPacienteNaoEncontrado(): void
    {
        $pacienteId = 999;
        $dados = [
            'nome' => 'João Silva',
            'telefone' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $this->pacienteRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($pacienteId)
            ->once()
            ->andReturnNull();

        $this->expectException(PacienteNaoEncontradoException::class);

        $this->pacienteAtualizarService->executar($dados, $pacienteId);
    }
}