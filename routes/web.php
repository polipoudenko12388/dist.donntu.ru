<?php

use Illuminate\Support\Facades\Route;
date_default_timezone_set('Europe/Moscow');


// переход на страницу Регистрация-Отображение ролей
Route::get('/registration',  'RegistrationController@getListRole');

// Обработка регистрации
// (например: Варанкин - БИ-19)
// отправка на сервер: {"id_role_user":"1", "id_role_db_univ":null, "inn_snils":"12345678945", "login":"loginvarankin", "email":"emailvarankin@gmail.com",  "password":"passwordvarankin", "date_registration":"2023-05-03" }
// (например: Секирин Александр - преподаватель)
// отправка на сервер: {"id_role_user":"5", "id_role_db_univ":"2", "inn_snils":"12345678935", "login":"loginsekirin", "email":"emailsekirin@gmail.com",  "password":"password25", "date_registration":"2023-05-03" }
// отправка на клиент: "error" or "info"
Route::post('/registration',  'RegistrationController@RegistrationProcessing');

// Проверка авторизации
// отправка на сервер: {"email_login":"login21","password":"password21", "platform":"Windows", "mobile":"false","brand":"Opera GX", "version_brand":"97"} - Теплова
// отправка на клиент: "error" or { "id_role_user": 5,  "id_user_reg": 39,  "token": ""}
// token будет хранить инфу об id_user,id_role_user,id_teacher_student
Route::post('/authorization',  'AuthorizationController@Authorizationcheck');

