function fetchRandomContent() {
    //-- We are using FETCH API to get the contents --//
    fetch('https://en.wikipedia.org/w/api.php?format=json&origin=*&action=query&generator=random&rvprop=content&prop=revisions', {
        method: 'GET',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: "same-origin"
    }).then(function(promise) {
        promise.json().then(function(response) {
            //--- Parsing Wikipedia data ---//
            var data = {},
                keys = Object.keys(response.query.pages),
                key = keys[0];

            data.title = response.query.pages[key].title
            data.content = response.query.pages[key].revisions[0]["*"]
            //--- Parsing Wikipedia data ---//

            //--- Sending message to our application ---//
            self.postMessage(data);
        });
    }, function(error) {
        console.log('shit!', error);
    });
}

// setInterval(function() {
//     fetchRandomContent();
// }, 100);

let count = 0;
while(count < 1000){
    fetchRandomContent();
    count = count +1;
}