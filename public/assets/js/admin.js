$(function(){  /* search btn */   $("#search-btn").on('click', function(){    window.location = web_url + '?filter=' + $("#filter").val().replace(/\s/g, "-");  });  $('#filter').keypress(function(e) {    if (e.which == 13) {      $("#search-btn").trigger('click');    }  });    $( "#maxpage" ).change(function() {    var max = $('#maxpage').val();      window.location.href = max;  });  $( "#descasc" ).change(function() {    var descasc = $('#descasc').val();    window.location.href = descasc;  });  });                                                      