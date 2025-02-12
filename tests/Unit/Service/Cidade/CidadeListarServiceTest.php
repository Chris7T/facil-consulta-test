<?php

namespace Tests\Unit\Service\Cidade;

use App\Client\IBGE\IBGEClient;
use App\Repository\CidadeRepository;
use App\Service\Cidade\CidadeListarService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class CidadeListarServiceTest extends TestCase
{
    private MockInterface $ibgeClientMock;
    private MockInterface $cidadeRepositoryMock;
    private CidadeListarService $cidadeListarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->ibgeClientMock = Mockery::mock(IBGEClient::class);
        $this->cidadeRepositoryMock = Mockery::mock(CidadeRepository::class);
        $this->cidadeListarService = new CidadeListarService(
            $this->ibgeClientMock,
            $this->cidadeRepositoryMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveListarCidadesSemFiltro()
    {
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $this->cidadeRepositoryMock
            ->shouldReceive('listar')
            ->with(null)
            ->once()
            ->andReturn($paginatorMock);

        $resultado = $this->cidadeListarService->executar();

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultado);
    }

    public function testDeveListarCidadesComFiltroDeNome()
    {
        $nomeFiltro = 'São Paulo';
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $this->cidadeRepositoryMock
            ->shouldReceive('listar')
            ->with($nomeFiltro)
            ->once()
            ->andReturn($paginatorMock);

        $resultado = $this->cidadeListarService->executar($nomeFiltro);

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultado);
    }
}