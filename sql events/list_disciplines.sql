select list_disciplines.id as id_disc, list_disciplines.name as `name_disc`, list_disciplines.id_teacher as id_teacher_creator, fon,
discipline_flow.id_flow, flow.name as `name_flow`, discipline_flow.number_hours_reading, teacher_discipline.id_teacher
FROM dbwebsite_university.list_disciplines 
left join dbwebsite_university.discipline_flow on discipline_flow.id_list_discipline = list_disciplines.id
left join dbwebsite_university.flow on discipline_flow.id_flow = flow.id
left join dbwebsite_university.teacher_discipline on teacher_discipline.id_discipline_flow = discipline_flow.id
where (list_disciplines.id_teacher=10 or teacher_discipline.id_teacher=10)