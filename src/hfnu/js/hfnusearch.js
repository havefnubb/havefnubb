$(document).ready(function() {
    var hfnuSearchInput = $("#hfnu_q");
    var url = hfnuSearchInput.attr('data-autocomplete-url');
    hfnuSearchInput.autocomplete(url, {
        width: 300,
        multiple: true,
        matchContains: true
    });
});
