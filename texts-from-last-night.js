var http = require('http');

var texts = [];
function getText(callback) {
    if(texts.length === 0) {
        $.ajax({
            url: 'http://www.kimonolabs.com/api/640xkp34',
            data: {
                apikey: '38c30756f7f07e7e3265cf3a1239e3bc',
                bust:   new Date().getTime(),
            },
            dataType: 'json',
            success: function(data) {
                var newTexts = data.results.collection1;

                for(var i=0; i<newTexts.length; i++) {
                    texts.push(newTexts[i].text.text);
                }

                callback(texts.pop());
            }
        });
    }
    else {
        callback(texts.pop());
    }
}


// Configure our HTTP server to respond with Hello World to all requests.
var server = http.createServer(function (request, response) {
    response.writeHead(200, {
        "Content-Type": "text/plain"
    });

    getText(function(text) {
        response.end(text);
    });
});

// Listen on port 8000, IP defaults to 127.0.0.1
server.listen(6000);

// Put a friendly message on the terminal
console.log("Server running at http://127.0.0.1:6000/");

