/*
*  Altair Admin
*  @version v2.4.0
*  @author tzd
*  @license http://themeforest.net/licenses
*  components_cards.js - components_cards.html
*/

$(function() {
    $('#randomize').click(function(e) {
        e.preventDefault();
        var rand = Math.floor((Math.random() * 100) + 1);

        altair_md.card_progress('#random_progress',rand);
        $('#progress_value').text(rand);

    });
});