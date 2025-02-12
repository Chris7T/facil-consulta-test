<?php

namespace Tests\Unit\Service\Consulta;

use App\Exception\Medico\MedicoNaoEncontradoException;
use App\Exception\Paciente\PacienteNaoEncontradoException;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use App\Repository\ConsultaRepository;
use App\Repository\MedicoRepository;
use App\Repository\PacienteRepository;
use App\Service\Consulta\ConsultaCriarService;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class ConsultaCriarServiceTest extends TestCase
{
    private MockInterface $consultaRepositoryMock;
    private MockInterface $pacienteRepositoryMock;
    private MockInterface $medicoRepositoryMock;
    private ConsultaCriarService $consultaCriarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->consultaRepositoryMock = Mockery::mock(ConsultaRepository::class);
        $this->pacienteRepositoryMock = Mockery::mock(PacienteRepository::class);
        $this->medicoRepositoryMock = Mockery::mock(MedicoRepository::class);
        
        $this->consultaCriarService = new ConsultaCriarService(
            $this->consultaRepositoryMock,
            $this->pacienteRepositoryMock,
            $this->medicoRepositoryMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveCriarConsultaComSucesso()
    {
        $dados = [
            'paciente_id' => 1,
            'medico_id' => 1,
            'data' => '2024-02-15',
            'hora' => '14:30:00'
        ];

        $pacienteMock = Mockery::mock(Paciente::class);
        $medicoMock = Mockery::mock(Medico::class);
        $consultaMock = Mockery::mock(Consulta::class);

        $this->pacienteRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($dados['paciente_id'])
            ->once()
            ->andReturn($pacienteMock);

        $this->medicoRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($dados['medico_id'])
            ->once()
            ->andReturn($medicoMock);

        $this->consultaRepositoryMock
            ->shouldReceive('criar')
            ->with($dados)
            ->once()
            ->andReturn($consultaMock);

        $resultado = $this->consultaCriarService->executar($dados);

        $this->assertInstanceOf(Consulta::class, $resultado);
    }

    public function testDeveLancarExcecaoQuandoPacienteNaoEncontrado()
    {
        $dados = [
            'paciente_id' => 999,
            'medico_id' => 1
        ];

        $this->pacienteRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($dados['paciente_id'])
            ->once()
            ->andReturnNull();

        $this->expectException(PacienteNaoEncontradoException::class);

        $this->consultaCriarService->executar($dados);
    }

    public function testDeveLancarExcecaoQuandoMedicoNaoEncontrado()
    {
        $dados = [
            'paciente_id' => 1,
            'medico_id' => 999
        ];

        $pacienteMock = Mockery::mock(Paciente::class);

        $this->pacienteRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($dados['paciente_id'])
            ->once()
            ->andReturn($pacienteMock);

        $this->medicoRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($dados['medico_id'])
            ->once()
            ->andReturnNull();

        $this->expectException(MedicoNaoEncontradoException::class);

        $this->consultaCriarService->executar($dados);
    }
}