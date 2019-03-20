
const axios = require('axios');

$('form').on('submit', function (e) {
  e.preventDefault();
  var formEl = $(this);
  var data = $(this).serialize();
  var url = $('input[type=hidden]#url', formEl).val();
  var identifier = $('input[name=parent]', formEl).val();
  var contentinput = $('textarea[name=content]', formEl).val();
  var newAreaComment = document.getElementById('new-pushed-' + identifier);
  axios.post(url, data)
    .then(function (response) {

      var data = response.data;
      var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }; 
      if (201 === response.status) {
        var html = '';
        html += '<div class="well">';
        html += '<span style="color:brown">'+data.user.firstname+' '+data.user.lastname+' </span> a répondu à '+data.publishedAt;
        html += '<br/>';
        html += data.comment;
        html += '</div>';

        $(newAreaComment).append(html);
        //$(html).insertAfter($(newAreaComment));
        $('textarea[name=content]', formEl).val('').empty();
        if($('input[name=parent]', formEl).val().length == 0){
          //On recharte la page si c'est un commentaire parent
          location.reload();
        }

      }
    }).catch(function (error) {
      console.log("Error: ",error);
    })

})