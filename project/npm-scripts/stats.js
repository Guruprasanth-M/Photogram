/*
 * Project Stats - Show file counts, sizes, and build comparison
 *
 * npm run stats
 */

var fs   = require('fs');
var path = require('path');
var glob = require('glob');

var ROOT   = path.resolve(__dirname, '..', '..');
var HTDOCS = path.join(ROOT, 'htdocs');


function fileSize(filepath) {
    if (!fs.existsSync(filepath)) return 0;
    return fs.statSync(filepath).size;
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    if (bytes < 1024) return bytes + ' B';
    return (bytes / 1024).toFixed(1) + ' KB';
}

function lineCount(filepath) {
    if (!fs.existsSync(filepath)) return 0;
    return fs.readFileSync(filepath, 'utf8').split('\n').length;
}

function countFiles(pattern) {
    return glob.globSync(pattern, { nodir: true }).length;
}

function totalSize(pattern) {
    var files = glob.globSync(pattern, { nodir: true });
    var total = 0;
    files.forEach(function (f) { total += fileSize(f); });
    return total;
}

function totalLines(pattern) {
    var files = glob.globSync(pattern, { nodir: true });
    var total = 0;
    files.forEach(function (f) { total += lineCount(f); });
    return total;
}


function main() {
    console.log('Photogram Project Stats');
    console.log('-----------------------\n');

    // source files
    var cssPattern = path.join(ROOT, 'project', 'css', '**', '*.css');
    var jsPattern  = path.join(ROOT, 'project', 'js', '**', '*.js');
    var phpPattern = path.join(HTDOCS, '**', '*.php');

    var cssFiles = countFiles(cssPattern);
    var jsFiles  = countFiles(jsPattern);
    var phpFiles = countFiles(phpPattern);

    console.log('source files');
    console.log('  css: ' + cssFiles + ' files, ' + totalLines(cssPattern) + ' lines, ' + formatBytes(totalSize(cssPattern)));
    console.log('  js:  ' + jsFiles + ' files, ' + totalLines(jsPattern) + ' lines, ' + formatBytes(totalSize(jsPattern)));
    console.log('  php: ' + phpFiles + ' files, ' + totalLines(phpPattern) + ' lines, ' + formatBytes(totalSize(phpPattern)));
    console.log('  total: ' + (cssFiles + jsFiles + phpFiles) + ' files');

    // build output
    var cssOut  = path.join(HTDOCS, 'css', 'style.min.css');
    var jsOut   = path.join(HTDOCS, 'js', 'app.min.js');
    var jsObf   = path.join(HTDOCS, 'js', 'app.o.js');

    console.log('\nbuild output');

    if (fs.existsSync(cssOut)) {
        var cssSrc = totalSize(cssPattern);
        var cssDst = fileSize(cssOut);
        var cssSaved = cssSrc > 0 ? ((1 - cssDst / cssSrc) * 100).toFixed(1) : 0;
        console.log('  style.min.css: ' + formatBytes(cssDst) + ' (saved ' + cssSaved + '%)');
    } else {
        console.log('  style.min.css: not built yet');
    }

    if (fs.existsSync(jsOut)) {
        var jsSrc = totalSize(jsPattern);
        var jsDst = fileSize(jsOut);
        var jsSaved = jsSrc > 0 ? ((1 - jsDst / jsSrc) * 100).toFixed(1) : 0;
        console.log('  app.min.js:    ' + formatBytes(jsDst) + ' (saved ' + jsSaved + '%)');
    } else {
        console.log('  app.min.js:    not built yet');
    }

    if (fs.existsSync(jsObf)) {
        console.log('  app.o.js:      ' + formatBytes(fileSize(jsObf)));
    } else {
        console.log('  app.o.js:      not built yet');
    }

    // htdocs total
    var htdocsPattern = path.join(HTDOCS, '**', '*');
    var htdocsFiles = countFiles(htdocsPattern);
    var htdocsSize  = totalSize(htdocsPattern);
    console.log('\nhtdocs total: ' + htdocsFiles + ' files, ' + formatBytes(htdocsSize));

    // deps
    var nodeModules = path.join(__dirname, 'node_modules');
    if (fs.existsSync(nodeModules)) {
        var pkgCount = 0;
        try {
            var installed = require(path.join(__dirname, 'node_modules', '.package-lock.json'));
            pkgCount = Object.keys(installed.packages || {}).length;
        } catch (e) {
            pkgCount = fs.readdirSync(nodeModules).filter(function (d) {
                return !d.startsWith('.');
            }).length;
        }
        console.log('node_modules: ' + pkgCount + ' packages');
    }

    console.log('');
}

main();
