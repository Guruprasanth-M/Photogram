/*
 * Photogram Build Script
 *
 * Concat, minify CSS & JS, obfuscate JS, watch for changes.
 *
 * Source CSS  -> project/css/
 * Source JS   -> project/js/
 * Output CSS  -> htdocs/css/style.min.css
 * Output JS   -> htdocs/js/app.min.js, app.o.js
 *
 * npm run build       full build
 * npm run build:css   css only
 * npm run build:js    js only
 * npm run watch       watch mode
 * npm run dev         build + watch
 * npm run clean       wipe dist/
 */

var fs   = require('fs');
var path = require('path');
var glob = require('glob');

var ROOT   = path.resolve(__dirname, '..', '..');
var HTDOCS = path.join(ROOT, 'htdocs');

var PATHS = {
    cssSrc:   path.join(ROOT, 'project', 'css', '**', '*.css'),
    jsSrc:    path.join(ROOT, 'project', 'js', '**', '*.js'),
    dist:     path.join(__dirname, 'dist'),
    cssOut:   path.join(HTDOCS, 'css', 'style.min.css'),
    jsOut:    path.join(HTDOCS, 'js', 'app.min.js'),
    jsMap:    path.join(HTDOCS, 'js', 'app.min.js.map'),
    jsObf:    path.join(HTDOCS, 'js', 'app.o.js'),
};


function banner() {
    var d  = new Date();
    var ts = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear()
           + ' ' + d.getHours() + ':' + String(d.getMinutes()).padStart(2, '0');
    return '/*! Photogram build ' + ts + ' */\n';
}

function mkdirFor(file) {
    var dir = path.dirname(file);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
}

function concat(pattern, dest) {
    var files = glob.globSync(pattern, { nodir: true });
    if (!files.length) {
        console.log('  [skip] no files matching ' + pattern);
        return null;
    }

    var out = banner();
    files.forEach(function (f) {
        console.log('  + ' + path.relative(ROOT, f));
        out += fs.readFileSync(f, 'utf8') + '\n';
    });

    mkdirFor(dest);
    fs.writeFileSync(dest, out, 'utf8');
    console.log('  -> ' + path.relative(ROOT, dest) + ' (' + (out.length / 1024).toFixed(1) + ' KB)');
    return out;
}


// concat tasks

function concatCSS() {
    console.log('\nconcat css');
    return concat(PATHS.cssSrc, path.join(PATHS.dist, 'style.css'));
}

function concatJS() {
    console.log('\nconcat js');
    return concat(PATHS.jsSrc, path.join(PATHS.dist, 'app.js'));
}


// minify css

async function minifyCSS() {
    var CleanCSS = require('clean-css');
    var src = path.join(PATHS.dist, 'style.css');

    if (!fs.existsSync(src)) {
        console.log('  [skip] dist/style.css missing');
        return;
    }

    console.log('\nminify css');
    var source = fs.readFileSync(src, 'utf8');
    var result = new CleanCSS({ level: 2 }).minify(source);

    if (result.errors.length) {
        console.error('  css errors:', result.errors);
        return;
    }

    mkdirFor(PATHS.cssOut);
    fs.writeFileSync(PATHS.cssOut, banner() + result.styles, 'utf8');
    var saved = ((1 - result.styles.length / source.length) * 100).toFixed(1);
    console.log('  -> ' + path.relative(ROOT, PATHS.cssOut) + ' (' + saved + '% smaller)');
}


// minify js

async function minifyJS() {
    var terser = require('terser');
    var src = path.join(PATHS.dist, 'app.js');

    if (!fs.existsSync(src)) {
        console.log('  [skip] dist/app.js missing');
        return;
    }

    console.log('\nminify js');
    var source = fs.readFileSync(src, 'utf8');
    var result = await terser.minify(source, {
        compress: { drop_console: false, passes: 2 },
        mangle: {
            reserved: ['$', 'jQuery', 'Masonry', 'imagesLoaded', 'FingerprintJS', 'bootstrap']
        },
        sourceMap: { filename: 'app.min.js', url: 'app.min.js.map' },
        output: { preamble: banner() }
    });

    if (result.error) {
        console.error('  js error:', result.error);
        return;
    }

    mkdirFor(PATHS.jsOut);
    fs.writeFileSync(PATHS.jsOut, result.code, 'utf8');
    if (result.map) fs.writeFileSync(PATHS.jsMap, result.map, 'utf8');

    var saved = ((1 - result.code.length / source.length) * 100).toFixed(1);
    console.log('  -> ' + path.relative(ROOT, PATHS.jsOut) + ' (' + saved + '% smaller)');
}


