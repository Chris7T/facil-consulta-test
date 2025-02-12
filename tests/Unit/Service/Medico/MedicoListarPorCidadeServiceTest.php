<?php

namespace Tests\Unit\Service\Medico;

use App\Exception\Cidade\CidadeNaoEncontradaException;
use App\Models\Cidade;
use App\Repository\CidadeRepository;
use App\Repository\MedicoRepository;
use App\Service\Medico\MedicoListarPorCidadeService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class MedicoListarPorCidadeServiceTest extends TestCase
{
    private MockInterface $medicoRepositoryMock;
    private MockInterface $cidadeRepositoryMock;
    private MedicoListarPorCidadeService $medicoListarPorCidadeService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->medicoRepositoryMock = Mockery::mock(MedicoRepository::class);
        $this->cidadeRepositoryMock = Mockery::mock(CidadeRepository::class);
        $this->medicoListarPorCidadeService = new MedicoListarPorCidadeService(
            $this->medicoRepositoryMock,
            $this->cidadeRepositoryMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveListarMedicosPorCidadeSemFiltroDeNome(): void
    {
        $cidadeId = 1;
        $cidadeMock = Mockery::mock(Cidade::class);
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $this->cidadeRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($cidadeId)
            ->once()
            ->andReturn($cidadeMock);

        $this->medicoRepositoryMock
            ->shouldReceive('listarPorCidade')
            ->with($cidadeId, null)
            ->once()
            ->andReturn($paginatorMock);

        $resultado = $this->medicoListarPorCidadeService->executar($cidadeId);

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultado);
    }

    public function testDeveListarMedicosPorCidadeComFiltroDeNome(): void
    {
        $cidadeId = 1;
        $nome = 'Dr. João';
        $cidadeMock = Mockery::mock(Cidade::class);
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $this->cidadeRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($cidadeId)
            ->once()
            ->andReturn($cidadeMock);

        $this->medicoRepositoryMock
            ->shouldReceive('listarPorCidade')
            ->with($cidadeId, $nome)
            ->once()
            ->andReturn($paginatorMock);

        $resultado = $this->medicoListarPorCidadeService->executar($cidadeId, $nome);

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultado);
    }

    public function testDeveLancarExcecaoQuandoCidadeNaoEncontrada(): void
    {
        $cidadeId = 999;

        $this->cidadeRepositoryMock
            ->shouldReceive('buscarPeloId')
            ->with($cidadeId)
            ->once()
            ->andReturnNull();

        $this->expectException(CidadeNaoEncontradaException::class);

        $this->medicoListarPorCidadeService->executar($cidadeId);
    }
}