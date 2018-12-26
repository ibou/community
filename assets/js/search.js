
$(function () {
    const axios = require('axios');
    // Get the input box
    var textInput = document.getElementById('search-field');
    var form = document.getElementById('form-search');
    const spanCountResult = this.querySelector('span.js-count-result');

    const action = form.action;
    var output = document.getElementById('output');
    var outputTags = document.getElementById('outputTags');

    function get_query(url) {
        var qs = url.substring(url.indexOf('?') + 1).split('&');
        for (var i = 0, result = {}; i < qs.length; i++) {
            qs[i] = qs[i].split('=');
            result[qs[i][0]] = decodeURIComponent(qs[i][1]);
        }
        result['url'] = url.substring(0, url.indexOf('?'));
        return result;
    }

    let onClickBtnLike = (params) => {
        var paramsArray = get_query(params.href)
        url = paramsArray.url;
        query = paramsArray.query;
        tags = paramsArray.tags; 

        doSearch(query, url, tags);
    }
    // Listen for keystroke events
    textInput.onkeyup = function (e) {
        const query = textInput.value;
        const url = action;
        if (query.length < 4) {
            output.innerHTML = "";
            outputTags.innerHTML = "";
            spanCountResult.textContent = "...";
            return false;
        }
        doSearch(query, url);
    };


    let doSearch = (query, url, tags = null) => {
        axios.get(url, {
            params: {
                query: query,
                limit: 90,
                tags: tags
            }
        })
            .then(response => {
                const items = response.data['source'];
                const aggrTags = response.data['aggr'];
                var html = "";
                var htmlTags = "";
                spanCountResult.textContent = items.length + ' résultat(s)';

                items.forEach(function (val) {
                    var tags = val['tags'];
                    html += "<article class=post>";
                    html += "<div class='alert alert-dismissible alert-gris'>";
                    html += "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
                    html += "<h2><a href= " + val['url'] + "> " + val['title'] + "</a></h2>";

                    html += "<p class='post-metadata'>";
                    html += "<span class='metadata'><i class='fa fa-calendar'></i>";
                    html += val['publishedAt'];
                    html += "</span>";
                    html += "<span class='metadata'><i class='fa fa-user'></i>";
                    html += val['author'];
                    html += "</span>";
                    html += "</p>";
                    html += "<div class='text-black'>";
                    html += val['content'].substr(1, 130) + " ...";
                    html += "<a href= " + val['url'] + "> (voir plus)</a>";
                    html += "</div>";
                    html += "<span class='metadata'><i class='fa fa-user'></i>";
                    html += "<p class='post-tags'>";
                    tags.forEach(tag => {
                        var linkByTag = val['url_post'] + "?tag=" + tag;
                        html += "<a href='" + linkByTag + "' class='label label-default search-tag-filter'>";
                        html += "<i class='fa fa-tag'></i>" + tag;
                        html += "</a>";
                    });
                    html += '</p>';
                    html += "</div>";

                    html += "</div>";
                    html += "</article>";

                });

                aggrTags.forEach(function (value) {
                    var filterByTags = response.request.responseURL + "&tags=" + value.key;
                    htmlTags += "<a href= " + filterByTags + " class='label label-default search-filter'>";
                    htmlTags += "<i class='fa fa-tag'></i><span class='tag-filter'>";
                    htmlTags += value.key + "(" + value.doc_count + ")";
                    htmlTags += "</span></a>";
                });

                //output.className = 'contai';
                output.innerHTML = html;
                var formStartTag = '<form action="" method="get" id="form-search-tag">';
                var formEndTag = '</form>';
                outputTags.innerHTML = formStartTag + htmlTags + formEndTag;
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

                document.querySelectorAll('a.search-filter').forEach(function (link) {

                    $("a.search-filter").click(function (event) {
                        event.preventDefault();
                        onClickBtnLike(this);
                    });
                });
                // always executed
            });
    }
});