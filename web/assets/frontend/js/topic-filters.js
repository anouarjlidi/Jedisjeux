(function($) {

    "use strict";

    var $form = $('form', '#topic-filters');
    var $select = $('#criteria_mainTaxon_mainTaxon');
    var taxon = $('#topic-filters').data('taxon');

    $(document).ready(function () {
        selectTaxon(taxon);
        handleFormSubmit();
    });

    function selectTaxon(taxon) {
        $('option', $select).each(function() {
            if (taxon === $(this).val()) {
                $(this).attr('selected', 'selected');
            }
        });
    }

    function handleFormSubmit() {
        if (window.location.href.match('/jeu-de-societe/')) {
            return;
        }

        $form.submit(function(event) {
            event.preventDefault();

            var slug = $select.val();
            window.location = Routing.generate('app_frontend_topic_index_by_taxon', { slug: slug } );
        });
    }
})(jQuery);
