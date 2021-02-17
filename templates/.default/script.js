BX.ready(function () {
    function initTable() {
        $('.answer-box-result-text').click(function () {
            var elementTr = $(this).children();
            if (elementTr.hasClass('not-visible'))
                elementTr.removeClass('not-visible');
            else
                elementTr.addClass('not-visible');
        });
    }
    initTable();
});

function openWin(url) {
    var myWindow = window.open(url, 'PrintWindow', 'width=700,height=400');
    myWindow.focus();
    myWindow.print();
    myWindow.close();
}