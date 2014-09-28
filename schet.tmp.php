<html>
    <head>
        <title>{title}</title>
        <link href="../../css/schet_print.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div>
        <table>
            <tr>
                <td class="rekvizity">
                    Счет для: {ooo}, Аккаунт: {name}<br><br>
                    <b>Поставщик: ООО "МХ Телеком"</b><br>
                    ИНН: 7723613717   КПП: 772301001<br>
                    Адрес: 109559, г. Москва, ул. Маршала Баграмяна, д. 4<br>
                    Телефон: (495) 514-3667<br>
                    р/с: 40702810600000009525 в ЗАО " РУССТРОЙБАНК "<br>
                    к/с: 30101810400000000490<br>
                    БИК: 044583490<br>
                </td>
            </tr>
            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            <tr>
                <td class="nomer">
                        Счёт №: {year_mont}-{no}
                </td>
            </tr>
            <tr>
                <td class="date">
                        Дата: {date} г.
                </td>
            </tr>
            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="platelshik">Плательщик: {ooo}, Аккаунт: {name}</span>
                    <table class="tbl">
                        <tr>
                            <th class="pp">п/п</th>
                            <th class="name">Предмет счета</th>
                            <th class="num">Кол-во</th>
                            <th class="cost">Стоимость, руб.</th>
                            <th class="sum_bn">Сумма, руб.</th>
                            <th class="sum_n">Сумма налога, руб.</th>
                            <th class="sum">Сумма с учётом налога, руб.</th>
                        </tr>
                        {tbl}
                        <tr>
                            <td colspan="4" class="itogo" align="right"><b>Итого:</b></td>
                            <td class="sum_bn">{sum_bn}</td>
                            <td class="sum_n">{sum_n}</td>
                            <td class="sum">{sum}</td>
                        </tr>
                    </table>
                    <span class="sum_text">Сумма прописью: {sum_text}</span>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <table class="mp">
                        <tr>
                            <td><br>Генеральный директор<br></td>
                            <td>____________________</td>
                            <td>/ Минцевич А.О. /</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><br>М.П.<br></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><br>Главный бухгалтер<br></td>
                            <td>____________________</td>
                            <td>/ Минцевич А.О. /</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td class="primechanie">Примечание: При отсутствии оплаты счета до конца текущего месяца услуги по договору будут приостановлены до полного погашения задолженности.</td></tr>
        </table>
        </div>
    </body>
</html>