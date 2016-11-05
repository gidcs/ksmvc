(function($){
  $(function(){
    $('.button-collapse').sideNav();
    $('.parallax').parallax();
    $(".dropdown-button").dropdown();
    $('.collapsible').collapsible();
    $('select').material_select();
    if($('div.alert_error').length!=0){
      Materialize.toast($('div.alert_error').html(), 2500);
    }
    else if($('div.alert_success').length!=0){
      Materialize.toast($('div.alert_success').html(), 2500);
    }
    $('.indicator').addClass('teal');

  }); // end of document ready
})(jQuery); // end of jQuery name space