// obfuscate js

function obfuscateJS() {
    var Obfuscator = require('javascript-obfuscator');
    var src = path.join(PATHS.dist, 'app.js');

    if (!fs.existsSync(src)) {
        console.log('  [skip] dist/app.js missing');
        return;
    }

    console.log('\nobfuscate js');
    var source = fs.readFileSync(src, 'utf8');
    var result = Obfuscator.obfuscate(source, {
        compact: true,
        controlFlowFlattening: true,
        controlFlowFlatteningThreshold: 0.5,
        deadCodeInjection: true,
        deadCodeInjectionThreshold: 0.3,
        debugProtection: false,
        disableConsoleOutput: false,
        identifierNamesGenerator: 'hexadecimal',
        log: false,
        numbersToExpressions: true,
        renameGlobals: false,
        selfDefending: false,
        simplify: true,
        splitStrings: true,
        splitStringsChunkLength: 10,
        stringArray: true,
        stringArrayCallsTransform: true,
        stringArrayEncoding: ['base64'],
        stringArrayIndexShift: true,
        stringArrayRotate: true,
        stringArrayShuffle: true,
        stringArrayWrappersCount: 2,
        stringArrayWrappersChainedCalls: true,
        stringArrayWrappersParametersMaxCount: 4,
        stringArrayWrappersType: 'function',
        stringArrayThreshold: 0.75,
        transformObjectKeys: true,
        unicodeEscapeSequence: false
    });

    mkdirFor(PATHS.jsObf);
    fs.writeFileSync(PATHS.jsObf, result.getObfuscatedCode(), 'utf8');
    console.log('  -> ' + path.relative(ROOT, PATHS.jsObf));
}


// watch

function watch() {
    var chokidar = require('chokidar');
    console.log('\nwatching for changes...');

    chokidar.watch(path.join(ROOT, 'project', 'css', '**', '*.css'), {
        ignoreInitial: true,
        awaitWriteFinish: { stabilityThreshold: 300 }
    }).on('all', async function (ev, f) {
        console.log('\ncss changed: ' + path.relative(ROOT, f) + ' [' + ev + ']');
        concatCSS();
        await minifyCSS();
        console.log('css done.');
    });

    chokidar.watch(path.join(ROOT, 'project', 'js', '**', '*.js'), {
        ignoreInitial: true,
        awaitWriteFinish: { stabilityThreshold: 300 }
    }).on('all', async function (ev, f) {
        console.log('\njs changed: ' + path.relative(ROOT, f) + ' [' + ev + ']');
        concatJS();
        await minifyJS();
        obfuscateJS();
        console.log('js done.');
    });

    console.log('  css: project/css/**/*.css');
    console.log('  js:  project/js/**/*.js');
    console.log('\nctrl+c to stop.\n');
}


// clean

function clean() {
    console.log('\ncleaning dist...');
    if (fs.existsSync(PATHS.dist)) {
        fs.rmSync(PATHS.dist, { recursive: true, force: true });
        console.log('  dist/ removed');
    } else {
        console.log('  nothing to clean');
    }
}


// main

async function main() {
    var args  = process.argv.slice(2);
    var start = Date.now();

    console.log('Photogram Build');
    console.log('----------------');

    if (args.includes('--clean')) {
        clean();
        return;
    }

    var all = args.includes('--all');

    if (all || args.includes('--concat-css'))  concatCSS();
    if (all || args.includes('--concat-js'))   concatJS();
    if (all || args.includes('--minify-css'))  await minifyCSS();
    if (all || args.includes('--minify-js'))   await minifyJS();
    if (all || args.includes('--obfuscate'))   obfuscateJS();

    var elapsed = ((Date.now() - start) / 1000).toFixed(2);
    console.log('\ndone in ' + elapsed + 's');

    if (args.includes('--watch')) watch();
}

main().catch(function (err) {
    console.error('build failed:', err);
    process.exit(1);
});
