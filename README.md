## Sobre TT

Um projeto demo para teste técnico que envolve a criação de um aplicativo de processamento de pagamento.

É simples, escalável, assíncrono e distribuido.

## Aprendendo

Para subir a aplicação é necessário instalar e configurar o Ngrok, além de configurar o painel webhook do Asaas para apontar para a 
url gerada pelo Ngrok.

- Instalação ngrok e subindo o servidor (fazendo o webhook de pagamento funcionar)

```curl -sSL https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list && sudo apt update && sudo apt install ngrok```

```ngrok config add-authtoken 2nXqoaUiYRX1lK6DCLbN71VM5l1_6o66FPPfBuAYNBTcAZosK```

- Adicionar a url gerada no campo URL do Webhook no painel do Asaas em https://sandbox.asaas.com/customerConfigIntegrations/webhooks
https://{url_gerada_apos_server_iniciado}.ngrok-free.app/webhook

> ativar a opção "Fila de sincronização ativada", em caso de offline em https://sandbox.asaas.com/customerConfigIntegrations/webhooks

- Subindo a aplicação, siga os seguintes passos:

1. git clone https://github.com/carlos-enginner/tt.git;
2. atualize o .env (aproveitando as configs já existentes) com o conteúdo shell a seguir;
3. execute no diretorio criado o comando ```bash startup.sh```;
4. acesse http://localhost/checkout;

```shell
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:PeBf8xjOBlWSbVbp1tO8jEjSNbbQltGHnSzos/OXxyI=
APP_DEBUG=true
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=laravel_mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=reverb
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis

CACHE_STORE=redis
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=laravel_redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

REVERB_APP_ID=953685
REVERB_APP_KEY=lizrduj0r8ima9g3rqto
REVERB_APP_SECRET=uldvrgps5wfo8ml4ybqi
REVERB_SERVER_HOST="0.0.0.0"
REVERB_SERVER_PORT=6001
REVERB_HOST="0.0.0.0"
REVERB_PORT=6001
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"


ASAAS_API_URL="https://sandbox.asaas.com/api/v3/"
ASAAS_API_TOKEN="$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTIxMzg6OiRhYWNoXzY1MDBiMWJmLTY3YzMtNDVlZS1hNzI0LTFjOWI1ODcwMDc4MQ=="
LOCALHOST_PURCHASE_API_URL="http://localhost/purchase/"
```

## Código de conduta

Pode ser utilizado para fins de estudo/aprendizado

## Boas práticas do projeto:

- Projeto pensado com os princípios de arquitetura: async, escalável e distribuída;
- Uso do cache redis em seguimentação, abrindo a possibilidade de monitoria do uso do cache;
- Projeto segue as normas PSRs;
- Monitoria dos eventos transacionais no laravel.log (simulando api de monitoramento);
- Padronização do timezone "Brazil/Sao_Paulo";
- Projeto usa design patterns, kiss e yagni e dry nas novas classes criadas, especialmente no PaymentService.php
- Execução de testes unitários com casos de borda (edge case);
- Cobertura de código ativa;

## Pontos a melhorar do projeto:

- Adicionar user no dockerfile;
- Ocultar/encriptar o canal de broadcast;
- Adicionar "session in" no aplicativo;
- Segmentar as exceptions para mapear "todas" as possibilidades de falhas da API;
- Setar no package version as versões dos packages (para evitar baixar coisas e quebrar);
- Fazer uma validação descente no front, nas validações de frontend;
- Ampliar o coverage dos componentes críticos: PaymentService e ChargeService;
- Ampliar o uso do design patterns, kiss e yagni, dry nas novas classes criadas;
- Melhorar os controllers, segmentando as validações;
- Aplicar o padrão SAGA afim de asegurar consistencia nos dados (onde cada request posterior com falha anula a anterior);
- Adicionar camanda de segurança e rate limit na comunicação das APIs;
- Adicionar análise estática no projeto para monitoria de code smells;
- Adicionar testes de mutação;

## Pontos de atenção:

- componente critico (redis) sem persistencia de dados;

## Stacks:

- PHP 8.2
- Laravel 11
- Laravel Broadcasting (reverb)
- Node
- PHPunit
- Redis
- MYSQL 8
- Docker e docker-compose

## Demonstrativo

**[Demostração integração Asaas - teste funcional](https://www.loom.com/share/d5db3e659fbc4151bd74230b1eeb9143?sid=7721a326-be26-4a6c-88b1-00cf35fec0f3/)**

**[Demostração integração Asaas - back office](https://www.loom.com/share/483500d663224d75a92eb73d4fbd8f77?sid=b7b7e6a2-0fbb-4b1f-a5b0-d98585605f8e/)**

## License

O projeto é de cunho pessoal e instrutivo.
