// import './jquery.instantSearch.js';

// $(function() {
//     $('.search-field').instantSearch({
//         delay: 100,
//     });
// });
$(function () {
    const axios = require('axios');
    // Get the input box
    var textInput = document.getElementById('search-field');
    var form = document.getElementById('form-search');
    const spanCountResult = this.querySelector('span.js-count-result'); 

    const action = form.action;
    var output = document.getElementById('output');

    // Listen for keystroke events
    textInput.onkeyup = function (e) {
        const query = textInput.value;
        const url = action;
        if(query.length < 4){ 
            output.innerHTML = "";
            spanCountResult.textContent = "...";
            return false;
        }
            axios.get(url, {
                params: {
                    query: query,
                    limit: 20
                }
            })
            .then(response => {
                const items = response.data;
                var html = "";
                spanCountResult.textContent = response.data.length + ' résultat(s)'; 
                items.forEach(function (val) {
                    var tags = val['tags'];
                    html += "<article class=post>";
                    // html += "<div class='alert alert-dismissible alert-light'>";
                    // html += "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
                    html += "<h2><a href= " + val['url'] + "> " + val['title'] + "</a></h2>";

                    html += "<p class='post-metadata'>";
                    html += "<span class='metadata'><i class='fa fa-calendar'></i>";
                    html += val['publishedAt'];
                    html += "</span>";
                    html += "<span class='metadata'><i class='fa fa-user'></i>";
                    html += val['author'];
                    html += "</span>";
                    html += "</p>";
                    html += "<p class='post-tags'>";
                    tags.forEach(tag => {
                        var linkByTag = val['url_post'] + "?tag=" + tag;
                        html += "<a href='" + linkByTag + "' class='label label-default'>";
                        html += "<i class='fa fa-tag'></i>" + tag;
                        html += "</a>";
                    });
                    html += '</p>';
                    html += "</div>";

                    // html += "</div>";
                    html += "</article>";

                });

                //output.className = 'contai';
                output.innerHTML = html;

            })
            .catch(function (error) {
                // handle error
                if (error.response.status === 403) {
                    window.alert("No Result ");
                } else {
                    window.alert("Une error s'est produite, re-essayer plustard ");
                }
                console.log("Error: ", error);
            })
            .then(function () {
                // always executed
            });


    };
});