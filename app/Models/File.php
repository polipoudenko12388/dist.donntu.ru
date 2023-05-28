<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    // перевод в латиницу из кириллицы (названия папки, файла)
    public static function translit($name)
    {
        // Оставляем в имени файла только те символы, содерж. в pattern
		$pattern = "[^a-zа-яё0-9-_]";
		$name = mb_eregi_replace($pattern, '_', $name);
			
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 
			
			'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya');
 
		return strtr($name, $converter);
    }

	// создание json файла посещаемости (при добавлении потока в дисциплину)
	public static function create_file_log_attend_group_json($id_group,$name_group,$array_students)
	{
		$log_attend_group = 
		[
			"id_group" => $id_group,
			"name_group" => $name_group,
			"attendance_group" => 
			[[
				"type_class" => "Л", // тип занятия (по дефолту)
				"date" => date('Y-m-d'), // дата занятия (по дефолту сегодняшняя)
				"array_students" => $array_students
			]]
		]; 
		return json_encode($log_attend_group);
	}

	
		// создание json файла успеваемости (при добавлении потока в дисциплину)
		public static function create_file_gradebook($name_group,$array_students_types_control, $array_students_intersessional_control, $array_students_passes,$array_students_offset,$array_students_exam)
		{
			$gradebook_group = 
			[
				"name_group" => $name_group,
				"tasks" => [], // лабораторные
				"other_types_control" => // другие виды котроля
				[
					[
						"name_control" => " дефолтное задание1",
						"array_students" => $array_students_types_control
					]
				],
				"Control_educational_process" => // Контроль учебно-воспитательного процесса
				[
					"intersessional_control"=> //МСК
					[
						"array_students" => $array_students_intersessional_control
					],
					"passes"=> // пропуски
					[
						"array_students" => $array_students_passes
					]
				],
				"Results_control_educational_process" => // Итоги контроля учебно-воспитательного процесса
				[
					"offset" => // зачет
					[
						"array_students" => $array_students_offset
					],
					"exam" => // экзамен
					[
						"array_students" => $array_students_exam
					]
				]
			]; 
			return json_encode($gradebook_group);
		}
}
