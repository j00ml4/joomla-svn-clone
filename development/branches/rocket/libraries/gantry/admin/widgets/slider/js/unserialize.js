// http://kevin.vanzonneveld.net
// +     original by: Arpad Ray (mailto:arpad@php.net)
// +     improved by: Pedro Tainha (http://www.pedrotainha.com)
// +     bugfixed by: dptr1988
// +      revised by: d3x
// +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +        input by: Brett Zamir (http://brett-zamir.me)
// +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: Chris
// +     improved by: James
// +        input by: Martin (http://www.erlenwiese.de/)
// +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: Le Torbi
// +     input by: kilops
// +     bugfixed by: Brett Zamir (http://brett-zamir.me)
// -      depends on: utf8_decode
// %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
// %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
// *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
// *       returns 1: ['Kevin', 'van', 'Zonneveld']
// *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
// *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}

String.implement({
	unserialize: function() {
		
		var data = this;
		
	    var that = this;
	    var utf8Overhead = function(chr) {
	        // http://phpjs.org/functions/unserialize:571#comment_95906
	        var code = chr.charCodeAt(0);
	        if (code < 0x0080) {
	            return 0;
	        }
	        if (code < 0x0800) {
	             return 1;
	        }
	        return 2;
	    };


	    var error = function (type, msg, filename, line){ return; };
	    var read_until = function (data, offset, stopchr){
	        var buf = [];
	        var chr = data.slice(offset, offset + 1);
	        var i = 2;
	        while (chr != stopchr) {
	            if ((i+offset) > data.length) {
	                error('Error', 'Invalid');
	            }
	            buf.push(chr);
	            chr = data.slice(offset + (i - 1),offset + i);
	            i += 1;
	        }
	        return [buf.length, buf.join('')];
	    };
	    var read_chrs = function (data, offset, length){
	        var buf;

	        buf = [];
	        for (var i = 0;i < length;i++){
	            var chr = data.slice(offset + (i - 1),offset + i);
	            buf.push(chr);
	            length -= utf8Overhead(chr); 
	        }
	        return [buf.length, buf.join('')];
	    };
	    var _unserialize = function (data, offset){
	        var readdata;
	        var readData;
	        var chrs = 0;
	        var ccount;
	        var stringlength;
	        var keyandchrs;
	        var keys;

	        if (!offset) {offset = 0;}
	        var dtype = (data.slice(offset, offset + 1)).toLowerCase();

	        var dataoffset = offset + 2;
	        var typeconvert = function(x) {return x;};

	        switch (dtype){
	            case 'i':
	                typeconvert = function (x) {return parseInt(x, 10);};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'b':
	                typeconvert = function (x) {return parseInt(x, 10) !== 0;};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'd':
	                typeconvert = function (x) {return parseFloat(x);};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'n':
	                readdata = null;
	            break;
	            case 's':
	                ccount = read_until(data, dataoffset, ':');
	                chrs = ccount[0];
	                stringlength = ccount[1];
	                dataoffset += chrs + 2;

	                readData = read_chrs(data, dataoffset+1, parseInt(stringlength, 10));
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 2;
	                if (chrs != parseInt(stringlength, 10) && chrs != readdata.length){
	                    error('SyntaxError', 'String length mismatch');
	                }

	                // Length was calculated on an utf-8 encoded string
	                // so wait with decoding
	                readdata = utf8_decode(readdata);
	            break;
	            case 'a':
	                readdata = {};

	                keyandchrs = read_until(data, dataoffset, ':');
	                chrs = keyandchrs[0];
	                keys = keyandchrs[1];
	                dataoffset += chrs + 2;

	                for (var i = 0; i < parseInt(keys, 10); i++){
	                    var kprops = _unserialize(data, dataoffset);
	                    var kchrs = kprops[1];
	                    var key = kprops[2];
	                    dataoffset += kchrs;

	                    var vprops = _unserialize(data, dataoffset);
	                    var vchrs = vprops[1];
	                    var value = vprops[2];
	                    dataoffset += vchrs;

	                    readdata[key] = value;
	                }

	                dataoffset += 1;
	            break;
	            default:
	                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
	            break;
	        }
	        return [dtype, dataoffset - offset, typeconvert(readdata)];
	    };
    
	    return _unserialize((data+''), 0)[2];
	}
});

function serialize (mixed_value) {

    var _getType = function (inp) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {
        case "function": 
            val = ""; 
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            mixed_value = utf8_encode(mixed_value);
            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = this.serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") { 
                    continue; 
                }
                
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serialize(okey) +
                        this.serialize(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = "N";
            break;
    }
    if (type != "object" && type != "array") {
        val += ";";
    }
    return val;
}

function utf8_decode (str_data) {
    var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;
    
    str_data += '';
    
    while ( i < str_data.length ) {
        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if ((c1 > 191) && (c1 < 224)) {
            c2 = str_data.charCodeAt(i+1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i+1);
            c3 = str_data.charCodeAt(i+2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }

    return tmp_arr.join('');
}

function utf8_encode ( argString ) {
    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}