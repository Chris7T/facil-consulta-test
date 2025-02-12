<?php

namespace Tests\Unit\Service\Cidade;

use App\Client\IBGE\IBGEClient;
use App\Repository\CidadeRepository;
use App\Service\Cidade\CidadeImportarService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class CidadeImportarServiceTest extends TestCase
{
    private MockInterface $ibgeClientMock;
    private MockInterface $cidadeRepositoryMock;
    private CidadeImportarService $cidadeImportarService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->ibgeClientMock = Mockery::mock(IBGEClient::class);
        $this->cidadeRepositoryMock = Mockery::mock(CidadeRepository::class);
        $this->cidadeImportarService = new CidadeImportarService(
            $this->ibgeClientMock,
            $this->cidadeRepositoryMock
        );

        Carbon::setTestNow('2024-02-11 12:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Mockery::close();
        parent::tearDown();
    }

    public function testDeveImportarCidadesComSucesso()
    {
        $cidadesAPI = [
            [
                'nome' => 'São Paulo',
                'microrregiao' => [
                    'mesorregiao' => [
                        'UF' => ['sigla' => 'SP']
                    ]
                ]
            ],
            [
                'nome' => 'Rio de Janeiro',
                'microrregiao' => [
                    'mesorregiao' => [
                        'UF' => ['sigla' => 'RJ']
                    ]
                ]
            ],
            [
                'nome' => 'Belo Horizonte',
                'microrregiao' => [
                    'mesorregiao' => [
                        'UF' => ['sigla' => 'MG']
                    ]
                ]
            ]
        ];

        $cidadesFormatadas = [
            [
                'nome' => 'São Paulo',
                'estado' => 'SP',
                'created_at' => '2024-02-11 12:00:00',
                'updated_at' => '2024-02-11 12:00:00'
            ],
            [
                'nome' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'created_at' => '2024-02-11 12:00:00',
                'updated_at' => '2024-02-11 12:00:00'
            ],
            [
                'nome' => 'Belo Horizonte',
                'estado' => 'MG',
                'created_at' => '2024-02-11 12:00:00',
                'updated_at' => '2024-02-11 12:00:00'
            ]
        ];

        $this->ibgeClientMock
            ->shouldReceive('listarCidades')
            ->once()
            ->andReturn($cidadesAPI);

        $this->cidadeRepositoryMock
            ->shouldReceive('inserirLote')
            ->once()
            ->with($cidadesFormatadas)
            ->andReturn(3);

        $resultado = $this->cidadeImportarService->executar();

        $this->assertEquals([
            'total_importado' => 3
        ], $resultado);
    }
}