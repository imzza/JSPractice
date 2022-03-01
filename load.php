<?php 
// https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
// https://stackoverflow.com/questions/60289589/intersectionobserver-and-ajax-loaded-content
// https://www.smashingmagazine.com/2018/01/deferring-lazy-loading-intersection-observer-api/
// Intersection Observer in React JS
// https://github.com/mayankshubham/react-lazy-loading-image

$string = file_get_contents("MOCK_DATA.json");
$users = json_decode($string);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>JavaScript Modran API's</title>
</head>
<style>
	body {
		margin: 0px;
		padding: 0px;
		min-width: 100%;
		min-height: 100vh;
		background-color: #eee;
	}
	.boxes {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: center;

		/*min-height: 100vh;*/
	}
	.box {
		/*border: 1px solid #000;*/
		display: flex;
		align-items: center;
		justify-content: center;
		flex-grow: 0;
		flex-shrink: 0;
		flex-basis: 250px;
		height: 100px;
		margin: 5px;
		border-radius: 5px;
		background-color: #fff;
		box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
	}

	.loader {
		  border: 5px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 5px solid #000;
		  width: 20px;
		  height: 20px;
		  animation: spin 1.5s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}


</style>

<body>
	<div class="worker">
		<h1>Checking the Worker API.</h1>
		<button id="postMessage">Post</button>
	</div>
	<div class="boxes">
		<?php if (count($users) > 0): ?>
			<?php foreach ($users as $u): ?>
				<div class="box loading" id="<?php echo $u->id; ?>" data-id="<?php echo $u->id; ?>">
					<div class="loader"></div>
				</div>
			<?php endforeach ?>
		<?php endif ?>
	</div>


<script>
let images = document.querySelectorAll('body .loading');
if ('IntersectionObserver' in window) {
    // IntersectionObserver Supported
    let config = {
        root: null,
        rootMargin: '0px 0px', //Y xes px below and X axis ratio  100px 0px
        threshold: 0.01
    };

    let observer = new IntersectionObserver(onChange, config);
    images.forEach(img => observer.observe(img));

    function onChange(changes, observer) {
        changes.forEach(change => {
            if (change.intersectionRatio > 0) {
                // Stop watching and load the image
                loadImage(change.target);
                observer.unobserve(change.target);
            }
        });
    }
} else {
    images.forEach(image => loadImage(image));
}

function loadImage(image) {
    image.classList.remove('loading');
    image.classList.add('loaded');
    image.innerHTML = '<p>Loaded</p>';

    console.log('Loading');


    //    if (image.dataset && image.dataset.src) {
    //        image.src = image.dataset.src;
    //    }
    //
    //    if (image.dataset && image.dataset.srcset) {
    //        image.srcset = image.dataset.srcset;
    //    }
}
</script>


<script type="text/js-worker">
  onmessage = function(oEvent) {
  console.log(oEvent);
    if(oEvent.data.action == 'ajax'){
      	postMessage(oEvent.data);
    }
  };
</script>

<script type="text/javascript">
  var blob = new Blob(Array.prototype.map.call(document.querySelectorAll('script[type=\'text\/js-worker\']'), function (oScript) { return oScript.textContent; }),{type: 'text/javascript'});

  // Creating a new document.worker property containing all our "text/js-worker" scripts.
  document.worker = new Worker(window.URL.createObjectURL(blob));

  document.worker.onmessage = function(oEvent) {
    console.log('Received: ', oEvent.data);
  };

document.querySelector('#postMessage').addEventListener('click', () => {
	document.worker.postMessage({action: 'ajax', data: {key: 1, user: 'John'}});
	console.log('Message Posted to Worker');
},false);

  // Start the worker.
  window.onload = function() { document.worker.postMessage({action: 'ajax', data: {key: 1, user: 'John'}}); };
</script>
</body>