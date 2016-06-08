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
###Использование
```php
$api = $this->get('hawk_api.api')->getApi();
$api
    ->registerUser($id)
    ->getToken($id, $this->getApi()->getSalt())
    ->execute()
    ->getResult('getToken')
;
```
или так:
```php
$msg = new Message();
$msg
	->setFrom('mail_demon')
	->setTo($this->getUser())
	->setText(['msg' => 'test'])
	->setEvent('new_push')
;

$gMessage = new GroupMessage();
$gMessage
	->setFrom('mail_demon')
	->setGroups(['groups'])
	->setText(['msg' => 'test'])
	->setEvent('event')
;

$this
	->container
	->get('event_dispatcher')
	->dispatch(Message::NEW_MESSAGE, $msg)
	->getResult() //HawkApi
	->getResult()
;

$this
	->container
	->get('event_dispatcher')
	->dispatch(GroupMessage::NEW_MESSAGE, $gMessage)
	->getResult() //HawkApi
	->getResult()
;

```

```javascript
$.post(Routing.generate('hawk_token'), {}, function (data) {
    if(data.errors === false)
    {
        HAWK_API.init({
            user_id: data.result.id,
            token: data.result.token,
            url: data.result.ws,
            debug: true
        });

        HAWK_API.unbind_handler('new_push');
        HAWK_API.bind_handler('new_push', function(e, msg){
            if(msg.from === 'hawk_client')
            {
                return;
            }

            //делаем что-нибудь
        });
    }
    else
    {
        if(data.errors !== 'no_user')
        {
            console.error(data.errors);
        }
    }
});
```

Про методы, доступные для использования вы можете прочесть в [документации](https://github.com/postHawk/hawk_api/wiki)
