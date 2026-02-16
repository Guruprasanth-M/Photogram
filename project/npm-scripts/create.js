/*
 * File Generator - Create new CSS and JS files with templates
 *
 * npm run create:css -- filename     create new css file
 * npm run create:js -- filename      create new js file
 * npm run create                     interactive mode
 */

var fs   = require('fs');
var path = require('path');

var ROOT = path.resolve(__dirname, '..', '..');


function getTimestamp() {
    var d = new Date();
    return d.getFullYear() + '-' +
           String(d.getMonth() + 1).padStart(2, '0') + '-' +
           String(d.getDate()).padStart(2, '0');
}


function getCSSTemplate(name) {
    return '/*\n' +
           ' * ' + name + '.css\n' +
           ' * Created: ' + getTimestamp() + '\n' +
           ' */\n\n' +
           '/* styles for ' + name + ' */\n\n' +
           '.' + name + ' {\n' +
           '    \n' +
           '}\n';
}


function getJSTemplate(name) {
    return '/*\n' +
           ' * ' + name + '.js\n' +
           ' * Created: ' + getTimestamp() + '\n' +
           ' */\n\n' +
           '(function () {\n' +
           '    var ' + name + ' = {\n' +
           '        init: function () {\n' +
           '            console.log(\'' + name + ' initialized\');\n' +
           '        }\n' +
           '    };\n\n' +
           '    if (typeof module !== \'undefined\' && module.exports) {\n' +
           '        module.exports = ' + name + ';\n' +
           '    }\n' +
           '}.call(this));\n';
}


function createCSS(filename) {
    if (!filename) {
        console.error('please provide a filename');
        console.error('  npm run create:css -- myfile');
        process.exit(1);
    }

    // clean filename
    filename = filename.replace(/\.css$/, '');
    var filepath = path.join(ROOT, 'project', 'css', filename + '.css');

    if (fs.existsSync(filepath)) {
        console.error('file already exists: ' + path.relative(ROOT, filepath));
        process.exit(1);
    }

    var dir = path.dirname(filepath);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }

    var template = getCSSTemplate(filename);
    fs.writeFileSync(filepath, template, 'utf8');

    console.log('created css file');
    console.log('  ' + path.relative(ROOT, filepath));
    console.log('\nwatch mode is running - file will auto-build');
}


function createJS(filename) {
    if (!filename) {
        console.error('please provide a filename');
        console.error('  npm run create:js -- myfile');
        process.exit(1);
    }

    // clean filename
    filename = filename.replace(/\.js$/, '');
    var filepath = path.join(ROOT, 'project', 'js', filename + '.js');

    if (fs.existsSync(filepath)) {
        console.error('file already exists: ' + path.relative(ROOT, filepath));
        process.exit(1);
    }

    var dir = path.dirname(filepath);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }

    var template = getJSTemplate(filename);
    fs.writeFileSync(filepath, template, 'utf8');

    console.log('created js file');
    console.log('  ' + path.relative(ROOT, filepath));
    console.log('\nwatch mode is running - file will auto-build');
}


function main() {
    var args = process.argv.slice(2);

    // find the filename (first non-flag argument)
    var filename = null;
    for (var i = 0; i < args.length; i++) {
        if (args[i] !== '--css' && args[i] !== '--js' && !args[i].startsWith('-')) {
            filename = args[i];
            break;
        }
    }

    if (args.includes('--css')) {
        createCSS(filename);
    } else if (args.includes('--js')) {
        createJS(filename);
    } else {
        // interactive mode
        console.log('Photogram File Generator');
        console.log('------------------------\n');
        console.log('usage:');
        console.log('  npm run create:css -- filename    create new css');
        console.log('  npm run create:js -- filename     create new js\n');
        console.log('examples:');
        console.log('  npm run create:css -- forms');
        console.log('  npm run create:js -- utils');
    }
}

main();
