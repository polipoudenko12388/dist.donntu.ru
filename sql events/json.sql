-- SELECT log_group_json
-- FROM dbwebsite_university.log_group
-- WHERE id = 123 and (log_group_json->"$.attendance_group[0].date"="2023-05-21" and log_group_json->"$.attendance_group[0].type_class"="Л")



-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_SET(`log_group_json`, "$.attendance_group[0].array_students", '[{"name":"Имя", "surname": "Фамилия", "id_student": 89, "patronymic": "Отчество", "presence_class": "-"}]')
-- WHERE id = 103

-- добавление  в конец массива
-- UPDATE dbwebsite_university.log_group
-- SET log_group_json = JSON_ARRAY_APPEND(log_group_json, '$.attendance_group[0].array_students', json_object('name','Имя', "surname","Фамилия", "id_student",89, "patronymic","Отчество","presence_class", "-"))
-- WHERE id = 103


-- изменение значений массива 
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_REPLACE(`log_group_json`, "$.attendance_group[0].array_students[1]", json_object("name","Имя2", "surname","Фамилия2", "id_student",89, "patronymic","Отчество2","presence_class", "-"))
-- WHERE id = 103

-- удаление
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_REMOVE(`log_group_json`, "$.attendance_group[0].array_students") WHERE log_group.id = 103

-- set null + insert or update (после редактирования как вставить обратно в бд)
-- UPDATE dbwebsite_university.log_group
-- SET `log_group_json` = JSON_INSERT(`log_group_json`, json_object("name","Имя2", "surname","Фамилия2"));


-- SELECT log_group_json->'$.Control_educational_process.passes'
-- FROM dbwebsite_university.log_group
-- WHERE id = 180 


set @array=JSON_ARRAY(json_object("host","localhost"),
                  json_object("user","root"),
                  json_object("pass","pass"));
                  
set @y='{ "a": 1}';
set @yy= '[{ "id_student": 31,"surname": "Бабичев"},{"id_student": 32, "surname": "Гейвандов"}]';

select JSON_REPLACE(log_group_json, '$.tasks.topic_material', 'topical_material')
FROM dbwebsite_university.log_group
WHERE id = 152 and $.tasks.id_educat_material=39

                                    

