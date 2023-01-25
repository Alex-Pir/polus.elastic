# bitrix_elastic
Модуль для работы с эластиком (Битрикс)

Основная идея в том, чтобы использовать данный модуль для построения высоконагруженного каталога товаров с использованием Elasticsearch.

Написано на основе https://github.com/ensi-platform/laravel-elastic-query

Сейчас реализовано:
- Установка/удаление модуля
- Выборка документов по фильтру
- Агрегации (планируется использование в умном фильтре)
- Создание индекса и индексация

Бэклог:
- Страница в админ. панели Битрикса для возможности формирования индекса по инфоблокам

Пример использования:

```php
class ClothIndex extends Index
{
    public function indexName(): string
    {
        return 'cloth';
    }

    public function settings(): array
    {
        return [
            'settings' => [
                'index.max_result_window' => 500000,
                'index.max_inner_result_window' => 100000,
                'index.number_of_shards' => 1,
                'index.number_of_replicas' => 0,
            ],
            'mappings' => [
                'properties' => [
                    'cloth_id' => ['type' => 'keyword'],
                    'section_id' => ['type' => 'keyword'],
                    'code' => ['type' => 'text'],
                    'artnumber' => ['type' => 'text']
                ]
            ]
        ];
    }

    public function indexing(): void
    {
        $allElements = ... //Получаем элементы

        foreach (array_chunk($allElements, 100) as $elements) {
            $bulk = [];

            foreach ($elements as $element) {
                $bulk[] = ['index' => ['_id' => $element['ID']]];
                $bulk[] = json_encode([
                    'cloth_id' => $element['ID'],
                    'section_id' => $element['SECTION_ID'],
                    'code' => $element['CODE'],
                    'artnumber' => $element['ARTICLE']
                ]);
            }

            if ($bulk) {
                $this->bulk($bulk);
            }
        }
    }
}
```

Для создания индекса вызываем:
```php
$index = new ClothIndex();
$index->create();
```

Для индексации вызываем
```php
$index = new ClothIndex();
$index->indexing();
```