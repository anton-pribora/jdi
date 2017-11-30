<?php
/* @var $this ApCode\Executor\RuntimeInterface */
?>
JDI DB   Help

Работа с базой данных.

Синопсис

  <?=app()?> db КОМАНДА [АРГУМЕНТЫ]
  
Команда
  install     Установка базы данных с текущими настройками конфига.
  reinstall   Удаление и повторная установка базы данных.
  remove      Удаление базы данных.
  setup       Установка настроек базы данных.
  
Аргументы
  Аргументы, доступные для команды setup:
    mysql [ПАРАМЕТРЫ] [БАЗА_ДАННЫХ]
      Задаёт настройки подключения к базе данных MySQL.
      Параметры:
        --user=имя, -u имя              Имя пользователя для подключения к базе данных.
        --password=пароль, -p пароль    Пароль для подключения к базе данных.
        --host=хост, -h хост            Адрес подключения к базе данных.
        --port=порт, -P порт            Порт подключения.
        БАЗА_ДАННЫХ                     Имя базы данных (по умолчанию <?=Config()->get('db.default.mysql.database')?>).
      
    postgres [ПАРАМЕТРЫ] [БАЗА_ДАННЫХ]
      Задаёт настройки подключения к базе данных MySQL.
      Параметры:
        --user=имя, -u имя              Имя пользователя для подключения к базе данных.
        --password=пароль, -p пароль    Пароль для подключения к базе данных.
        --host=хост, -h хост            Адрес подключения к базе данных.
        --port=порт, -P порт            Порт подключения.
        БАЗА_ДАННЫХ                     Имя базы данных (по умолчанию <?=Config()->get('db.default.postgres.database')?>).
      
    sqlite [ИМЯ_ФАЙЛА]
      Задаёт настройки подключения к базе SQLite.
      Аргументы:
        ИМЯ_ФАЙЛА     Имя файла, где будет храниться база данных. По умолчанию
                      имеет значение <?=Config()->get('db.default.sqlite.file')?>.
        
Примеры использования
  Установка базы данных MySql:
    % <?=ExpandPath('@app')?> db setup mysql -utest -ptest -hlocalhost just_do_it
    % <?=ExpandPath('@app')?> db install
    
  Установка базы данных PostgreSQL:
    % <?=ExpandPath('@app')?> db setup postgres -utest -ptest -hlocalhost just_do_it
    % <?=ExpandPath('@app')?> db install
    
  Установка базы данных SQLite:
    % <?=ExpandPath('@app')?> db setup sqlite
    % <?=ExpandPath('@app')?> db install

  Удаление базы данных:
    % <?=ExpandPath('@app')?> db remove
