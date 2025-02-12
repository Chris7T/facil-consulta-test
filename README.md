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

## Testes
Para executar os testes, use o comando:
```bash
php artisan test
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