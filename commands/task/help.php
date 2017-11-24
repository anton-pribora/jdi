<?php

/* @var $this ApCode\Executor\RuntimeInterface */

?>
JDI TASK   Help

Выводит или обновляет информацию по номеру задания.

Синопсис
  
  <?=app()?> task НОМЕР_ЗАДАНИЯ [НОМЕР_ЗАДАНИЯ ...] [ПАРАМЕРЫ]
  
Аргументы
  НОМЕР_ЗАДАНИЯ   Порядковый номер задания в очереди.
  
Параметры
  --json      Вывод задания в формате json.
  --ready     Установить статус 'готов к выполнению'.
  --cancel    Установить статус 'отмена'.
  --remove    Удалить задание.
  
  --set-*     Установить дополнительные данные.
  --unset-*   Удалить долнительные данные.

Примеры использования

  Выставить текущий прогресс и описание для задания.
  
    % <?=app()?> task 12 --set-progress=70 --set-progress-text='Выписка билетов'

  Вывести задания в формате json.
  
    % <?=app()?> task 1 2 3 --json

