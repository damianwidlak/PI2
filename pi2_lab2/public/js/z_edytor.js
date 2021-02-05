$(function () {
    $("#btnKatalogWyslij").click(function () {
        const $frm = $("#formKatalog")

        $.post($frm.attr('action'), $frm.serializeArray(), function (resp) {
            if (resp == 'ok') {
                alert("Katalog został dodany.");
                $("textarea").val('');
                $('#modalEdycja').modal('hide');
                location.reload();
            } else {
                alert("Wystąpił błąd podczas dodawania katalogu.");
            }
        });
        return true;
    })

    $("#btnWyslij").click(function () {
        const $frm = $("#formPlik")

        $.post($frm.attr('action'), $frm.serializeArray(), function (resp) {
            if (resp == 'ok') {
                alert("Plik został zapisany.");
                $("textarea").val('');
                $('#modalEdycja').modal('hide');
                location.reload();
            } else {
                alert("Wystąpił błąd podczas zapisu.");
            }
        });
        return true;
    })

    $('#modalEdycja').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var action = button.data('action') // Extract info from data-* attributes
        if (action === 'new') {
            // Dodawanie nowego pliku
            $("#btnWyslij").prop('disabled', false);
            $("#spinner").hide();
            $('input[name="nazwaPliku"]').prop("readonly", false);
            $('input').val('');
            $("textarea").val('');
            return true;
        } else {
            // Edytowanie istniejącego pliku
            var path = button.data('filename');
            var path_array = path.split('/');
            var filename = path_array[path_array.length-1];

            // console.log('file: ' + filename);
            $('input[name="nazwaPliku"]').prop("readonly", true);
            $('input[name="nazwaPliku"]').val(filename);
            $('textarea[name="zawartoscPliku"]').val("Trwa pobieranie zawartości, czekaj...");
            $('textarea[name="zawartoscPliku"]').prop("readonly", true);
            var url = "/dropbox/pobierz?path=";
            if (!path.startsWith('/')) {
                url = url + '/';
            }
            $.get(url + path, function (data) {
                $('textarea[name="zawartoscPliku"]').val(data);
                $('textarea[name="zawartoscPliku"]').prop("readonly", false);
                $("#btnWyslij").prop('disabled', false);
                $("#spinner").hide();
            });
        }
    })
})

