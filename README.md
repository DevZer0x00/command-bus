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
