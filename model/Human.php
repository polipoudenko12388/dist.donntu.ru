<?php

class Human
{
    // Подключение к БД таблице "users"
    protected $conn;

    // Свойства
    public $id;
    public $surname;
    public $name;
    public $patronymic;
    public $datebirth;
    public $email;
    public $phone;
    public $photo;
    public $snils;
    public $inn;
    public $name_country;
    public $name_regions;
    public $name_type;
    public $name_settlements;


    // Конструктор класса human
    public function __construct($db)
    {
        $this->conn = $db;  
    }

    // поиск человека в бд вуза в таблице "human" по его снилсу или инн
    public function find_human_dbubiversity($snils_inn)
    {
        // запрос, есть ли человек по инн или снилку в бд вуза
        $query =  $this->conn->prepare("Select `dbuniversity`.`human`.`id`, `dbuniversity`.`human`.`surname`, `dbuniversity`.`human`.`name`, `dbuniversity`.`human`.`patronymic`, `dbuniversity`.`human`.`datebirth`, `dbuniversity`.`human`.`email`, 
        `dbuniversity`.`human`.`phone`, `dbuniversity`.`human`.`photo`,  `dbuniversity`.`human`.`snils`, `dbuniversity`.`human`.`inn`, `dbuniversity`.`country`.`name` as `name_country`,
         `dbuniversity`.`regions`.`name` as `name_regions`, `dbuniversity`.`types_of_settlements`.`name` as `name_type`, `dbuniversity`.`names_of_settlements`.`name` as `name_settlements`
        from `dbuniversity`.`human` inner join `dbuniversity`.`place_of_residence` on `dbuniversity`.`place_of_residence`.`id` = `dbuniversity`.`human`.`id_placeresidence`
        inner join `dbuniversity`.`country` on `dbuniversity`.`country`.`id` = `dbuniversity`.`place_of_residence`.`id_country`
        left join `dbuniversity`.`regions` on `dbuniversity`.`regions`.`id` = `dbuniversity`.`place_of_residence`.`id_regions`
        left join `dbuniversity`.`types_of_settlements` on `dbuniversity`.`types_of_settlements`.`id` = `dbuniversity`.`place_of_residence`.`id_typesettlements`
        inner join  `dbuniversity`.`names_of_settlements` on `dbuniversity`.`names_of_settlements`.`id` = `dbuniversity`.`place_of_residence`.`id_namesettlements`
        where (`dbuniversity`.`human`.`snils`= ? or `dbuniversity`.`human`.`inn`= ?)");
        $query->execute([$snils_inn, $snils_inn]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        return $result;
    }
}