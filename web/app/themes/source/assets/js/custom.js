(function($) {
    $('a.ez-toc-toggle').on('click', function(event) {
        event.preventDefault();
        $('ul.toc_list').toggle('fast');
    });
})(jQuery);