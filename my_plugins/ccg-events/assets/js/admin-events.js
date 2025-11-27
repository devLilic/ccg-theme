(function($){
    $(document).ready(function(){

        // === ONE-DAY EVENT: ascunde/arată câmpurile de final ===
        function toggleEndDateFields() {
            var isOneDay = $('#ccg_event_is_one_day').is(':checked');
            var $wrapper = $('.ccg-event-end-wrapper');
            if (isOneDay) {
                $wrapper.hide();
            } else {
                $wrapper.show();
            }
        }

        $('#ccg_event_is_one_day').on('change', toggleEndDateFields);
        toggleEndDateFields();

        // === PROGRAM FILE ===
        var programFileFrame;

        $('#ccg_event_program_file_button').on('click', function(e){
            e.preventDefault();

            if (programFileFrame) {
                programFileFrame.open();
                return;
            }

            programFileFrame = wp.media({
                title: 'Selectează fișierul de program',
                button: { text: 'Folosește fișierul' },
                multiple: false
            });

            programFileFrame.on('select', function(){
                var attachment = programFileFrame.state().get('selection').first().toJSON();
                $('#ccg_event_program_file').val(attachment.id);
                $('#ccg_event_program_file_label').text(attachment.title || attachment.filename);
            });

            programFileFrame.open();
        });

        $('#ccg_event_program_file_clear').on('click', function(){
            $('#ccg_event_program_file').val('');
            $('#ccg_event_program_file_label').text('');
        });

        // === GALLERY ===
        var galleryFrame;

        $('#ccg_event_gallery_button').on('click', function(e){
            e.preventDefault();

            if (galleryFrame) {
                galleryFrame.open();
                return;
            }

            galleryFrame = wp.media({
                title: 'Selectează imagini pentru galerie',
                button: { text: 'Folosește imaginile' },
                multiple: true
            });

            galleryFrame.on('select', function(){
                var selection = galleryFrame.state().get('selection');
                var ids = [];
                var $preview = $('#ccg_event_gallery_preview');

                $preview.empty();

                selection.each(function(attachment){
                    attachment = attachment.toJSON();
                    ids.push(attachment.id);
                    var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    $preview.append('<img src="'+url+'" />');
                });

                $('#ccg_event_gallery').val(ids.join(','));
            });

            galleryFrame.open();
        });

        $('#ccg_event_gallery_clear').on('click', function(){
            $('#ccg_event_gallery').val('');
            $('#ccg_event_gallery_preview').empty();
        });

    });
})(jQuery);
