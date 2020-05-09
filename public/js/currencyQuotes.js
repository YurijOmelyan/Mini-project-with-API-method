const datepicker = "#datepicker";
const containerData = '#container-data';

$(document).ready(
    $(function () {
        $(datepicker).datepicker({
            dateFormat: "yy-mm-dd"
        });

        $(datepicker).change(function () {
            getDataForDate({'date': $(datepicker).val()});
        })

    })
)

function getDataForDate(data) {
    $.get('getData', data, 'json').done(function (json) {
        let res = JSON.parse(json);
        if ('data' in res) {
            showMessage(res.data);
            return;
        }
        showQuotationTable(res);
    });
}

function showMessage(mes) {
    $(containerData).html(`<p>${mes}</p>  `);
}

function showQuotationTable(obj) {
    let htmlTabel = '<table class="table table-striped"><thead><tr><th scope="col">#</th>';
    for (let key in obj[0]) {
        htmlTabel += `<th scope="col">${key}</th>`
    }
    htmlTabel += '</tr></thead><tbody>';

    for (let valute in obj) {
        htmlTabel += `<tr><th scope="row">${Number(valute) + 1}</th>`;
        for (let key in obj[valute]) {
            htmlTabel += `<td>${obj[valute][key]}</td>`
        }
        htmlTabel += '</tr>';
    }
    htmlTabel += '</tbody></table>';
    $(containerData).html(htmlTabel);
}