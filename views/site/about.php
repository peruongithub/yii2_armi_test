<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <br>
        <h5>Тестовое задание:</h5><br>
        Разработать простейший чат одностраничник.<br>
        <ol>
            <li>Пользователь заходит на сайт и видит окно чата и поле для ввода ника.</li>
            <li>После ввода ника у него появляется возможность писать сообщения, а также добавляем пользователя в базу, сохраняем его ник и ip.</li>
            <li>Все сообщения всех пользователей должны храниться в одной таблице.</li>
            <li>Новые сообщения в чате должны отображаться снизу, после добавления нового сообщения чат должен прокручиваться до самого низа.</li>
            <li>Справа должны отображаться все ники пользователей вместе с городами.</li>
            <li>Все должно работать через аякс, без перезагрузки страницы.Решить поставленную задачу используя: ● PHP 5.6+ ● Yii2 ● MySQL● PSR­2 (http://www.php­fig.org/psr/psr­2/) и PSR­4 (http://www.php­fig.org/psr/psr­4/) ● Apache 2.4 / Nginx ● https://getcomposer.org/ для автолоада классов и подключения сторонних библиотек, используемых для решения задачи (написанных вами в том числе) ● http://getbootstrap.com/ для стилизации HTML страниц Решение прислать ввиде ссылки на код проекта на https://github.com/ В репозитории обязан быть файл README.md, содержащий инструкцию как установить проект и просмотреть функциональность.</li>
        </ol>

</div>