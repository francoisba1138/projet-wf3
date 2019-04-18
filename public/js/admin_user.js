$(function () {

    $('.btn-delete').click(function (event) {
        // empêche d'aller vers la page de suppression
        event.preventDefault();

        // ouvre la modale de confirmation
        $('#modal-confirm-delete').modal('show');

        // l'url de la page de suppression
        var href = $(this).attr('href');

        // au clic du bouton de confirmation, redirection vers la page de suppression
        $('.btn-confirm-delete').click(function () {

            location.href = href;
        });

    });


    $('.btn-user-content').click(function (event)
    {
        event.preventDefault();

        var href = $(this).attr('href');

        $.get(
            href,
            function (response)
            {

                var $modal = $('#modal-user-content');

                // retour en texte brut
                 $modal.find('.modal-body').html(response);




                $modal.modal('show');
            })
    })


});


