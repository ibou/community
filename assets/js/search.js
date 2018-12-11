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
        var html = "";
        // output.className = 'container';
        //output.innerHTML = response.data;
        
//iterating through all the item one by one.
     items.forEach(function(val) {
        //getting all the keys in val (current array item)
        var keys = Object.keys(val);
        //assigning HTML string to the variable html
        html += "<div class = 'cat'>";
        //iterating through all the keys presented in val (current array item)
        keys.forEach(function(key) {
            //appending more HTML string with key and value aginst that key;
            html += "<strong>" + key + "</strong>: " + val[key] + "<br>";
        });
        //final HTML sting is appending to close the DIV element.
        html += "</div><br>";
        });
        output.innerHTML = html;
        
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