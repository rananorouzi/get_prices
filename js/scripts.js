function removeTable() {
    $('#table_container').remove();
}
function callAlkoData(obj) {
    href = $(obj).attr('href');
    page = href.split('_')[1];
    $.post('ajax/price_ajax.php', {'page': page}, function (data) {
        if (data['result'] == true) {
            $('#table_prices').html(data['html']);
        } else {
            alert('Somethind is wrong. try again later!')
        }
    }, 'json');
}
function callAlkoDataInitial(page) {
    $.post('ajax/price_ajax.php', {'page': page}, function (data) {
        if (data['result'] == true) {
            $('#table_prices').html(data['html']);
        } else {
            alert('Somethind is wrong. try again later!')
        }
    }, 'json');
}
function changeOrderAmount(id, changeType) {
    if (id != '' && changeType != '') {
        currentAmount = parseInt($('#amount_' + id).text());
        $.post('ajax/price_amount_ajax.php', {
            'id': id,
            'changeType': changeType,
            'oldamount': currentAmount
        }, function (data) {
            if (data['result'] == true) {
                if (changeType == 'add') {
                    $('#amount_' + id).text(currentAmount + 1);
                } else if (changeType == 'clear' && currentAmount > 0) {
                    $('#amount_' + id).text(currentAmount - 1);
                }
            } else {
                alert('Somethind is wrong. try again later!')
            }
        }, 'json');
    } else {
        alert('ERROR!');
    }
}