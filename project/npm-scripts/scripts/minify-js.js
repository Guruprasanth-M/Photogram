/**
 * Minify JS - Minify concatenated JS using Terser
 */

var fs     = require('fs');
var path   = require('path');
var terser = require('terser');
var config = require('./config');

async function minifyJS() {
    console.log('minifying js...');

    var inputPath = path.join(config.dist, 'app.js');

    if (!fs.existsSync(inputPath)) {
        console.error('dist/app.js not found. run concat:js first.');
        process.exit(1);
    }

    var source = fs.readFileSync(inputPath, 'utf-8');

    var result = await terser.minify(source, {
        compress: {
            drop_console: false,
            passes: 2,
        },
        mangle: {
            reserved: ['$', 'jQuery', 'Masonry', 'imagesLoaded'],
        },
        output: {
            comments: /^!/,
            preamble: config.getBanner(),
        },
        sourceMap: {
            filename: 'app.min.js',
            url: 'app.min.js.map',
        },
    });

    if (result.code === undefined) {
        console.error('terser produced no output.');
        process.exit(1);
    }

    // write to dist
    var distOutput = path.join(config.dist, 'app.min.js');
    fs.writeFileSync(distOutput, result.code, 'utf-8');

    // write source map
    if (result.map) {
        fs.writeFileSync(distOutput + '.map', result.map, 'utf-8');
    }

    // write to htdocs for production
    var prodOutput = path.join(config.output.js, 'app.min.js');
    fs.mkdirSync(path.dirname(prodOutput), { recursive: true });
    fs.writeFileSync(prodOutput, result.code, 'utf-8');

    var originalSize = Buffer.byteLength(source, 'utf-8');
    var minifiedSize = Buffer.byteLength(result.code, 'utf-8');
    var savings = ((1 - minifiedSize / originalSize) * 100).toFixed(1);

    console.log('js minified -> app.min.js (' + savings + '% smaller)');
    console.log('  original: ' + (originalSize / 1024).toFixed(1) + 'KB -> minified: ' + (minifiedSize / 1024).toFixed(1) + 'KB');
}

minifyJS().catch(function (err) {
    console.error('js minification failed:', err);
    process.exit(1);
});
