<?php

namespace App\Console\Commands;

use App\Service\Cidade\CidadeImportarService;
use Illuminate\Console\Command;

class CidadeImportarCommand extends Command
{
    protected $signature = 'importar:cidades';
    protected $description = 'Importar cidade de API externa';

    public function __construct(private CidadeImportarService $service)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Iniciar Importação...');

        $result = $this->service->executar();

        $this->info('Importação finalizada! Quantidade de cidades importadas: ' . $result['total_importado']);
    }
}
