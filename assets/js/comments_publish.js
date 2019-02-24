console.log("Comments ", new Date());

const axios = require('axios');

document.querySelectorAll('button.publish-comment').forEach(function(link){  
    link.addEventListener('click',  onClickBtnComment);    
})

function onClickBtnComment (event){
    event.preventDefault();
    const form = this.form; 
    var ct = document.querySelector('textarea');  
    var frm = $("form").serialize();
    //  const parent = form.name;    
    console.log("cocococ hello ",frm)
    // axios.post(url,data) 
    // .then(function(response){ 
         
    //     console.log("REP POST", response)
    // })
}