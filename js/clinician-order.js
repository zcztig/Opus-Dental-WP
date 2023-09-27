(function($){
    'use_strict';
    $(function(){
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
    });
})(jQuery);