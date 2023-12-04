init();
function init() {
    $('[data-toggle="tooltip"]').tooltip();

    btnSubmitLoding();

    $("document").on("pjax:end", '.pjax-filter', function () {
        btnSubmitLoding();
    });

    $('input[type="number"]').on('keyup change paste input', function (e) {
        this.value = this.value < 0 ? 0 : this.value;
    });

    $('.modalButton').click(function () {
        $('#header-modal').html($(this).attr('modal-header'));
        $('#modal').modal('show')
            .find('#modalContent')
            .html('Loading...')
            .load($(this).attr('value'));
    });

    $('#dynamic-form').on('keydown', 'input, select', function (e) {
        return disableEnter(e);
    });

    window.setInterval(function () {
        $('select, input, textarea').removeClass('is-valid');
    }, 1000);

    initDate();
    initDecimal();
}

function toDecimal(selector, value) {
    $(selector).val(toFixed(value));
}

function toIntDecimalNumber(value) {
    if (value.length > 0) {
        return parseFloat(value.replace('.', '').replace(',', '.'));
    }
    return value;
}

function toFixed(value, number = 2) {
    return value.toFixed(number);
}

function initDecimal() {
    var options = {
        'alias': 'decimal',
        'radixPoint': ',',
        'groupSeparator': '.',
        'digits': 2,
        'autoGroup': true,
        'autoUnmask': true,
        'unmaskAsNumber': true,
        'removeMaskOnSubmit': true
    };

    $('.number').inputmask(options);

    $('.integer').inputmask(Object.assign(options, { 'digits': 0 }));
}

function initDate() {
    $('.mask-date').inputmask('dd-mm-yyyy');
}

function toCountdown(id, date, datenow) {
    var count = new Date(date).getTime();
    var now = new Date(datenow).getTime();
    var left = count - now;
    var iv = setInterval(function () {
        left -= 1000;
        var days = Math.floor(left / (1000 * 60 * 60 * 24));
        var hours = Math.floor((left % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((left % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((left % (1000 * 60)) / 1000);

        var str = "";
        str += days > 0 ? days + "h " : "";
        str += days > 0 || hours > 0 ? hours + "j " : "";
        str += minutes + "m " + seconds + "s";

        $(id).html("<code>" + str + "</code>");
        if (left < 0) {
            $(id).html("<code>Expired!</code>")
            clearInterval(iv);
            location.reload();
        }
    }, 1000);

}

function btnLoading(target) {
    var tmp = target.html();
    target.html('<span class="fa fa-spin fa-spinner"></span> Loading ...');
    target.prop('disabled', true);
    return tmp;
}

function btnSubmitLoding() {
    $('body').on('beforeSubmit', 'form', function () {
        btnLoading($(this).find('button[type="submit"]'));
    });
}

function toCurrency(val, isPlain = false) {
    if (isPlain) {
        return val.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, '$1.');
    }

    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        // These options are needed to round to whole numbers if that's what you want.
        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    });

    return formatter.format(val);
}

function formatDate(date = new Date()) {
    let d = new Date(date);
    let month = (d.getMonth() + 1).toString();
    let day = d.getDate().toString();
    let year = d.getFullYear();

    if (month.length < 2) {
        month = '0' + month;
    }

    if (day.length < 2) {
        day = '0' + day;
    }

    return [year, month, day].join('-');
}

function print_report(url) {
    window.open(url, 'popUpWindow', 'height=500,width=800,left=1000,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
}

function disableEnter(e) {
    const keyCode = e.keyCode || e.which;

    if (keyCode === 13) {
        e.preventDefault();
//        var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
//        focusable = form.find('input,a,select,button,textarea').filter(':visible');
//        next = focusable.eq(focusable.index(this) + 1);
//        if (next.length) {
//            next.focus();
//        } else {
//            form.submit();
//        }
        return false;
    }
}

const plugins = {
    id: 'custom_canvas_background_color',
    beforeDraw: (chart, args, options) => {
        const { ctx } = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = "rgba(255, 255, 255)";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.reClient();
    }
};

function createChart(labels, datasets, type = 'line', selector = 'myChart') {
    const ctx = document.querySelector('.' + selector).getContext('2d');

    const myChart = new Chart(ctx, {
        type: type,
        data: {
            labels,
            datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        footer: (ttItem) => {
                            if (type === 'pie') {
                                let sum = 0;
                                let dataArr = ttItem[0].dataset.data;
                                dataArr.map(data => {
                                    sum += Number(data);
                                });

                                let percentage = (ttItem[0].parsed * 100 / sum).toFixed(2) + '%';
                                return `Persentase : ${percentage}`;
                            }
                        }
                    }
                },
            }
        },
        plugins: [plugins]
    });
}

function degToRad(degrees) {
    return degrees * (Math.PI / 180);
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371000; // Radius Bumi dalam meter

    const dLat = degToRad(lat2 - lat1);
    const dLon = degToRad(lon2 - lon1);

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(degToRad(lat1)) * Math.cos(degToRad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = earthRadius * c;
    return distance;
}