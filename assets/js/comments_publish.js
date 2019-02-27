
const axios = require('axios');

$('form').on('submit', function (e) {
  e.preventDefault();
  var formEl = $(this);
  var data = $(this).serialize();
  var url = $('input[type=hidden]#url', formEl).val();
  var identifier = $('input[name=parent]', formEl).val();
  var content = $('textarea[name=content]', formEl).val();
  var newAreaComment = document.getElementById('new-pushed-' + identifier)
  newAreaComment.innerHTML = "";

  axios.post(url, data)
    .then(function (response) {

      
      console.log("REP POST", response);
      if (201 === response.status) {
        var html = '';
        html += '<div class="well">';
        html += content;
        html += '</div>';
        // location.reload();

        newAreaComment.innerHTML = html;
      }
    }).catch(function (error) {
      console.log("Error: ",error);
    })

}) 