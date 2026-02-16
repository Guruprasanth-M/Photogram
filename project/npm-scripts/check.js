/*
 * Syntax Checker - Check PHP and JS files for syntax errors
 *
 * npm run check       check all php and js
 * npm run check:php   check php only
 * npm run check:js    check js only
 */

var fs    = require('fs');
var path  = require('path');
var glob  = require('glob');
var exec  = require('child_process').execSync;

var ROOT   = path.resolve(__dirname, '..', '..');
var HTDOCS = path.join(ROOT, 'htdocs');

var errors = [];
var total  = 0;


function checkPHP() {
    console.log('checking php syntax...');

    var pattern = path.join(HTDOCS, '**', '*.php');
    var files = glob.globSync(pattern, { nodir: true });

    if (!files.length) {
        console.log('  no php files found');
        return;
    }

    files.forEach(function (file) {
        total++;
        try {
            exec('php -l "' + file + '" 2>&1', { stdio: 'pipe' });
            console.log('  ok ' + path.relative(ROOT, file));
        } catch (e) {
            var output = e.stdout || e.message;
            console.error('  error ' + path.relative(ROOT, file));
            console.error('    ' + output.toString().trim());
            errors.push(file);
        }
    });

    console.log('  checked ' + files.length + ' php files\n');
}


function checkJS() {
    console.log('checking js syntax...');

    var pattern = path.join(ROOT, 'project', 'js', '**', '*.js');
    var files = glob.globSync(pattern, { nodir: true });

    if (!files.length) {
        console.log('  no js files found');
        return;
    }

    files.forEach(function (file) {
        total++;
        try {
            exec('node --check "' + file + '" 2>&1', { stdio: 'pipe' });
            console.log('  ok ' + path.relative(ROOT, file));
        } catch (e) {
            var output = e.stdout || e.message;
            console.error('  error ' + path.relative(ROOT, file));
            console.error('    ' + output.toString().trim());
            errors.push(file);
        }
    });

    console.log('  checked ' + files.length + ' js files\n');
}


function main() {
    var args = process.argv.slice(2);

    console.log('Photogram Syntax Check');
    console.log('----------------------\n');

    var checkAllPHP = !args.length || args.includes('--php');
    var checkAllJS = !args.length || args.includes('--js');

    if (checkAllPHP) checkPHP();
    if (checkAllJS) checkJS();

    console.log('checked ' + total + ' files');

    if (errors.length) {
        console.log('errors found: ' + errors.length);
        errors.forEach(function (f) {
            console.log('  ' + path.relative(ROOT, f));
        });
        process.exit(1);
    } else {
        console.log('all good');
        process.exit(0);
    }
}

main();
