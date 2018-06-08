# Event
##Как все работает

#### Инициализция
```php
use Sweetkit\Foundation\Event\{EventManager, PriorityCollection, Event, Subscriber};
use Sweetkit\Foundation\Event\Adapter\ArrayFileAdapter as EventArrayFileAdapter;

$ev = new EventManager;
```


#### Подготовленная загрузка
```php
$file = "/Volumes/store/data/www/localhost/events.php";
$ev = new EventManager(new EventArrayFileAdapter($file));
```

##### Пример файла
```php
return [
    ["db:connection2",function($listener,$event,$attributes){
    	echo "db:collection2";
    },["key" => "values"],Sweetkit\Foundation\Event\Event::NORMAL,true],
    ["db:connection5","Sweetkit\Foundation\Event\Test@SendUser",["key" => "values"],Sweetkit\Foundation\Event\Event::HIGH,true],
];
```
#### Регистрация слушателя
```php
$ev->listen("Имя события","Имя класса с методом или анонимная функция","Параметры","Приоритет","Можно ли отменять");

$ev->listen("db:connection",function($listener,$event,$attrs){
            return $attrs;
        },["key1" => "value"], Event::HIGH,true);
        
$ev->listen("db:connection","ClassName@methodName",["key1" => "value"], Event::HIGH,true);        
```

#### Уровни приоритетов
- Event::LOW
- Event::NORMAL
- Event::HIGH

#### Вызов события 
```php
$ev->fire("Имя события","Параметры");

$ev->fire("db:connection",["att" => 13]);       
```
#### Подписка на события
```php
class SubscribeEmail extends Subscriber
{

    public function onSendEmail($listener,$event,$attributes)
    {
        echo "onSendEmail";
    }

    public function onRecipEmail($listener,$event,$attributes)
    {
        echo "onRecipEmail";
    }
    public function subscribe(EventManager $event)
    {
        $event->listen("email:send_email",[$this,"SendEmail"]);
        $event->listen("email:recip_email",[$this,"RecipEmail"]);

    }
}
$ev->subscribe(new SubscribeEmail);
```
##На будущее 
- Пусто