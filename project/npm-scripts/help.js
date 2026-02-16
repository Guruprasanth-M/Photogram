/*
 * Help - Show all available npm tasks
 *
 * npm run help
 */

var tasks = [
    ['build',      'full build - concat, minify css/js, obfuscate js'],
    ['build:css',  'build css only'],
    ['build:js',   'build js only (minify + obfuscate)'],
    ['dev',        'build + watch (auto-rebuild on file change)'],
    ['watch',      'watch only, no initial build'],
    ['clean',      'delete dist/ folder'],
    ['check',      'syntax check all php and js files'],
    ['check:php',  'syntax check php files only'],
    ['check:js',   'syntax check js files only'],
    ['create:css', 'create new css file (npm run create:css -- name)'],
    ['create:js',  'create new js file (npm run create:js -- name)'],
    ['serve',      'start php dev server on localhost:8000'],
    ['stats',      'show project file counts, sizes, build comparison'],
    ['help',       'show this list'],
];

console.log('');
console.log('Photogram Build Tasks');
console.log('---------------------');
console.log('');

var maxLen = 0;
tasks.forEach(function (t) {
    if (t[0].length > maxLen) maxLen = t[0].length;
});

tasks.forEach(function (t) {
    var pad = '';
    for (var i = 0; i < maxLen - t[0].length; i++) pad += ' ';
    console.log('  npm run ' + t[0] + pad + '   ' + t[1]);
});

console.log('');
console.log('source:  project/css/  project/js/');
console.log('output:  htdocs/css/   htdocs/js/');
console.log('');
