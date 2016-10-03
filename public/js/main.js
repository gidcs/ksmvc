$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(".btn-back").click(function(event) {
  event.preventDefault();
  history.back(-1);
});
