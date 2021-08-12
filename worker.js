// importScripts();                         /* imports nothing */
// importScripts('foo.js');                  imports just "foo.js" 
// importScripts('foo.js', 'bar.js');       /* imports two scripts */
// importScripts('//example.com/hello.js');

onmessage = function(e) {
  console.log('Message received from main script');
  // var workerResult = 'Result: ' + (e.data[0] * e.data[1]);
  console.log('Posting message back to main script');
  postMessage('Hellow');
}