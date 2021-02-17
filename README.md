# Результат голосования
Отображение результатов голосования с поименной привязкой к выбранным ответам.
Отображается в административной части.
```
Редакция 1С-Битрикс / Компонент tszh.voting.result
```

В файле \bitrix\modules\vdgb.tszhvote\classes\mysql\polls_vote.php надо добавить запрос, чтобы связать пользователя с его ответом
```sql
"ANSWER_ID" => Array(
    "FIELD" => "CVEA.ANSWER_ID", 
    "TYPE" => "int", 
    "FROM" => "INNER JOIN b_citrus_voting_event_answer CVEA ON (CVE.ID = CVEA.ID)"
),
```