// Страницы преподавателя
Route::prefix('/teacher')->group(function () 
{
    Route::prefix('/disciplines')->group(function () 
    {
        // отправка на сервер: {"id_user_reg":"39","token":"", "name_disc":"", "id_disc":null}
        // отправка на клиент: "error" или 
        // [
        //     {
        //       "id_disc": 23,
        //       "name_disc": "Дисциплина1",
        //       "id_teacher_creator": 9,
        //       "fon": "http://dist.donntu.ru:3030/storage/fileserver/defaultimage/default_fon_discipline.png",
        //       "id_institute": 2,
        //       "id_faculty": 10,
        //       "id_department": 1,
        //       "edit_disc": true,
        //       "array_flow": [
        //         {
        //           "id_flow": 1,
        //           "name_flow": "Поток1"
        //         },
        //         {
        //           "id_flow": 5,
        //           "name_flow": "Поток4"
        //         },
        //         {
        //           "id_flow": 7,
        //           "name_flow": "Поток6"
        //         }
        //       ]
        //     }, {} ] - весь список или с фильтрацией
        Route::post('/', 'DisciplineController@ListDisciplines'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_institute":null, "id_faculty":null, "id_department":null, "name_disc":null}
        // отправка на клиент: "error" or "info"
        Route::post('/createdisc', 'DisciplineController@ResultCreateDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"",  "id_disc":null }
        // отправка на клиент: "error" or "info"
        Route::post('/deletedisc', 'DisciplineController@ResultDeleteDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_disc":null, "id_institute":null, "id_faculty":null, "id_department":null, "new_name":null, "id_new_creator":null }
        // отправка на клиент: "error" or "info"
        Route::post('/editdisc', 'DisciplineController@ResultEditDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_disc":null, "id_flow":null,  "number_hours_reading":null, "teachers":[1,2] - array }
        // отправка на клиент: "error" or "info"
        Route::post('/addflowindisc', 'DisciplineController@ResultAddFlowinDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_disc_flow":null}
        // отправка на клиент: "error" or "info"
        Route::post('/deleteflowindisc', 'DisciplineController@ResultDeleteFlowinDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_disc":null, "id_flow":null}
        // отправка на клиент: "error" or 
        // {
        //     "id_disc_flow": 31,
        //     "name_flow": "Поток6",
        //     "number_hours_reading": 200,
        //     "arrayteacher": [
        //       {
        //         "id_teacher": 10,
        //         "role": 5,
        //         "surname": "Матях",
        //         "name": "Ирина",
        //         "patronymic": "Владимировна",
        //         "email": "emailteacher2@gmail.com",
        //         "phone": "79491231153",
        //         "creator": true
        //       }, {}] }
        Route::post('/teachersflowdisc', 'DisciplineController@ListTeachersFlowinDisc'); // +++

        // отправка на сервер: {"id_user_reg":"39","token":"", "number_hours_reading":null, "id_disc_flow":null, "add_teachers":null, "delete_teachers":[1,2] }
        // отправка на клиент: "error" or "info"
        Route::post('/editflowindisc', 'DisciplineController@ResultEditFlowinDisc'); // +++

       
        Route::prefix('/posts')->group(function ()
        {
            // отправка на сервер: {"id_user_reg":39, "token":"", "id_disc_flow":22, "text":"hello2", "attendance_button":true, 
            // "date_end_button":"2023-05-25 15:15:00", "id_type_class":1, "files":[] }
            // отправка на клиент: "error" or "info"
            Route::post('/createpost', 'PostController@ResultCreatePost');

            // отправка на сервер: {"id_user_reg":39, "token":"", "id_post":null }
            // отправка на клиент: "error" or "info"
            Route::post('/deletepost', 'PostController@ResultDeletePost');
            
            // отправка на сервер: {"id_user_reg":39, "token":"", "id_post":null,"text":null, "files":[]}
            // отправка на клиент: "error" or "info"
            Route::post('/editpost', 'PostController@ResultEditPost');

            // отправка на сервер: {"id_user_reg":39, "token":"", "id_disc_flow":22}
            // отправка на клиент: "error" or 
            // [
            //     {
            //         "id_post": 17,
            //         "id_disc_flow": 22,
            //         "id_teacher_creator": 9,
            //         "date_create_post": "2023-05-26 00:31:58", // при редактировании поста обновляется дата 
            //         "id_type_post": 1, // 1-сообщение, может редактироваться, 2 тип - нет
            //         "text": null,
            //         "attendance_button": false,
            //         "date_end_button": null,
            //         "edit": true, // был ли уже отредактирован пост (true/false)
            //         "surname__teacher_creator": "Теплова",
            //         "name__teacher_creator": "Ольга",
            //         "patronymic__teacher_creator": "Валентиновна",
            //         "files": [
            //           "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/posts/postid17/2.txt",
            //           "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/posts/postid17/spisok_gruppy.jpg",
            //           "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/posts/postid17/svoystva_konteynerov.docx"
            //         ]
            //       }, 
            // {"files":[]} ]
            Route::post('/', 'PostController@ListPosts');
        });

        Route::prefix('/educatmaterial')->group(function ()
        {
            // отправка на сервер: {"id_user_reg":39, "token":"", "id_disc_flow":22, 
            // "topic_material":"Тема материала", "id_type_material":"1", "id_type_assessment":1, 
            // "date_assignment":"2023-05-25", "max_score":5,"min_score":1, "files":[] }
            // отправка на клиент: "error" or "info" (название темы можно не писать, если добавляется лекция)
            Route::post('/addmaterial', 'EducatationMaterialController@ResultAddMaterial');

            // удаление самого задания 
            // отправка на сервер: {"id_user_reg":39, "token":"","id_educat_material":null ,"id_disc_flow":null}
            // отправка на клиент: "error" or "info"
            Route::post('/deletetask', 'EducatationMaterialController@ResultDeleteMaterialTasks');
        
            // редактирование задания 
            // отправка на сервер: {"id_user_reg":39, "token":"","id_disc_flow":22, "id_educat_material":null, "topic_material":"Тема материала", "id_type_assessment":1, 
            // "date_assignment":"2023-05-25", "max_score":5,"min_score":1, "files":[]}
            // отправка на клиент: "error" or "info"
            Route::post('/edittask', 'EducatationMaterialController@ResultEditMaterialTasks');

            // отправка на сервер: {"id_user_reg":39, "token":"","id_disc_flow":22, "id_educat_material":null}
            // отправка на клиент: "error" or
            // [
            //     {
            //       "id_educat_material": 53,
            //       "id_disc_flow": 22,
            //       "topic_material": "задание3",
            //       "id_type_assessment": 1,
            //       "type_assessment": "оценка",
            //       "id_teacher_added": 9,
            //       "surname": "Теплова",
            //       "name": "Ольга",
            //       "patronymic": "Валентиновна",
            //       "date_added": "2023-05-31",
            //       "date_assignment": "2023-05-28",
            //       "explanation_task": null,
            //       "max_score": 5,
            //       "min_score": 3,
            //       "files": [
            //         "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/tasks/id53task/filesteacher/поворот.JPG"
            //       ]
            //     }
            //   ]
            Route::post('/', 'EducatationMaterialController@ListMaterialTasks');

            // окно конретного задания
            // отправка на сервер: {"id_user_reg":39, "token":"","id_disc_flow":22, "id_educat_material":null}
            // отправка на клиент: "error" or
            // [
            //     {
            //       "group": {
            //         "name_group": "ИСТ-19а",
            //         "count_students": 12,
            //         "id_log_group": 266,
            //         "array_students": [
            //           {
            //             "id_student": 19,
            //             "surname": "Белая",
            //             "name": "Алина",
            //             "patronymic": "Юрьевна",
            //             "type_execution": "назначено",
            //             "score": "неизвестно"
            //           }
            //         ]
            //       },
            //       "countrecord": {
            //         "count_type_naznacheno": 12,
            //         "count_type_sdano": 0,
            //         "count_in_score": 0,
            //         "count_type_dorabotat": 0,
            //         "count_type_sdano_opozd": 0
            //       }
            //     }
            // ]
            Route::post('/task', 'EducatationMaterialController@ResultGetTaskDiscipline');

            // окно конретного задания студента
            // отправка на сервер: {"id_user_reg":39, "token":"", "id_educat_material":null,"id_log_group":null, "id_student":null }
            // отправка на клиент:
            // {
            //     "group": "ИСТ-19а",
            //     "surname": "Пойденко",
            //     "name": "Полина",
            //     "patronymic": "Александровна",
            //     "id_type_execution": 2,
            //     "type_execution": "сдано",
            //     "date": "2023-05-05",
            //     "score": "неизвестно",
            //     "files": [
            //       "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/tasks/id53task/id1group/id24Poydenko/Пойденко_ИСТ-19а_лаб_5.docx"
            //     ]
            //   }
            Route::post('/taskstudent', 'EducatationMaterialController@ResultGetTaskStudentDiscipline');

            // сохранения результата проверки задания студента
            // отправка на сервер: {"id_user_reg":39, "token":"", "id_educat_material":null,"id_log_group":null, "id_student":null, "score":null,"id_type_execution":null, "type_execution":null }
            // отправка на клиент: "error" or "info"
            Route::post('/checktaskstudent', 'EducatationMaterialController@ResultSaveCheckTaskStudentDiscipline');

            // отправка на сервер: {"id_user_reg":39, "token":"", "id_educat_material":null, "explanation_task":null}
            Route::post('/editexplanationtask', 'EducatationMaterialController@ResultEditExplanationTask');
 
        });
        
        // отправка на сервер: {"id_user_reg":"39","token":"", "id_educat_material":null, "id_disc_flow":22, "name_file":null}
        Route::post('/deletefilesdiscipline','EducatationMaterialController@DeleteFileLecture');

        // для полины 
        Route::post('/createlog', 'DisciplineController@createlogbuf'); // +++
        
    });
   
    // отправка на сервер: {"id_user_reg":"39","token":"", "id_student_teacher":null, "id_role":null}
    // отправка на клиент: "error" or
    // {
    //     "id": 43,
    //     "surname": "Теплова",
    //     "name": "Ольга",
    //     "patronymic": "Валентиновна",
    //     "datebirth": "1970-01-12",
    //     "email": "emailteacher1@gmail.com",
    //     "phone": "79491231152",
    //     "photo": "http://dist.donntu.ru:3030/storage/fileserver/photohuman/id43teplova.jpg",
    //     "name_country": "Россия",
    //     "type_regions": "республика",
    //     "name_regions": "Донецкая Народная",
    //     "type_settlements": "город",
    //     "name_settlements": "Макеевка",
    //     "array_data_workes": [
    //       {
    //         "role": "преподаватель",
    //         "status": "работает",
    //         "position": "старший преподаватель",
    //         "institute": "Компьютерных наук и технологий",
    //         "faculty": "Информационных систем и технологий",
    //         "department": "автоматизированных систем управления"
    //       },
    //       {}
    //     ],
    //     "date_registration": "2023-05-02"
    //   }
    Route::post('/profile', 'GeneralUserController@DataProfile'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":1}
    // отправка на клиент: "error" or [ { "id_institute": 1, "name_institute": "Горного дела и геологии"}, {} ] - весь список или по фильтрации
    Route::post('/institutions', 'GeneralTeacherController@ListInstitutions'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":null,"id_faculty":null}
    // отправка на клиент: "error" or [ { "id_faculty": 9, "name_faculty": "Интеллектуальных систем и программирования"  }, {}] - весь список или по фильтрации
    Route::post('/faculties',    'GeneralTeacherController@ListFaculties'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":null, "id_faculty":null, "id_department":null}
    // отправка на клиент: "error" or [ { "id_department": 1, "name_department": "автоматизированных систем управления" }, {}] - весь список или по фильтрации
    Route::post('/departments',  'GeneralTeacherController@ListDepartments'); // +++
    
    // отправка на сервер: {"id_user_reg":"39","token":""} 
    // отправка на клиент:  "error" or [ { "id_teacher": 9, "surname": "Теплова", "name": "Ольга",  "patronymic": "Валентиновна" }, {}]
    Route::post('/teachers',  'GeneralTeacherController@ListTeachers'); // +++

    // отправка на сервер: {"id_user_reg":"39","token":"", "id_institute":null, "id_faculty":null, "id_department":null, "namegroup":null}
    // отправка на клиент:  "error" or
    // [
    //   {
    //     "id_group_db_website": 1,
    //     "name_group": "ИСТ-19а",
    //     "institute": "Компьютерных наук и технологий",
    //     "faculty": "Информационных систем и технологий",
    //     "department": "автоматизированных систем управления"
    //   }, {} ] весь список групп или по фильтрации
    Route::post('/groupsinfo',  'GroupController@ListGroupsInfo'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", "id_group":1 }
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_student": 19,
    //       "surname": "Белая",
    //       "name": "Алина",
    //       "patronymic": "Юрьевна"
    //     }, {} ] по фильтрации 
    Route::post('/students',  'GroupController@ListStudentsGroup'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", id_flow:null} - 
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_group": 1,
    //       "name_group": "ИСТ-19а"
    //     }, {} ] - весь список групп или по id_flow
    Route::post('/groups',  'GroupController@ListGroups'); // +++

    // отправка на сервер: {"id_user_reg":"39","token":""}
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_teacher": 9,
    //       "surname": "Теплова",
    //       "name": "Ольга"
    //     }, {} ]
    Route::post('/creatorsflows',  'FlowController@ListCreatorFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", "id_creator":null, "id_group":null, "name_flow":null }
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_flow": 1,
    //       "name_flow": "Поток1",
    //       "surname_creator": "Теплова",
    //       "name_creator": "Ольга",
    //       "edit_flow": true,
    //       "array_disc": [
    //         {
    //           "name_disc": "Дисциплина1"
    //         },
    //         {
    //           "name_disc": "Дисциплина4"
    //         }
    //       ]
    //     }, {} ] - весь список или по фильтрам
    Route::post('/flows',  'FlowController@ListFlow'); // +++

    // отправка на сервер: {"id_user_reg":"39","token":"", ,"name_flow":"new_name","groups":[1,2] - array}
    // отправка на клиент: "error" or "info"
    Route::post('/createflow',  'FlowController@ResultCreateFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", ,"id_flow":11}
    // отправка на клиент: "info" or "error"
    Route::post('/deleteflow',  'FlowController@ResultDeleteFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", ,"id_flow":11, "new_name":""}
    Route::post('/updatenameflow',  'FlowController@ResultUpdateNameFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", ,"id_flow":14, "groups":[1,2] - array}
    // отправка на клиент: "info" or "error"
    Route::post('/deletegroupsflow',  'FlowController@ResultDeleteGroupsFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", ,"id_flow":14, "groups":[1,2] - array}
    // отправка на клиент: "info" or "error"
    Route::post('/insertnewgroupsflow',  'FlowController@ResultInsertNewGroupsFlow'); // +++ 


    // -------------------------------------------------------------------------------------------------------------
    // 2 часть 19.05.2023
    Route::prefix('/logs')->group(function () 
    {
        // отправка на сервер: {"id_user_reg":"39","token":""}
        // отправка на клиент: "error" or
        // [
        //     {
        //       "id_type": 1,
        //       "name_type": "посещаемости"
        //     },
        //     {
        //       "id_type": 2,
        //       "name_type": "успеваемости"
        //     }
        //   ]
        Route::post('/typelogs',  'LogController@ListTypeLogs'); 

        // отправка на сервер: {"id_user_reg":"39","token":""}
        // отправка на клиент: "error" or
        // [
        //     {
        //       "id_type": 1,
        //       "name_type": "Л"
        //     }, {} 
        // ]
        Route::post('/typeclass',  'LogController@ListTypeClass'); 

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_type":null, "id_disc":null,"id_flow":null, "id_group":null}
        // отправка на клиент: error or 
        // {
        //     "id_log_group": 125,
        //     "id_teacher_edit": null,
        //     "log": {
        //       "id_group": 1,
        //       "name_group": "ИСТ-19а",
        //       "attendance_group": [
        //         {
        //           "date": "2023-05-21",
        //           "type_class": "Л",
        //           "array_students": [
        //             {
        //               "name": "Алина",
        //               "surname": "Белая",
        //               "id_student": 19,
        //               "patronymic": "Юрьевна",
        //               "presence_class": "-"
        //             },{} ]}]}} - только фильтрация
        Route::post('/',  'LogController@getLog'); 

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_log_group": 125, "log":null}
        // отправка на клиент: error or 
        Route::post('/updatelog',  'LogController@ResultUpdateLog'); 

        // запрос на разрешение изменения журнала
        // отправка на сервер: {"id_user_reg":"39","token":"", "id_log_group": 132}
        // отправка на клиент: error or 
        Route::post('/permitedit',  'LogController@ResultPermitEditLog'); 
    });

     // отправка на сервер: {"id_user_reg":"39","token":""}
     // отправка на клиент: error or
    //  [
    //     {
    //       "id_type": 1,
    //       "name_type": "Лабораторная"
    //     },
    //     {
    //       "id_type": 2,
    //       "name_type": "Лекция"
    //     }
    //   ]
    Route::post('/type_material',  'EducatationMaterialController@ListTypeMaterial'); 


    // отправка на сервер: {"id_user_reg":"39","token":""}
    // отправка на клиент: error or
    // [
    //     {
    //       "id_type": 1,
    //       "name_type": "оценка"
    //     },
    //     {
    //       "id_type": 2,
    //       "name_type": "балл"
    //     }
    //   ]
    Route::post('/type_assessment',  'EducatationMaterialController@ListTypeAssessment'); 

    // отправка на сервер: {"id_user_reg":"39","token":""}
    // отправка на клиент: error or
    // [
    //     {
    //       "id_type": 1,
    //       "name_type": "назначено"
    //     },
    //     {
    //       "id_type": 2,
    //       "name_type": "сдано"
    //     },
    //     {
    //       "id_type": 3,
    //       "name_type": "с оценкой"
    //     },
    //     {
    //       "id_type": 4,
    //       "name_type": "доработать"
    //     }
    //   ]
    Route::post('/type_execution',  'EducatationMaterialController@ListTypeExecution'); 
});

// отправка на сервер: {"id_user_reg":"39","token":""}
// отправка на клиент: "info" or "error"
Route::post('/exit','GeneralUserController@exit'); 

// отправка на сервер: {"id_user_reg":"39","token":"", "id_disc_flow":null, "id_type_material":null}
// отправка на клиент: "error" or 
// [
//     {
//       "id_educ_material": 11,
//       "name_file": "Lekciya_1.doc",
//       "file": "http://dist.donntu.ru:3030/storage/fileserver/disciplines/id23Disciplina1_20230517_160849/id22Potok1_20230517_162857/lectures/Lekciya_1.doc"
//     }, {} 
// ]
Route::post('/filesdiscipline','EducatationMaterialController@FilesEducatMaterial'); 

// отправка на сервер: {"id_user_reg":"39","token":"", "id_disc_flow":null}
// отправка на клиент: error or 
// {
//     "name_flow": "Поток3",
//     "groups": [
//       {
//         "namegroup": "ИСТ-19а",
//         "count_students": 12,
//         "students": [
//           {
//             "id_student": 19,
//             "role": 1, 
//             "surname": "Белая",
//             "name": "Алина",
//             "patronymic": "Юрьевна"
//             "photo": "http://dist.donntu.ru:3030/storage/fileserver/defaultimage/user_photo.svg"
//           },{},
//         ]
//       },
//       {
//         "namegroup": "ИСТ-19б",
//         "count_students": 8,
//         "students": [
//           { },{}
//         ]
//       },
//       {
//         "namegroup": "БИ-19",
//         "count_students": 1,
//         "students": [ { } ]
//       },
//       {
//         "namegroup": "КМД-19",
//         "count_students": 0,
//         "students": null
//       }
//     ]
//   }
Route::post('/studentsdiscipline','GeneralUserController@ListStudentsDiscipline'); 

