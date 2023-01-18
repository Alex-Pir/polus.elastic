# bitrix_elastic
Модуль для работы с эластиком (Битрикс)

Основная идея в том, чтобы использовать данный модуль для построения высоконагруженного каталога товаров с использованием Elasticsearch.

Написано на основе https://github.com/ensi-platform/laravel-elastic-query

Сейчас реализовано:
- Установка/удаление модуля
- Выборка документов по фильтру
- Агрегации (планируется использование в умном фильтре)

Бэклог:
- Страница в админ. панели Битрикса для возможности формирования индекса по инфоблокам
- Создание индекса и индексация