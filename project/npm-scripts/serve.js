/*
 * PHP Dev Server
 *
 * Starts PHP's built-in server pointing at htdocs/.
 * Temporarily sets base_path to "/" so CSS/JS loads correctly.
 *
 * npm run serve
 * npm run serve -- 9000    (custom port)
 */

var fs    = require('fs');
var path  = require('path');
var spawn = require('child_process').spawn;

var ROOT       = path.resolve(__dirname, '..', '..');
var HTDOCS     = path.join(ROOT, 'htdocs');
var CONFIG     = path.join(ROOT, 'project', 'photogramconfig.json');
var PORT       = process.argv[2] || 8000;
var HOST       = '0.0.0.0';

// read original config and swap base_path for dev server
var original = fs.readFileSync(CONFIG, 'utf8');
var cfg      = JSON.parse(original);
var prodPath = cfg.base_path;
cfg.base_path = '/';

// write temp config
fs.writeFileSync(CONFIG, JSON.stringify(cfg, null, 4), 'utf8');

console.log('starting php dev server...');
console.log('  root:      htdocs/');
console.log('  url:       http://localhost:' + PORT);
console.log('  base_path: / (was ' + prodPath + ')');
console.log('  ctrl+c to stop\n');

var server = spawn('php', ['-S', HOST + ':' + PORT, '-t', HTDOCS], {
    stdio: 'inherit',
    cwd: HTDOCS
});

// restore original config on exit
function restore() {
    try {
        cfg.base_path = prodPath;
        fs.writeFileSync(CONFIG, JSON.stringify(cfg, null, 4), 'utf8');
        console.log('\nbase_path restored to ' + prodPath);
    } catch (e) {}
}

process.on('SIGINT', function () {
    restore();
    process.exit(0);
});

process.on('SIGTERM', function () {
    restore();
    process.exit(0);
});

server.on('error', function (err) {
    restore();
    if (err.code === 'ENOENT') {
        console.error('php not found - make sure php is installed and in your PATH');
    } else {
        console.error('server error:', err.message);
    }
    process.exit(1);
});

server.on('close', function (code) {
    restore();
    console.log('server stopped');
    process.exit(code);
});
