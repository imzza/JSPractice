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
		<div class="box">
			<div class="loader"></div>
		</div>
		<div class="box">
			<div class="loader"></div>
		</div>
		<div class="box">
			<div class="loader"></div>
		</div>
		<div class="box">
			<div class="loader"></div>
		</div>
		<div class="box">
			<div class="loader"></div>
		</div>
	</div>

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
</html>