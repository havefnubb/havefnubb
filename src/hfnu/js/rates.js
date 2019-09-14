$(document).ready(function() {
    $('.rating-form').each(function() {
        var ratingForm = $(this);
        var options = {
            success:   function(response) {
                ratingForm.find('.rates-result').html(response);
                ratingForm.find('.post-rates-msg').show();
            },
            url:       this.getAttribute('data-ajax-action'),
            type:      "post",
            dataType:  "text"
        };

        ratingForm.find('.starsrating').rating({
            focus: function(value, link){
                var tip = ratingForm.find('.rating-hover');
                tip[0].data = tip[0].data || tip.html();
                tip.html(link.title || 'value: '+value);
            },
            blur: function(value, link){
                var tip = ratingForm.find('.rating-hover');
                tip.html(tip[0].data || '');
            },
            callback: function(value, link){
                $(this.form).ajaxSubmit(options);
            }
        });
    });
});
