console.log(new Date());

const axios = require('axios');

function onClickBtnLike(event){
    event.preventDefault();
    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');

    const icon = this.querySelector('i');
    const ival = $(icon).attr('id');
    axios.get(url)
    .then(function(response){
        spanCount.textContent = response.data.likes;
        $( "."+ival ).toggleClass( "fa-thumbs-up" );
        if(icon.classList.contains('fa-thumbs-up')){
            icon.innerHTML = "";
        }else{
            icon.innerHTML = "&#xf087;";
        }
    })
    .catch(function (error) {
        if(error.response.status === 403){
            window.alert("Connectez vous pour pouvoir effectuer cette action");
        }else{
            window.alert("Une error s'est produite, re-essayer plustard ");
        }
        console.log("Error: ",error);
      })
      .then(function () {
        // always executed
      });
}

 document.querySelectorAll('a.js-like').forEach(function(link){
     link.addEventListener('click',  onClickBtnLike);
 })
{/* <i class="far fa-thumbs-up"></i>
<i class="fas fa-thumbs-up"></i> */}