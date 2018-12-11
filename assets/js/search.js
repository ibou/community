// import './jquery.instantSearch.js';

// $(function() {
//     $('.search-field').instantSearch({
//         delay: 100,
//     });
// }); 
$(function() {
const axios = require('axios');
// Get the input box
var textInput = document.getElementById('search-field');
var form = document.getElementById('form-search');
console.log(form.action)

const action = form.action;
// const divresults = this.querySelector('div#results');
var output = document.getElementById('output');
console.log('Input action:', action);
// Listen for keystroke events
textInput.onkeyup = function (e) {
    const query = textInput.value
    const url = action+'?q='+query
    console.log('Input Value:', url);
    axios.get(url) 
    .then(response => {
        console.log("RESR",response) 
        const items = response.data;
        // output.className = 'container';
        output.innerHTML = response.data;
        
        
    })
    .catch(function (error) {
            // handle error
            if(error.response.status === 403){
                window.alert("No Result ");
            }else{
                window.alert("Une error s'est produite, re-essayer plustard ");
            }
            console.log("Error: ",error);
          })
          .then(function () {
            // always executed
          });
          

};
});