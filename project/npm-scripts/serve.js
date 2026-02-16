/*
 * PHP Dev Server
 *
 * Starts PHP's built-in web server pointing at htdocs/
 * so you can test without Apache or Nginx.
 *
 * npm run serve
 */

var path  = require('path');
var spawn = require('child_process').spawn;

var ROOT   = path.resolve(__dirname, '..', '..');
var HTDOCS = path.join(ROOT, 'htdocs');
var PORT   = process.argv[2] || 8000;
var HOST   = '0.0.0.0';

console.log('starting php dev server...');
console.log('  root: htdocs/');
console.log('  url:  http://localhost:' + PORT);
console.log('  ctrl+c to stop\n');

var server = spawn('php', ['-S', HOST + ':' + PORT, '-t', HTDOCS], {
    stdio: 'inherit',
    cwd: HTDOCS
});

server.on('error', function (err) {
    if (err.code === 'ENOENT') {
        console.error('php not found - make sure php is installed and in your PATH');
    } else {
        console.error('server error:', err.message);
    }
    process.exit(1);
});

server.on('close', function (code) {
    console.log('\nserver stopped');
    process.exit(code);
});
