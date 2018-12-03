console.log("Date ::", new Date());

const axios = require('axios');

function onClickBtnLike(event){
    event.preventDefault();
    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');  

    const icon = this.querySelector('i');   
    axios.get(url) 
    .then(function(response){ 
        spanCount.textContent = response.data.likes; 
        
        if(icon.classList.contains('fa-thumbs-down')){ 
            icon.classList.replace('fa-thumbs-down','fa-thumbs-up');
        }else{ 
            icon.classList.replace('fa-thumbs-up','fa-thumbs-down');
        }

    })
    .catch(function (error) {
        // handle error
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