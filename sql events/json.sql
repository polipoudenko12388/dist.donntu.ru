SELECT log_group_json, log_group_json->"$.attendance_group[0].array_students[0]"
FROM dbwebsite_university.log_group
WHERE id = 103

-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_SET(`log_group_json`, "$.attendance_group[0].array_students", '[{"name":"Имя", "surname": "Фамилия", "id_student": 89, "patronymic": "Отчество", "presence_class": "-"}]')
-- WHERE id = 103

-- добавление  в конец массива
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_ARRAY_APPEND(`log_group_json`, "$.attendance_group[0].array_students[0]", json_object("name","Имя", "surname","Фамилия", "id_student",89, "patronymic","Отчество","presence_class", "-"))
-- WHERE id = 103


-- изменение значений массива 
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_REPLACE(`log_group_json`, "$.attendance_group[0].array_students[1]", json_object("name","Имя2", "surname","Фамилия2", "id_student",89, "patronymic","Отчество2","presence_class", "-"))
-- WHERE id = 103

-- удаление
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_REMOVE(`log_group_json`, "$.attendance_group[0].array_students[1]") WHERE log_group.id = 103
