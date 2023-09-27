(function($){
    'use_strict';
    $(function(){
        $('#opus-check-connection').click(function(e){
            e.preventDefault();
            $.post('admin-ajax.php', {action: 'test_opus_connection'}, function(r){
                if (r.success) {
                    alert('Vellykket tilkobling til API. Løsningen er klar til bruk, men vi anbefaler å fylle ut innstillingene for backupløsning også i tilfelle nedetid.');
                } else {
                    alert('Kunne ikke koble til, sjekk at du har riktige innstillinger. Hvis du er sikker på at innstillingene er riktige, må du kontakte opus og sjekke at tjenesten er aktivert');
                }
            });
        });
        $('#clinician-add').on('click', function(e){
            e.preventDefault();
            var template = wp.template('clinician-order'),
            html = template();
            $('#clinician-order').append(html);
        });
        $('body').on( 'click', '.clinician-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $('#treatment-add').on('click', function(e){
            e.preventDefault();
            var template = wp.template('fallback-treatments');
            html = template();
            $('#opus-fallback-treatments').append(html);
        });
        $('body').on( 'click', '.treatment-remove', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
        $('.opus-sortable').sortable({
            handle: '.handle'
        });
    });
})(jQuery);