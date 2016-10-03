$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(".btn-back").click(function(event) {
  event.preventDefault();
  window.history.back();
});
