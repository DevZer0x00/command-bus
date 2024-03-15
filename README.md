# Command Bus
Command bus for symfony projects

## Установка

````bash
composer require 
````

## Работа с обертками

Обертки должны имплементить **DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface**

На данный момент пакет включает в себя два вида оберток:
1. LockHandlerWrapper
2. DoctrineTransactionHandlerWrapper

Для того чтобы использовать обертки, их необходимо прописать в **config/services.yaml**

```yaml
services:
  DevZer0x00\CommandBus\Wrapper\Lock\LockHandlerWrapperFactory:
    arguments:
      $lockFactory: '@lock.default.factory'
    tags:
      - { name: app.command_handler.wrapper_factory, priority: -100 }
```

## PHPStan

Для того чтобы в хэндлерах анализатор кода не ругался на "Access to an undefined property" добавьте в ваш файл
конфигурации анализатора следующий код:

```yaml
parameters:
  universalObjectCratesClasses:
    - DevZer0x00\CommandBus\CommandInterface
```
