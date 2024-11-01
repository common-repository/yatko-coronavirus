jQuery(document).ready(function () {
    var fields = [
        'region', 'total_cases', 'active_cases',
        'recovered', 'deaths', 'death_ratio', 'recovery_ratio'
    ];

    var selector = jQuery('.feed-selector select');
    var container = jQuery('.feed-text');

    if(selector) {
        selector.on('change', function (e) {
            container.css('opacity', .5);

            jQuery.post(
                xhr.url,
                {
                    'action': 'feed_update',
                    'region': selector.val()
                },
                function(response) {
                    var data = JSON.parse(response);

                    for(let field of fields) {
                        if(data.hasOwnProperty(field)) {
                            jQuery('[data-id="' + field +  '"').text(data[field]);
                        }
                    }

                    container.css('opacity', 1);
                }
            );
        })
    }
});