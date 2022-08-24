<h4>Логи</h4>
<?php if(!empty($logs)) : ?>
    <table class="table logs-table">
        <tr>
            <th>
                Дата/время
            </th>
            <th>
                Действие
            </th>
            <th>
                Описание
            </th>
            <th>
                Пользователь
            </th>
        </tr>
        <?php foreach($logs as $log) : ?>
            <tr>
                <td>
                    <?= $log['datetime'] ?>
                </td>
                <td>
                    <?= $log['placeName'] ?>
                </td>
                <td>
                    <?= $log['string'] ?>
                </td>
                <td>
                    <?= $log['user'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <table class="table logs-table">
        <tr>
            <td>Логов нет</td>
        </tr>
    </table>
<?php endif; ?>
