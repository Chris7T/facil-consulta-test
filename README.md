# Facil Consulta Test

## Pré-requisitos
- Docker
- Git

## Instalação

1. Clone o repositório
```bash
git clone git@github.com:Chris7T/facil-consulta-test.git
```

2. Entre no diretório do projeto
```bash
cd facil-consulta-test/
```

3. Instale as dependências do projeto
```bash
docker run --rm -v "$(pwd)":/opt/project -w /opt/project laravelsail/php82-composer:latest composer install
```

4. Configure o ambiente
```bash
cp .env.example .env
```

5. Inicie os containers
```bash
./vendor/bin/sail up -d
```

6. Entre no container
```bash
docker exec -it facil-consulta-test-laravel.test-1 bash
```

7. Configure as permissões
```bash
chown -R sail:sail storage
```

8. Gere as chaves do projeto
```bash
php artisan jwt:secret
php artisan key:generate
```

9. Execute as migrations
```bash
php artisan migrate
```

## API
A documentação completa da API está disponível em:
```
localhost/docs/api
```

## Postman Collection
Na raiz do projeto, dentro da pasta `/postman`, você encontrará o arquivo da collection do Postman com todas as rotas da API. Para utilizar:

1. Abra o Postman
2. Clique em Import
3. Selecione o arquivo `Facil-Consulta.postman_collection.json`
4. A collection será importada com todas as rotas configuradas

## Testes

### Executando os Testes
Para executar os testes, dentro do container Docker:
```bash
php artisan test
```

Para executar um teste específico:
```bash
php artisan test --filter NomeDoTeste
```

### Estrutura dos Testes
O projeto contém testes unitários para os seguintes serviços:

#### Autenticação
- `CadastrarServiceTest`: Testa o cadastro de usuários
- `LoginServiceTest`: Testa o processo de login
- `LogoutServiceTest`: Testa o processo de logout

#### Cidade
- `CidadeImportarServiceTest`: Testa a importação de cidades do IBGE
- `CidadeListarServiceTest`: Testa a listagem de cidades

#### Médico
- `MedicoCriarServiceTest`: Testa a criação de médicos
- `MedicoListarPorCidadeServiceTest`: Testa a listagem de médicos por cidade

#### Paciente
- `PacienteAtualizarServiceTest`: Testa a atualização de dados do paciente
- `PacienteCriarServiceTest`: Testa a criação de pacientes

#### Consulta
- `ConsultaCriarServiceTest`: Testa a criação de consultas médicas

### Cobertura dos Testes
Os testes unitários cobrem:
- Fluxos de sucesso
- Tratamento de erros e exceções
- Validações de negócio
- Integridade dos dados

Cada teste é isolado usando mocks, garantindo que apenas a unidade em teste está sendo verificada, sem dependências externas como banco de dados ou serviços externos.