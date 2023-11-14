## Тестовое задание для KMA
[Тестовое задание для KMA.pdf](Тестовое задание для KMA.pdf)

## Развертывание проекта
Клонируйте проект.
```
git clone https://github.com/Reshetnikov/testKma.git
cd testKma/
```
Скопируйте содержимое файла ".env.example" в файл ".env".
```
cp .env.example .env
```
Отредактируйте его в случаи необходимости.

Выполните команды:
```
docker compose build
docker compose up -d
```

## Проверка функционала
Запустите скрипт отправляющий сообщения в RabbitMq
```
docker compose exec app-php sh -c "php ./app/send.php"
```
Откройте в браузере ссылку [http://localhost:8081/](http://localhost:8081/). По мере отправки и обработки сообщений обновляйте страницу.


### Проверить содержимое баз данных можно выполнив:
```
docker compose exec -it clickhouse clickhouse-client
USE default
SELECT * FROM requests;
```
```
docker compose exec -it  mariadb mariadb -umariaUser -pmariaPassword
USE mariaDb
SELECT * FROM requests;
```
### Получение списка контейнеров
```
docker compose ps
```