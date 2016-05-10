# HawkApiBundle
bundle for symfony based on post hawk api

###Установка:

```bash
composer require post-hawk/hawk-api-bundle dev-master
```
Для windows:
```bash
cd path/to/project
mklink /J web\bundles\hawkapi vendor\post-hawk\hawk-api\Resources\public\
```

Для unix:

```bash
cd path/to/project
ln -s web\bundles\hawkapi vendor\post-hawk\hawk-api\Resources\public\
```

###Кофигурация:
```yml
#app/config/config.yml
hawk_api:
    client:
        host: client_addres #ip или домен
        port: 7777 #порт, который слушает клиент
        key: 'ваш апи ключ'
```

```yml
#app/config/routing.yml
hawk:
    resource: '@HawkApiBundle/Controller/'
    prefix:   /hawk
```

```html
<script src="{{ asset('/bundles/hawkapi/js/hawk_api.js') }}"></script>
```