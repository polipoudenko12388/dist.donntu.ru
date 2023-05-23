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
        //     }, {} ]
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

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_disc":null, "id_flow":null, "name_flow":null, "number_hours_reading":null, "teachers":[1,2] - array }
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
            // отправка на сервер: {"id_user_reg":"39","token":"", id_disc_flow:null, "id_teacher_creator":null, text:null,attendance_button:true,
            // date_end_button: "2023-05-22 15:15:00", id_type_class:""}
            Route::post('/createpost', 'PostController@ResultCreatePost');

        });

        // для полины 
        Route::post('/createlog', 'DisciplineController@createlogbuf'); // +++
        
    });
   
    // отправка на сервер: {"id_user_reg":"39","token":""}
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
    Route::post('/profile', 'GeneralTeacherController@DataProfile'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":1}
    // отправка на клиент: "error" or [ { "id_institute": 1, "name_institute": "Горного дела и геологии"}, {} ]
    Route::post('/institutions', 'GeneralTeacherController@ListInstitutions'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":null,"id_faculty":null}
    // отправка на клиент: "error" or [ { "id_faculty": 9, "name_faculty": "Интеллектуальных систем и программирования"  }, {}]
    Route::post('/faculties',    'GeneralTeacherController@ListFaculties'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"","id_institute":null, "id_faculty":null, "id_department":null}
    // отправка на клиент: "error" or [ { "id_department": 1, "name_department": "автоматизированных систем управления" }, {}]
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
    //   }, {} ]
    Route::post('/groupsinfo',  'GroupController@ListGroupsInfo'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", "id_group":1 }
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_student": 19,
    //       "surname": "Белая",
    //       "name": "Алина",
    //       "patronymic": "Юрьевна"
    //     }, {} ]
    Route::post('/students',  'GroupController@ListStudentsGroup'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", id_flow:null}
    // отправка на клиент:  "error" or
    // [
    //     {
    //       "id_group": 1,
    //       "name_group": "ИСТ-19а"
    //     }, {} ]
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
    //     }, {} ]
    Route::post('/flows',  'FlowController@ListFlow'); // +++
    // отправка на сервер: {"id_user_reg":"39","token":"", "id_flow":1 }
    // отправка на клиент:  "error" or
    // {
    //     "id_flow": 1,
    //     "name_flow": "Поток1",
    //     "groups": [
    //       {
    //         "id_group": 1,
    //         "name_group": "ИСТ-19а"
    //       }, {},{}
    //     ]
    //   }
    Route::post('/groupsflow',  'FlowController@ListGroupsFlow'); // +++
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

        // отправка на сервер: {"id_user_reg":"39","token":"", "id_type":null, "id_disc":null,"id_flow":null, "id_group":null}
        // отправка на клиент: error or 
        // {
        //     "id_log_group": 125,
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
        //             },{} ]}]}}
        Route::post('/log',  'LogController@getLog'); 

        // отправка на сервер: {"id_user_reg":"39","token":"" }
        Route::post('/',  'LogController@getListLogs'); 
    });

});

// отправка на сервер: {"id_user_reg":"39","token":""}
// отправка на клиент: "info" or "error"
Route::post('/exit','GeneralUserController@exit'); 
