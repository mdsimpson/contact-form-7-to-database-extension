<?php
/*
    "Contact Form to Database" Copyright (C) 2011-2012 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Contact Form to Database.

    Contact Form to Database is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Contact Form to Database is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database.
    If not, see <http://www.gnu.org/licenses/>.
*/

// Taken from: http://www.ustrem.org/en/articles/mime-type-by-extension-en/
class CFDBMimeTypeExtensions {
    public $type_by_ext = array(
        '3dm' => 'x-world/x-3dmf',
        '3dmf' => 'x-world/x-3dmf',
        'a' => 'application/octet-stream',
        'aab' => 'application/x-authorware-bin',
        'aam' => 'application/x-authorware-map',
        'aas' => 'application/x-authorware-seg',
        'abc' => 'text/vnd.abc',
        'acgi' => 'text/html',
        'afl' => 'video/animaflex',
        'ai' => 'application/postscript',
        'aif' =>
        array(
            0 => 'audio/aiff',
            1 => 'audio/x-aiff',
        ),
        'aifc' =>
        array(
            0 => 'audio/aiff',
            1 => 'audio/x-aiff',
        ),
        'aiff' =>
        array(
            0 => 'audio/aiff',
            1 => 'audio/x-aiff',
        ),
        'aim' => 'application/x-aim',
        'aip' => 'text/x-audiosoft-intra',
        'ani' => 'application/x-navi-animation',
        'aos' => 'application/x-nokia-9000-communicator-add-on-software',
        'aps' => 'application/mime',
        'arc' => 'application/octet-stream',
        'arj' =>
        array(
            0 => 'application/arj',
            1 => 'application/octet-stream',
        ),
        'art' => 'image/x-jg',
        'asf' => 'video/x-ms-asf',
        'asm' => 'text/x-asm',
        'asp' => 'text/asp',
        'asx' =>
        array(
            0 => 'application/x-mplayer2',
            1 => 'video/x-ms-asf',
            2 => 'video/x-ms-asf-plugin',
        ),
        'au' =>
        array(
            0 => 'audio/basic',
            1 => 'audio/x-au',
        ),
        'avi' =>
        array(
            0 => 'application/x-troff-msvideo',
            1 => 'video/avi',
            2 => 'video/msvideo',
            3 => 'video/x-msvideo',
        ),
        'avs' => 'video/avs-video',
        'bcpio' => 'application/x-bcpio',
        'bin' =>
        array(
            0 => 'application/mac-binary',
            1 => 'application/macbinary',
            2 => 'application/octet-stream',
            3 => 'application/x-binary',
            4 => 'application/x-macbinary',
        ),
        'bm' => 'image/bmp',
        'bmp' =>
        array(
            0 => 'image/bmp',
            1 => 'image/x-windows-bmp',
        ),
        'boo' => 'application/book',
        'book' => 'application/book',
        'boz' => 'application/x-bzip2',
        'bsh' => 'application/x-bsh',
        'bz' => 'application/x-bzip',
        'bz2' => 'application/x-bzip2',
        'c' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-c',
        ),
        'c++' => 'text/plain',
        'cat' => 'application/vnd.ms-pki.seccat',
        'cc' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-c',
        ),
        'ccad' => 'application/clariscad',
        'cco' => 'application/x-cocoa',
        'cdf' =>
        array(
            0 => 'application/cdf',
            1 => 'application/x-cdf',
            2 => 'application/x-netcdf',
        ),
        'cer' =>
        array(
            0 => 'application/pkix-cert',
            1 => 'application/x-x509-ca-cert',
        ),
        'cha' => 'application/x-chat',
        'chat' => 'application/x-chat',
        'class' =>
        array(
            0 => 'application/java',
            1 => 'application/java-byte-code',
            2 => 'application/x-java-class',
        ),
        'com' =>
        array(
            0 => 'application/octet-stream',
            1 => 'text/plain',
        ),
        'conf' => 'text/plain',
        'cpio' => 'application/x-cpio',
        'cpp' => 'text/x-c',
        'cpt' =>
        array(
            0 => 'application/mac-compactpro',
            1 => 'application/x-compactpro',
            2 => 'application/x-cpt',
        ),
        'crl' =>
        array(
            0 => 'application/pkcs-crl',
            1 => 'application/pkix-crl',
        ),
        'crt' =>
        array(
            0 => 'application/pkix-cert',
            1 => 'application/x-x509-ca-cert',
            2 => 'application/x-x509-user-cert',
        ),
        'csh' =>
        array(
            0 => 'application/x-csh',
            1 => 'text/x-script.csh',
        ),
        'css' =>
        array(
            0 => 'application/x-pointplus',
            1 => 'text/css',
        ),
        'cxx' => 'text/plain',
        'dcr' => 'application/x-director',
        'deepv' => 'application/x-deepv',
        'def' => 'text/plain',
        'der' => 'application/x-x509-ca-cert',
        'dif' => 'video/x-dv',
        'dir' => 'application/x-director',
        'dl' =>
        array(
            0 => 'video/dl',
            1 => 'video/x-dl',
        ),
        'doc' => 'application/msword',
        'dot' => 'application/msword',
        'dp' => 'application/commonground',
        'drw' => 'application/drafting',
        'dump' => 'application/octet-stream',
        'dv' => 'video/x-dv',
        'dvi' => 'application/x-dvi',
        'dwf' =>
        array(
            0 => 'drawing/x-dwf (old)',
            1 => 'model/vnd.dwf',
        ),
        'dwg' =>
        array(
            0 => 'application/acad',
            1 => 'image/vnd.dwg',
            2 => 'image/x-dwg',
        ),
        'dxf' =>
        array(
            0 => 'application/dxf',
            1 => 'image/vnd.dwg',
            2 => 'image/x-dwg',
        ),
        'dxr' => 'application/x-director',
        'el' => 'text/x-script.elisp',
        'elc' =>
        array(
            0 => 'application/x-bytecode.elisp (compiled elisp)',
            1 => 'application/x-elc',
        ),
        'env' => 'application/x-envoy',
        'eps' => 'application/postscript',
        'es' => 'application/x-esrehber',
        'etx' => 'text/x-setext',
        'evy' =>
        array(
            0 => 'application/envoy',
            1 => 'application/x-envoy',
        ),
        'exe' => 'application/octet-stream',
        'f' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-fortran',
        ),
        'f77' => 'text/x-fortran',
        'f90' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-fortran',
        ),
        'fdf' => 'application/vnd.fdf',
        'fif' =>
        array(
            0 => 'application/fractals',
            1 => 'image/fif',
        ),
        'fli' =>
        array(
            0 => 'video/fli',
            1 => 'video/x-fli',
        ),
        'flo' => 'image/florian',
        'flx' => 'text/vnd.fmi.flexstor',
        'fmf' => 'video/x-atomic3d-feature',
        'for' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-fortran',
        ),
        'fpx' =>
        array(
            0 => 'image/vnd.fpx',
            1 => 'image/vnd.net-fpx',
        ),
        'frl' => 'application/freeloader',
        'funk' => 'audio/make',
        'g' => 'text/plain',
        'g3' => 'image/g3fax',
        'gif' => 'image/gif',
        'gl' =>
        array(
            0 => 'video/gl',
            1 => 'video/x-gl',
        ),
        'gsd' => 'audio/x-gsm',
        'gsm' => 'audio/x-gsm',
        'gsp' => 'application/x-gsp',
        'gss' => 'application/x-gss',
        'gtar' => 'application/x-gtar',
        'gz' =>
        array(
            0 => 'application/x-compressed',
            1 => 'application/x-gzip',
        ),
        'gzip' =>
        array(
            0 => 'application/x-gzip',
            1 => 'multipart/x-gzip',
        ),
        'h' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-h',
        ),
        'hdf' => 'application/x-hdf',
        'help' => 'application/x-helpfile',
        'hgl' => 'application/vnd.hp-hpgl',
        'hh' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-h',
        ),
        'hlb' => 'text/x-script',
        'hlp' =>
        array(
            0 => 'application/hlp',
            1 => 'application/x-helpfile',
            2 => 'application/x-winhelp',
        ),
        'hpg' => 'application/vnd.hp-hpgl',
        'hpgl' => 'application/vnd.hp-hpgl',
        'hqx' =>
        array(
            0 => 'application/binhex',
            1 => 'application/binhex4',
            2 => 'application/mac-binhex',
            3 => 'application/mac-binhex40',
            4 => 'application/x-binhex40',
            5 => 'application/x-mac-binhex40',
        ),
        'hta' => 'application/hta',
        'htc' => 'text/x-component',
        'htm' => 'text/html',
        'html' => 'text/html',
        'htmls' => 'text/html',
        'htt' => 'text/webviewhtml',
        'htx' => 'text/html',
        'ice' => 'x-conference/x-cooltalk',
        'ico' => 'image/x-icon',
        'idc' => 'text/plain',
        'ief' => 'image/ief',
        'iefs' => 'image/ief',
        'iges' =>
        array(
            0 => 'application/iges',
            1 => 'model/iges',
        ),
        'igs' =>
        array(
            0 => 'application/iges',
            1 => 'model/iges',
        ),
        'ima' => 'application/x-ima',
        'imap' => 'application/x-httpd-imap',
        'inf' => 'application/inf',
        'ins' => 'application/x-internett-signup',
        'ip' => 'application/x-ip2',
        'isu' => 'video/x-isvideo',
        'it' => 'audio/it',
        'iv' => 'application/x-inventor',
        'ivr' => 'i-world/i-vrml',
        'ivy' => 'application/x-livescreen',
        'jam' => 'audio/x-jam',
        'jav' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-java-source',
        ),
        'java' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-java-source',
        ),
        'jcm' => 'application/x-java-commerce',
        'jfif' =>
        array(
            0 => 'image/jpeg',
            1 => 'image/pjpeg',
        ),
        'jfif-tbnl' => 'image/jpeg',
        'jpe' =>
        array(
            0 => 'image/jpeg',
            1 => 'image/pjpeg',
        ),
        'jpeg' =>
        array(
            0 => 'image/jpeg',
            1 => 'image/pjpeg',
        ),
        'jpg' =>
        array(
            0 => 'image/jpeg',
            1 => 'image/pjpeg',
        ),
        'jps' => 'image/x-jps',
        'js' => 'application/x-javascript',
        'jut' => 'image/jutvision',
        'kar' =>
        array(
            0 => 'audio/midi',
            1 => 'music/x-karaoke',
        ),
        'ksh' =>
        array(
            0 => 'application/x-ksh',
            1 => 'text/x-script.ksh',
        ),
        'la' =>
        array(
            0 => 'audio/nspaudio',
            1 => 'audio/x-nspaudio',
        ),
        'lam' => 'audio/x-liveaudio',
        'latex' => 'application/x-latex',
        'lha' =>
        array(
            0 => 'application/lha',
            1 => 'application/octet-stream',
            2 => 'application/x-lha',
        ),
        'lhx' => 'application/octet-stream',
        'list' => 'text/plain',
        'lma' =>
        array(
            0 => 'audio/nspaudio',
            1 => 'audio/x-nspaudio',
        ),
        'log' => 'text/plain',
        'lsp' =>
        array(
            0 => 'application/x-lisp',
            1 => 'text/x-script.lisp',
        ),
        'lst' => 'text/plain',
        'lsx' => 'text/x-la-asf',
        'ltx' => 'application/x-latex',
        'lzh' =>
        array(
            0 => 'application/octet-stream',
            1 => 'application/x-lzh',
        ),
        'lzx' =>
        array(
            0 => 'application/lzx',
            1 => 'application/octet-stream',
            2 => 'application/x-lzx',
        ),
        'm' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-m',
        ),
        'm1v' => 'video/mpeg',
        'm2a' => 'audio/mpeg',
        'm2v' => 'video/mpeg',
        'm3u' => 'audio/x-mpequrl',
        'man' => 'application/x-troff-man',
        'map' => 'application/x-navimap',
        'mar' => 'text/plain',
        'mbd' => 'application/mbedlet',
        'mc$' => 'application/x-magic-cap-package-1.0',
        'mcd' =>
        array(
            0 => 'application/mcad',
            1 => 'application/x-mathcad',
        ),
        'mcf' =>
        array(
            0 => 'image/vasa',
            1 => 'text/mcf',
        ),
        'mcp' => 'application/netmc',
        'me' => 'application/x-troff-me',
        'mht' => 'message/rfc822',
        'mhtml' => 'message/rfc822',
        'mid' =>
        array(
            0 => 'application/x-midi',
            1 => 'audio/midi',
            2 => 'audio/x-mid',
            3 => 'audio/x-midi',
            4 => 'music/crescendo',
            5 => 'x-music/x-midi',
        ),
        'midi' =>
        array(
            0 => 'application/x-midi',
            1 => 'audio/midi',
            2 => 'audio/x-mid',
            3 => 'audio/x-midi',
            4 => 'music/crescendo',
            5 => 'x-music/x-midi',
        ),
        'mif' =>
        array(
            0 => 'application/x-frame',
            1 => 'application/x-mif',
        ),
        'mime' =>
        array(
            0 => 'message/rfc822',
            1 => 'www/mime',
        ),
        'mjf' => 'audio/x-vnd.audioexplosion.mjuicemediafile',
        'mjpg' => 'video/x-motion-jpeg',
        'mm' =>
        array(
            0 => 'application/base64',
            1 => 'application/x-meme',
        ),
        'mme' => 'application/base64',
        'mod' =>
        array(
            0 => 'audio/mod',
            1 => 'audio/x-mod',
        ),
        'moov' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'mp2' =>
        array(
            0 => 'audio/mpeg',
            1 => 'audio/x-mpeg',
            2 => 'video/mpeg',
            3 => 'video/x-mpeg',
            4 => 'video/x-mpeq2a',
        ),
        'mp3' =>
        array(
            0 => 'audio/mpeg3',
            1 => 'audio/x-mpeg-3',
            2 => 'video/mpeg',
            3 => 'video/x-mpeg',
        ),
        'mpa' =>
        array(
            0 => 'audio/mpeg',
            1 => 'video/mpeg',
        ),
        'mpc' => 'application/x-project',
        'mpe' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' =>
        array(
            0 => 'audio/mpeg',
            1 => 'video/mpeg',
        ),
        'mpga' => 'audio/mpeg',
        'mpp' => 'application/vnd.ms-project',
        'mpt' => 'application/x-project',
        'mpv' => 'application/x-project',
        'mpx' => 'application/x-project',
        'mrc' => 'application/marc',
        'ms' => 'application/x-troff-ms',
        'mv' => 'video/x-sgi-movie',
        'my' => 'audio/make',
        'mzz' => 'application/x-vnd.audioexplosion.mzz',
        'nap' => 'image/naplps',
        'naplps' => 'image/naplps',
        'nc' => 'application/x-netcdf',
        'ncm' => 'application/vnd.nokia.configuration-message',
        'nif' => 'image/x-niff',
        'niff' => 'image/x-niff',
        'nix' => 'application/x-mix-transfer',
        'nsc' => 'application/x-conference',
        'nvd' => 'application/x-navidoc',
        'o' => 'application/octet-stream',
        'oda' => 'application/oda',
        'omc' => 'application/x-omc',
        'omcd' => 'application/x-omcdatamaker',
        'omcr' => 'application/x-omcregerator',
        'p' => 'text/x-pascal',
        'p10' =>
        array(
            0 => 'application/pkcs10',
            1 => 'application/x-pkcs10',
        ),
        'p12' =>
        array(
            0 => 'application/pkcs-12',
            1 => 'application/x-pkcs12',
        ),
        'p7a' => 'application/x-pkcs7-signature',
        'p7c' =>
        array(
            0 => 'application/pkcs7-mime',
            1 => 'application/x-pkcs7-mime',
        ),
        'p7m' =>
        array(
            0 => 'application/pkcs7-mime',
            1 => 'application/x-pkcs7-mime',
        ),
        'p7r' => 'application/x-pkcs7-certreqresp',
        'p7s' => 'application/pkcs7-signature',
        'part' => 'application/pro_eng',
        'pas' => 'text/pascal',
        'pbm' => 'image/x-portable-bitmap',
        'pcl' =>
        array(
            0 => 'application/vnd.hp-pcl',
            1 => 'application/x-pcl',
        ),
        'pct' => 'image/x-pict',
        'pcx' => 'image/x-pcx',
        'pdb' => 'chemical/x-pdb',
        'pdf' => 'application/pdf',
        'pfunk' =>
        array(
            0 => 'audio/make',
            1 => 'audio/make.my.funk',
        ),
        'pgm' =>
        array(
            0 => 'image/x-portable-graymap',
            1 => 'image/x-portable-greymap',
        ),
        'pic' => 'image/pict',
        'pict' => 'image/pict',
        'pkg' => 'application/x-newton-compatible-pkg',
        'pko' => 'application/vnd.ms-pki.pko',
        'pl' =>
        array(
            0 => 'text/plain',
            1 => 'text/x-script.perl',
        ),
        'plx' => 'application/x-pixclscript',
        'pm' =>
        array(
            0 => 'image/x-xpixmap',
            1 => 'text/x-script.perl-module',
        ),
        'pm4' => 'application/x-pagemaker',
        'pm5' => 'application/x-pagemaker',
        'png' => 'image/png',
        'pnm' =>
        array(
            0 => 'application/x-portable-anymap',
            1 => 'image/x-portable-anymap',
        ),
        'pot' =>
        array(
            0 => 'application/mspowerpoint',
            1 => 'application/vnd.ms-powerpoint',
        ),
        'pov' => 'model/x-pov',
        'ppa' => 'application/vnd.ms-powerpoint',
        'ppm' => 'image/x-portable-pixmap',
        'pps' =>
        array(
            0 => 'application/mspowerpoint',
            1 => 'application/vnd.ms-powerpoint',
        ),
        'ppt' =>
        array(
            0 => 'application/mspowerpoint',
            1 => 'application/powerpoint',
            2 => 'application/vnd.ms-powerpoint',
            3 => 'application/x-mspowerpoint',
        ),
        'ppz' => 'application/mspowerpoint',
        'pre' => 'application/x-freelance',
        'prt' => 'application/pro_eng',
        'ps' => 'application/postscript',
        'psd' => 'application/octet-stream',
        'pvu' => 'paleovu/x-pv',
        'pwz' => 'application/vnd.ms-powerpoint',
        'py' => 'text/x-script.phyton',
        'pyc' => 'applicaiton/x-bytecode.python',
        'qcp' => 'audio/vnd.qcelp',
        'qd3' => 'x-world/x-3dmf',
        'qd3d' => 'x-world/x-3dmf',
        'qif' => 'image/x-quicktime',
        'qt' => 'video/quicktime',
        'qtc' => 'video/x-qtc',
        'qti' => 'image/x-quicktime',
        'qtif' => 'image/x-quicktime',
        'ra' =>
        array(
            0 => 'audio/x-pn-realaudio',
            1 => 'audio/x-pn-realaudio-plugin',
            2 => 'audio/x-realaudio',
        ),
        'ram' => 'audio/x-pn-realaudio',
        'ras' =>
        array(
            0 => 'application/x-cmu-raster',
            1 => 'image/cmu-raster',
            2 => 'image/x-cmu-raster',
        ),
        'rast' => 'image/cmu-raster',
        'rexx' => 'text/x-script.rexx',
        'rf' => 'image/vnd.rn-realflash',
        'rgb' => 'image/x-rgb',
        'rm' =>
        array(
            0 => 'application/vnd.rn-realmedia',
            1 => 'audio/x-pn-realaudio',
        ),
        'rmi' => 'audio/mid',
        'rmm' => 'audio/x-pn-realaudio',
        'rmp' =>
        array(
            0 => 'audio/x-pn-realaudio',
            1 => 'audio/x-pn-realaudio-plugin',
        ),
        'rng' =>
        array(
            0 => 'application/ringing-tones',
            1 => 'application/vnd.nokia.ringing-tone',
        ),
        'rnx' => 'application/vnd.rn-realplayer',
        'roff' => 'application/x-troff',
        'rp' => 'image/vnd.rn-realpix',
        'rpm' => 'audio/x-pn-realaudio-plugin',
        'rt' =>
        array(
            0 => 'text/richtext',
            1 => 'text/vnd.rn-realtext',
        ),
        'rtf' =>
        array(
            0 => 'application/rtf',
            1 => 'application/x-rtf',
            2 => 'text/richtext',
        ),
        'rtx' =>
        array(
            0 => 'application/rtf',
            1 => 'text/richtext',
        ),
        'rv' => 'video/vnd.rn-realvideo',
        's' => 'text/x-asm',
        's3m' => 'audio/s3m',
        'saveme' => 'application/octet-stream',
        'sbk' => 'application/x-tbook',
        'scm' =>
        array(
            0 => 'application/x-lotusscreencam',
            1 => 'text/x-script.guile',
            2 => 'text/x-script.scheme',
            3 => 'video/x-scm',
        ),
        'sdml' => 'text/plain',
        'sdp' =>
        array(
            0 => 'application/sdp',
            1 => 'application/x-sdp',
        ),
        'sdr' => 'application/sounder',
        'sea' =>
        array(
            0 => 'application/sea',
            1 => 'application/x-sea',
        ),
        'set' => 'application/set',
        'sgm' =>
        array(
            0 => 'text/sgml',
            1 => 'text/x-sgml',
        ),
        'sgml' =>
        array(
            0 => 'text/sgml',
            1 => 'text/x-sgml',
        ),
        'sh' =>
        array(
            0 => 'application/x-bsh',
            1 => 'application/x-sh',
            2 => 'application/x-shar',
            3 => 'text/x-script.sh',
        ),
        'shar' =>
        array(
            0 => 'application/x-bsh',
            1 => 'application/x-shar',
        ),
        'shtml' =>
        array(
            0 => 'text/html',
            1 => 'text/x-server-parsed-html',
        ),
        'sid' => 'audio/x-psid',
        'sit' =>
        array(
            0 => 'application/x-sit',
            1 => 'application/x-stuffit',
        ),
        'skd' => 'application/x-koan',
        'skm' => 'application/x-koan',
        'skp' => 'application/x-koan',
        'skt' => 'application/x-koan',
        'sl' => 'application/x-seelogo',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'snd' =>
        array(
            0 => 'audio/basic',
            1 => 'audio/x-adpcm',
        ),
        'sol' => 'application/solids',
        'spc' =>
        array(
            0 => 'application/x-pkcs7-certificates',
            1 => 'text/x-speech',
        ),
        'spl' => 'application/futuresplash',
        'spr' => 'application/x-sprite',
        'sprite' => 'application/x-sprite',
        'src' => 'application/x-wais-source',
        'ssi' => 'text/x-server-parsed-html',
        'ssm' => 'application/streamingmedia',
        'sst' => 'application/vnd.ms-pki.certstore',
        'step' => 'application/step',
        'stl' =>
        array(
            0 => 'application/sla',
            1 => 'application/vnd.ms-pki.stl',
            2 => 'application/x-navistyle',
        ),
        'stp' => 'application/step',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc' => 'application/x-sv4crc',
        'svf' =>
        array(
            0 => 'image/vnd.dwg',
            1 => 'image/x-dwg',
        ),
        'svr' =>
        array(
            0 => 'application/x-world',
            1 => 'x-world/x-svr',
        ),
        'swf' => 'application/x-shockwave-flash',
        't' => 'application/x-troff',
        'talk' => 'text/x-speech',
        'tar' => 'application/x-tar',
        'tbk' =>
        array(
            0 => 'application/toolbook',
            1 => 'application/x-tbook',
        ),
        'tcl' =>
        array(
            0 => 'application/x-tcl',
            1 => 'text/x-script.tcl',
        ),
        'tcsh' => 'text/x-script.tcsh',
        'tex' => 'application/x-tex',
        'texi' => 'application/x-texinfo',
        'texinfo' => 'application/x-texinfo',
        'text' =>
        array(
            0 => 'application/plain',
            1 => 'text/plain',
        ),
        'tgz' =>
        array(
            0 => 'application/gnutar',
            1 => 'application/x-compressed',
        ),
        'tif' =>
        array(
            0 => 'image/tiff',
            1 => 'image/x-tiff',
        ),
        'tiff' =>
        array(
            0 => 'image/tiff',
            1 => 'image/x-tiff',
        ),
        'tr' => 'application/x-troff',
        'tsi' => 'audio/tsp-audio',
        'tsp' =>
        array(
            0 => 'application/dsptype',
            1 => 'audio/tsplayer',
        ),
        'tsv' => 'text/tab-separated-values',
        'turbot' => 'image/florian',
        'txt' => 'text/plain',
        'uil' => 'text/x-uil',
        'uni' => 'text/uri-list',
        'unis' => 'text/uri-list',
        'unv' => 'application/i-deas',
        'uri' => 'text/uri-list',
        'uris' => 'text/uri-list',
        'ustar' =>
        array(
            0 => 'application/x-ustar',
            1 => 'multipart/x-ustar',
        ),
        'uu' =>
        array(
            0 => 'application/octet-stream',
            1 => 'text/x-uuencode',
        ),
        'uue' => 'text/x-uuencode',
        'vcd' => 'application/x-cdlink',
        'vcs' => 'text/x-vcalendar',
        'vda' => 'application/vda',
        'vdo' => 'video/vdo',
        'vew' => 'application/groupwise',
        'viv' =>
        array(
            0 => 'video/vivo',
            1 => 'video/vnd.vivo',
        ),
        'vivo' =>
        array(
            0 => 'video/vivo',
            1 => 'video/vnd.vivo',
        ),
        'vmd' => 'application/vocaltec-media-desc',
        'vmf' => 'application/vocaltec-media-file',
        'voc' =>
        array(
            0 => 'audio/voc',
            1 => 'audio/x-voc',
        ),
        'vos' => 'video/vosaic',
        'vox' => 'audio/voxware',
        'vqe' => 'audio/x-twinvq-plugin',
        'vqf' => 'audio/x-twinvq',
        'vql' => 'audio/x-twinvq-plugin',
        'vrml' =>
        array(
            0 => 'application/x-vrml',
            1 => 'model/vrml',
            2 => 'x-world/x-vrml',
        ),
        'vrt' => 'x-world/x-vrt',
        'vsd' => 'application/x-visio',
        'vst' => 'application/x-visio',
        'vsw' => 'application/x-visio',
        'w60' => 'application/wordperfect6.0',
        'w61' => 'application/wordperfect6.1',
        'w6w' => 'application/msword',
        'wav' =>
        array(
            0 => 'audio/wav',
            1 => 'audio/x-wav',
        ),
        'wb1' => 'application/x-qpro',
        'wbmp' => 'image/vnd.wap.wbmp',
        'web' => 'application/vnd.xara',
        'wiz' => 'application/msword',
        'wk1' => 'application/x-123',
        'wmf' => 'windows/metafile',
        'wml' => 'text/vnd.wap.wml',
        'wmlc' => 'application/vnd.wap.wmlc',
        'wmls' => 'text/vnd.wap.wmlscript',
        'wmlsc' => 'application/vnd.wap.wmlscriptc',
        'word' => 'application/msword',
        'wp' => 'application/wordperfect',
        'wp5' =>
        array(
            0 => 'application/wordperfect',
            1 => 'application/wordperfect6.0',
        ),
        'wp6' => 'application/wordperfect',
        'wpd' =>
        array(
            0 => 'application/wordperfect',
            1 => 'application/x-wpwin',
        ),
        'wq1' => 'application/x-lotus',
        'wri' =>
        array(
            0 => 'application/mswrite',
            1 => 'application/x-wri',
        ),
        'wrl' =>
        array(
            0 => 'application/x-world',
            1 => 'model/vrml',
            2 => 'x-world/x-vrml',
        ),
        'wrz' =>
        array(
            0 => 'model/vrml',
            1 => 'x-world/x-vrml',
        ),
        'wsc' => 'text/scriplet',
        'wsrc' => 'application/x-wais-source',
        'wtk' => 'application/x-wintalk',
        'xbm' =>
        array(
            0 => 'image/x-xbitmap',
            1 => 'image/x-xbm',
            2 => 'image/xbm',
        ),
        'xdr' => 'video/x-amt-demorun',
        'xgz' => 'xgl/drawing',
        'xif' => 'image/vnd.xiff',
        'xl' => 'application/excel',
        'xla' =>
        array(
            0 => 'application/excel',
            1 => 'application/x-excel',
            2 => 'application/x-msexcel',
        ),
        'xlb' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
        ),
        'xlc' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
        ),
        'xld' =>
        array(
            0 => 'application/excel',
            1 => 'application/x-excel',
        ),
        'xlk' =>
        array(
            0 => 'application/excel',
            1 => 'application/x-excel',
        ),
        'xll' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
        ),
        'xlm' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
        ),
        'xls' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
            3 => 'application/x-msexcel',
        ),
        'xlt' =>
        array(
            0 => 'application/excel',
            1 => 'application/x-excel',
        ),
        'xlv' =>
        array(
            0 => 'application/excel',
            1 => 'application/x-excel',
        ),
        'xlw' =>
        array(
            0 => 'application/excel',
            1 => 'application/vnd.ms-excel',
            2 => 'application/x-excel',
            3 => 'application/x-msexcel',
        ),
        'xm' => 'audio/xm',
        'xml' =>
        array(
            0 => 'application/xml',
            1 => 'text/xml',
        ),
        'xmz' => 'xgl/movie',
        'xpix' => 'application/x-vnd.ls-xpix',
        'xpm' =>
        array(
            0 => 'image/x-xpixmap',
            1 => 'image/xpm',
        ),
        'x-png' => 'image/png',
        'xsr' => 'video/x-amt-showrun',
        'xwd' =>
        array(
            0 => 'image/x-xwd',
            1 => 'image/x-xwindowdump',
        ),
        'xyz' => 'chemical/x-pdb',
        'z' =>
        array(
            0 => 'application/x-compress',
            1 => 'application/x-compressed',
        ),
        'zip' =>
        array(
            0 => 'application/x-compressed',
            1 => 'application/x-zip-compressed',
            2 => 'application/zip',
            3 => 'multipart/x-zip',
        ),
        'zoo' => 'application/octet-stream',
        'zsh' => 'text/x-script.zsh',

        // Newer MS doc types
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'xlsm' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12'


    );

/*
    public $ext_by_type = array(
        'x-world/x-3dmf' =>
        array(
            0 => '3dm',
            1 => '3dmf',
            2 => 'qd3',
            3 => 'qd3d',
        ),
        'application/octet-stream' =>
        array(
            0 => 'a',
            1 => 'arc',
            2 => 'arj',
            3 => 'bin',
            4 => 'com',
            5 => 'dump',
            6 => 'exe',
            7 => 'lha',
            8 => 'lhx',
            9 => 'lzh',
            10 => 'lzx',
            11 => 'o',
            12 => 'psd',
            13 => 'saveme',
            14 => 'uu',
            15 => 'zoo',
        ),
        'application/x-authorware-bin' => 'aab',
        'application/x-authorware-map' => 'aam',
        'application/x-authorware-seg' => 'aas',
        'text/vnd.abc' => 'abc',
        'text/html' =>
        array(
            0 => 'acgi',
            1 => 'htm',
            2 => 'html',
            3 => 'htmls',
            4 => 'htx',
            5 => 'shtml',
        ),
        'video/animaflex' => 'afl',
        'application/postscript' =>
        array(
            0 => 'ai',
            1 => 'eps',
            2 => 'ps',
        ),
        'audio/aiff' =>
        array(
            0 => 'aif',
            1 => 'aifc',
            2 => 'aiff',
        ),
        'audio/x-aiff' =>
        array(
            0 => 'aif',
            1 => 'aifc',
            2 => 'aiff',
        ),
        'application/x-aim' => 'aim',
        'text/x-audiosoft-intra' => 'aip',
        'application/x-navi-animation' => 'ani',
        'application/x-nokia-9000-communicator-add-on-software' => 'aos',
        'application/mime' => 'aps',
        'application/arj' => 'arj',
        'image/x-jg' => 'art',
        'video/x-ms-asf' =>
        array(
            0 => 'asf',
            1 => 'asx',
        ),
        'text/x-asm' =>
        array(
            0 => 'asm',
            1 => 's',
        ),
        'text/asp' => 'asp',
        'application/x-mplayer2' => 'asx',
        'video/x-ms-asf-plugin' => 'asx',
        'audio/basic' =>
        array(
            0 => 'au',
            1 => 'snd',
        ),
        'audio/x-au' => 'au',
        'application/x-troff-msvideo' => 'avi',
        'video/avi' => 'avi',
        'video/msvideo' => 'avi',
        'video/x-msvideo' => 'avi',
        'video/avs-video' => 'avs',
        'application/x-bcpio' => 'bcpio',
        'application/mac-binary' => 'bin',
        'application/macbinary' => 'bin',
        'application/x-binary' => 'bin',
        'application/x-macbinary' => 'bin',
        'image/bmp' =>
        array(
            0 => 'bm',
            1 => 'bmp',
        ),
        'image/x-windows-bmp' => 'bmp',
        'application/book' =>
        array(
            0 => 'boo',
            1 => 'book',
        ),
        'application/x-bzip2' =>
        array(
            0 => 'boz',
            1 => 'bz2',
        ),
        'application/x-bsh' =>
        array(
            0 => 'bsh',
            1 => 'sh',
            2 => 'shar',
        ),
        'application/x-bzip' => 'bz',
        'text/plain' =>
        array(
            0 => 'c',
            1 => 'c++',
            2 => 'cc',
            3 => 'com',
            4 => 'conf',
            5 => 'cxx',
            6 => 'def',
            7 => 'f',
            8 => 'f90',
            9 => 'for',
            10 => 'g',
            11 => 'h',
            12 => 'hh',
            13 => 'idc',
            14 => 'jav',
            15 => 'java',
            16 => 'list',
            17 => 'log',
            18 => 'lst',
            19 => 'm',
            20 => 'mar',
            21 => 'pl',
            22 => 'sdml',
            23 => 'text',
            24 => 'txt',
        ),
        'text/x-c' =>
        array(
            0 => 'c',
            1 => 'cc',
            2 => 'cpp',
        ),
        'application/vnd.ms-pki.seccat' => 'cat',
        'application/clariscad' => 'ccad',
        'application/x-cocoa' => 'cco',
        'application/cdf' => 'cdf',
        'application/x-cdf' => 'cdf',
        'application/x-netcdf' =>
        array(
            0 => 'cdf',
            1 => 'nc',
        ),
        'application/pkix-cert' =>
        array(
            0 => 'cer',
            1 => 'crt',
        ),
        'application/x-x509-ca-cert' =>
        array(
            0 => 'cer',
            1 => 'crt',
            2 => 'der',
        ),
        'application/x-chat' =>
        array(
            0 => 'cha',
            1 => 'chat',
        ),
        'application/java' => 'class',
        'application/java-byte-code' => 'class',
        'application/x-java-class' => 'class',
        'application/x-cpio' => 'cpio',
        'application/mac-compactpro' => 'cpt',
        'application/x-compactpro' => 'cpt',
        'application/x-cpt' => 'cpt',
        'application/pkcs-crl' => 'crl',
        'application/pkix-crl' => 'crl',
        'application/x-x509-user-cert' => 'crt',
        'application/x-csh' => 'csh',
        'text/x-script.csh' => 'csh',
        'application/x-pointplus' => 'css',
        'text/css' => 'css',
        'application/x-director' =>
        array(
            0 => 'dcr',
            1 => 'dir',
            2 => 'dxr',
        ),
        'application/x-deepv' => 'deepv',
        'video/x-dv' =>
        array(
            0 => 'dif',
            1 => 'dv',
        ),
        'video/dl' => 'dl',
        'video/x-dl' => 'dl',
        'application/msword' =>
        array(
            0 => 'doc',
            1 => 'dot',
            2 => 'w6w',
            3 => 'wiz',
            4 => 'word',
        ),
        'application/commonground' => 'dp',
        'application/drafting' => 'drw',
        'application/x-dvi' => 'dvi',
        'drawing/x-dwf (old)' => 'dwf',
        'model/vnd.dwf' => 'dwf',
        'application/acad' => 'dwg',
        'image/vnd.dwg' =>
        array(
            0 => 'dwg',
            1 => 'dxf',
            2 => 'svf',
        ),
        'image/x-dwg' =>
        array(
            0 => 'dwg',
            1 => 'dxf',
            2 => 'svf',
        ),
        'application/dxf' => 'dxf',
        'text/x-script.elisp' => 'el',
        'application/x-bytecode.elisp (compiled elisp)' => 'elc',
        'application/x-elc' => 'elc',
        'application/x-envoy' =>
        array(
            0 => 'env',
            1 => 'evy',
        ),
        'application/x-esrehber' => 'es',
        'text/x-setext' => 'etx',
        'application/envoy' => 'evy',
        'text/x-fortran' =>
        array(
            0 => 'f',
            1 => 'f77',
            2 => 'f90',
            3 => 'for',
        ),
        'application/vnd.fdf' => 'fdf',
        'application/fractals' => 'fif',
        'image/fif' => 'fif',
        'video/fli' => 'fli',
        'video/x-fli' => 'fli',
        'image/florian' =>
        array(
            0 => 'flo',
            1 => 'turbot',
        ),
        'text/vnd.fmi.flexstor' => 'flx',
        'video/x-atomic3d-feature' => 'fmf',
        'image/vnd.fpx' => 'fpx',
        'image/vnd.net-fpx' => 'fpx',
        'application/freeloader' => 'frl',
        'audio/make' =>
        array(
            0 => 'funk',
            1 => 'my',
            2 => 'pfunk',
        ),
        'image/g3fax' => 'g3',
        'image/gif' => 'gif',
        'video/gl' => 'gl',
        'video/x-gl' => 'gl',
        'audio/x-gsm' =>
        array(
            0 => 'gsd',
            1 => 'gsm',
        ),
        'application/x-gsp' => 'gsp',
        'application/x-gss' => 'gss',
        'application/x-gtar' => 'gtar',
        'application/x-compressed' =>
        array(
            0 => 'gz',
            1 => 'tgz',
            2 => 'z',
            3 => 'zip',
        ),
        'application/x-gzip' =>
        array(
            0 => 'gz',
            1 => 'gzip',
        ),
        'multipart/x-gzip' => 'gzip',
        'text/x-h' =>
        array(
            0 => 'h',
            1 => 'hh',
        ),
        'application/x-hdf' => 'hdf',
        'application/x-helpfile' =>
        array(
            0 => 'help',
            1 => 'hlp',
        ),
        'application/vnd.hp-hpgl' =>
        array(
            0 => 'hgl',
            1 => 'hpg',
            2 => 'hpgl',
        ),
        'text/x-script' => 'hlb',
        'application/hlp' => 'hlp',
        'application/x-winhelp' => 'hlp',
        'application/binhex' => 'hqx',
        'application/binhex4' => 'hqx',
        'application/mac-binhex' => 'hqx',
        'application/mac-binhex40' => 'hqx',
        'application/x-binhex40' => 'hqx',
        'application/x-mac-binhex40' => 'hqx',
        'application/hta' => 'hta',
        'text/x-component' => 'htc',
        'text/webviewhtml' => 'htt',
        'x-conference/x-cooltalk' => 'ice',
        'image/x-icon' => 'ico',
        'image/ief' =>
        array(
            0 => 'ief',
            1 => 'iefs',
        ),
        'application/iges' =>
        array(
            0 => 'iges',
            1 => 'igs',
        ),
        'model/iges' =>
        array(
            0 => 'iges',
            1 => 'igs',
        ),
        'application/x-ima' => 'ima',
        'application/x-httpd-imap' => 'imap',
        'application/inf' => 'inf',
        'application/x-internett-signup' => 'ins',
        'application/x-ip2' => 'ip',
        'video/x-isvideo' => 'isu',
        'audio/it' => 'it',
        'application/x-inventor' => 'iv',
        'i-world/i-vrml' => 'ivr',
        'application/x-livescreen' => 'ivy',
        'audio/x-jam' => 'jam',
        'text/x-java-source' =>
        array(
            0 => 'jav',
            1 => 'java',
        ),
        'application/x-java-commerce' => 'jcm',
        'image/jpeg' =>
        array(
            0 => 'jfif',
            1 => 'jfif-tbnl',
            2 => 'jpe',
            3 => 'jpeg',
            4 => 'jpg',
        ),
        'image/pjpeg' =>
        array(
            0 => 'jfif',
            1 => 'jpe',
            2 => 'jpeg',
            3 => 'jpg',
        ),
        'image/x-jps' => 'jps',
        'application/x-javascript' => 'js',
        'image/jutvision' => 'jut',
        'audio/midi' =>
        array(
            0 => 'kar',
            1 => 'mid',
            2 => 'midi',
        ),
        'music/x-karaoke' => 'kar',
        'application/x-ksh' => 'ksh',
        'text/x-script.ksh' => 'ksh',
        'audio/nspaudio' =>
        array(
            0 => 'la',
            1 => 'lma',
        ),
        'audio/x-nspaudio' =>
        array(
            0 => 'la',
            1 => 'lma',
        ),
        'audio/x-liveaudio' => 'lam',
        'application/x-latex' =>
        array(
            0 => 'latex',
            1 => 'ltx',
        ),
        'application/lha' => 'lha',
        'application/x-lha' => 'lha',
        'application/x-lisp' => 'lsp',
        'text/x-script.lisp' => 'lsp',
        'text/x-la-asf' => 'lsx',
        'application/x-lzh' => 'lzh',
        'application/lzx' => 'lzx',
        'application/x-lzx' => 'lzx',
        'text/x-m' => 'm',
        'video/mpeg' =>
        array(
            0 => 'm1v',
            1 => 'm2v',
            2 => 'mp2',
            3 => 'mp3',
            4 => 'mpa',
            5 => 'mpe',
            6 => 'mpeg',
            7 => 'mpg',
        ),
        'audio/mpeg' =>
        array(
            0 => 'm2a',
            1 => 'mp2',
            2 => 'mpa',
            3 => 'mpg',
            4 => 'mpga',
        ),
        'audio/x-mpequrl' => 'm3u',
        'application/x-troff-man' => 'man',
        'application/x-navimap' => 'map',
        'application/mbedlet' => 'mbd',
        'application/x-magic-cap-package-1.0' => 'mc$',
        'application/mcad' => 'mcd',
        'application/x-mathcad' => 'mcd',
        'image/vasa' => 'mcf',
        'text/mcf' => 'mcf',
        'application/netmc' => 'mcp',
        'application/x-troff-me' => 'me',
        'message/rfc822' =>
        array(
            0 => 'mht',
            1 => 'mhtml',
            2 => 'mime',
        ),
        'application/x-midi' =>
        array(
            0 => 'mid',
            1 => 'midi',
        ),
        'audio/x-mid' =>
        array(
            0 => 'mid',
            1 => 'midi',
        ),
        'audio/x-midi' =>
        array(
            0 => 'mid',
            1 => 'midi',
        ),
        'music/crescendo' =>
        array(
            0 => 'mid',
            1 => 'midi',
        ),
        'x-music/x-midi' =>
        array(
            0 => 'mid',
            1 => 'midi',
        ),
        'application/x-frame' => 'mif',
        'application/x-mif' => 'mif',
        'www/mime' => 'mime',
        'audio/x-vnd.audioexplosion.mjuicemediafile' => 'mjf',
        'video/x-motion-jpeg' => 'mjpg',
        'application/base64' =>
        array(
            0 => 'mm',
            1 => 'mme',
        ),
        'application/x-meme' => 'mm',
        'audio/mod' => 'mod',
        'audio/x-mod' => 'mod',
        'video/quicktime' =>
        array(
            0 => 'moov',
            1 => 'mov',
            2 => 'qt',
        ),
        'video/x-sgi-movie' =>
        array(
            0 => 'movie',
            1 => 'mv',
        ),
        'audio/x-mpeg' => 'mp2',
        'video/x-mpeg' =>
        array(
            0 => 'mp2',
            1 => 'mp3',
        ),
        'video/x-mpeq2a' => 'mp2',
        'audio/mpeg3' => 'mp3',
        'audio/x-mpeg-3' => 'mp3',
        'application/x-project' =>
        array(
            0 => 'mpc',
            1 => 'mpt',
            2 => 'mpv',
            3 => 'mpx',
        ),
        'application/vnd.ms-project' => 'mpp',
        'application/marc' => 'mrc',
        'application/x-troff-ms' => 'ms',
        'application/x-vnd.audioexplosion.mzz' => 'mzz',
        'image/naplps' =>
        array(
            0 => 'nap',
            1 => 'naplps',
        ),
        'application/vnd.nokia.configuration-message' => 'ncm',
        'image/x-niff' =>
        array(
            0 => 'nif',
            1 => 'niff',
        ),
        'application/x-mix-transfer' => 'nix',
        'application/x-conference' => 'nsc',
        'application/x-navidoc' => 'nvd',
        'application/oda' => 'oda',
        'application/x-omc' => 'omc',
        'application/x-omcdatamaker' => 'omcd',
        'application/x-omcregerator' => 'omcr',
        'text/x-pascal' => 'p',
        'application/pkcs10' => 'p10',
        'application/x-pkcs10' => 'p10',
        'application/pkcs-12' => 'p12',
        'application/x-pkcs12' => 'p12',
        'application/x-pkcs7-signature' => 'p7a',
        'application/pkcs7-mime' =>
        array(
            0 => 'p7c',
            1 => 'p7m',
        ),
        'application/x-pkcs7-mime' =>
        array(
            0 => 'p7c',
            1 => 'p7m',
        ),
        'application/x-pkcs7-certreqresp' => 'p7r',
        'application/pkcs7-signature' => 'p7s',
        'application/pro_eng' =>
        array(
            0 => 'part',
            1 => 'prt',
        ),
        'text/pascal' => 'pas',
        'image/x-portable-bitmap' => 'pbm',
        'application/vnd.hp-pcl' => 'pcl',
        'application/x-pcl' => 'pcl',
        'image/x-pict' => 'pct',
        'image/x-pcx' => 'pcx',
        'chemical/x-pdb' =>
        array(
            0 => 'pdb',
            1 => 'xyz',
        ),
        'application/pdf' => 'pdf',
        'audio/make.my.funk' => 'pfunk',
        'image/x-portable-graymap' => 'pgm',
        'image/x-portable-greymap' => 'pgm',
        'image/pict' =>
        array(
            0 => 'pic',
            1 => 'pict',
        ),
        'application/x-newton-compatible-pkg' => 'pkg',
        'application/vnd.ms-pki.pko' => 'pko',
        'text/x-script.perl' => 'pl',
        'application/x-pixclscript' => 'plx',
        'image/x-xpixmap' =>
        array(
            0 => 'pm',
            1 => 'xpm',
        ),
        'text/x-script.perl-module' => 'pm',
        'application/x-pagemaker' =>
        array(
            0 => 'pm4',
            1 => 'pm5',
        ),
        'image/png' =>
        array(
            0 => 'png',
            1 => 'x-png',
        ),
        'application/x-portable-anymap' => 'pnm',
        'image/x-portable-anymap' => 'pnm',
        'application/mspowerpoint' =>
        array(
            0 => 'pot',
            1 => 'pps',
            2 => 'ppt',
            3 => 'ppz',
        ),
        'application/vnd.ms-powerpoint' =>
        array(
            0 => 'pot',
            1 => 'ppa',
            2 => 'pps',
            3 => 'ppt',
            4 => 'pwz',
        ),
        'model/x-pov' => 'pov',
        'image/x-portable-pixmap' => 'ppm',
        'application/powerpoint' => 'ppt',
        'application/x-mspowerpoint' => 'ppt',
        'application/x-freelance' => 'pre',
        'paleovu/x-pv' => 'pvu',
        'text/x-script.phyton' => 'py',
        'applicaiton/x-bytecode.python' => 'pyc',
        'audio/vnd.qcelp' => 'qcp',
        'image/x-quicktime' =>
        array(
            0 => 'qif',
            1 => 'qti',
            2 => 'qtif',
        ),
        'video/x-qtc' => 'qtc',
        'audio/x-pn-realaudio' =>
        array(
            0 => 'ra',
            1 => 'ram',
            2 => 'rm',
            3 => 'rmm',
            4 => 'rmp',
        ),
        'audio/x-pn-realaudio-plugin' =>
        array(
            0 => 'ra',
            1 => 'rmp',
            2 => 'rpm',
        ),
        'audio/x-realaudio' => 'ra',
        'application/x-cmu-raster' => 'ras',
        'image/cmu-raster' =>
        array(
            0 => 'ras',
            1 => 'rast',
        ),
        'image/x-cmu-raster' => 'ras',
        'text/x-script.rexx' => 'rexx',
        'image/vnd.rn-realflash' => 'rf',
        'image/x-rgb' => 'rgb',
        'application/vnd.rn-realmedia' => 'rm',
        'audio/mid' => 'rmi',
        'application/ringing-tones' => 'rng',
        'application/vnd.nokia.ringing-tone' => 'rng',
        'application/vnd.rn-realplayer' => 'rnx',
        'application/x-troff' =>
        array(
            0 => 'roff',
            1 => 't',
            2 => 'tr',
        ),
        'image/vnd.rn-realpix' => 'rp',
        'text/richtext' =>
        array(
            0 => 'rt',
            1 => 'rtf',
            2 => 'rtx',
        ),
        'text/vnd.rn-realtext' => 'rt',
        'application/rtf' =>
        array(
            0 => 'rtf',
            1 => 'rtx',
        ),
        'application/x-rtf' => 'rtf',
        'video/vnd.rn-realvideo' => 'rv',
        'audio/s3m' => 's3m',
        'application/x-tbook' =>
        array(
            0 => 'sbk',
            1 => 'tbk',
        ),
        'application/x-lotusscreencam' => 'scm',
        'text/x-script.guile' => 'scm',
        'text/x-script.scheme' => 'scm',
        'video/x-scm' => 'scm',
        'application/sdp' => 'sdp',
        'application/x-sdp' => 'sdp',
        'application/sounder' => 'sdr',
        'application/sea' => 'sea',
        'application/x-sea' => 'sea',
        'application/set' => 'set',
        'text/sgml' =>
        array(
            0 => 'sgm',
            1 => 'sgml',
        ),
        'text/x-sgml' =>
        array(
            0 => 'sgm',
            1 => 'sgml',
        ),
        'application/x-sh' => 'sh',
        'application/x-shar' =>
        array(
            0 => 'sh',
            1 => 'shar',
        ),
        'text/x-script.sh' => 'sh',
        'text/x-server-parsed-html' =>
        array(
            0 => 'shtml',
            1 => 'ssi',
        ),
        'audio/x-psid' => 'sid',
        'application/x-sit' => 'sit',
        'application/x-stuffit' => 'sit',
        'application/x-koan' =>
        array(
            0 => 'skd',
            1 => 'skm',
            2 => 'skp',
            3 => 'skt',
        ),
        'application/x-seelogo' => 'sl',
        'application/smil' =>
        array(
            0 => 'smi',
            1 => 'smil',
        ),
        'audio/x-adpcm' => 'snd',
        'application/solids' => 'sol',
        'application/x-pkcs7-certificates' => 'spc',
        'text/x-speech' =>
        array(
            0 => 'spc',
            1 => 'talk',
        ),
        'application/futuresplash' => 'spl',
        'application/x-sprite' =>
        array(
            0 => 'spr',
            1 => 'sprite',
        ),
        'application/x-wais-source' =>
        array(
            0 => 'src',
            1 => 'wsrc',
        ),
        'application/streamingmedia' => 'ssm',
        'application/vnd.ms-pki.certstore' => 'sst',
        'application/step' =>
        array(
            0 => 'step',
            1 => 'stp',
        ),
        'application/sla' => 'stl',
        'application/vnd.ms-pki.stl' => 'stl',
        'application/x-navistyle' => 'stl',
        'application/x-sv4cpio' => 'sv4cpio',
        'application/x-sv4crc' => 'sv4crc',
        'application/x-world' =>
        array(
            0 => 'svr',
            1 => 'wrl',
        ),
        'x-world/x-svr' => 'svr',
        'application/x-shockwave-flash' => 'swf',
        'application/x-tar' => 'tar',
        'application/toolbook' => 'tbk',
        'application/x-tcl' => 'tcl',
        'text/x-script.tcl' => 'tcl',
        'text/x-script.tcsh' => 'tcsh',
        'application/x-tex' => 'tex',
        'application/x-texinfo' =>
        array(
            0 => 'texi',
            1 => 'texinfo',
        ),
        'application/plain' => 'text',
        'application/gnutar' => 'tgz',
        'image/tiff' =>
        array(
            0 => 'tif',
            1 => 'tiff',
        ),
        'image/x-tiff' =>
        array(
            0 => 'tif',
            1 => 'tiff',
        ),
        'audio/tsp-audio' => 'tsi',
        'application/dsptype' => 'tsp',
        'audio/tsplayer' => 'tsp',
        'text/tab-separated-values' => 'tsv',
        'text/x-uil' => 'uil',
        'text/uri-list' =>
        array(
            0 => 'uni',
            1 => 'unis',
            2 => 'uri',
            3 => 'uris',
        ),
        'application/i-deas' => 'unv',
        'application/x-ustar' => 'ustar',
        'multipart/x-ustar' => 'ustar',
        'text/x-uuencode' =>
        array(
            0 => 'uu',
            1 => 'uue',
        ),
        'application/x-cdlink' => 'vcd',
        'text/x-vcalendar' => 'vcs',
        'application/vda' => 'vda',
        'video/vdo' => 'vdo',
        'application/groupwise' => 'vew',
        'video/vivo' =>
        array(
            0 => 'viv',
            1 => 'vivo',
        ),
        'video/vnd.vivo' =>
        array(
            0 => 'viv',
            1 => 'vivo',
        ),
        'application/vocaltec-media-desc' => 'vmd',
        'application/vocaltec-media-file' => 'vmf',
        'audio/voc' => 'voc',
        'audio/x-voc' => 'voc',
        'video/vosaic' => 'vos',
        'audio/voxware' => 'vox',
        'audio/x-twinvq-plugin' =>
        array(
            0 => 'vqe',
            1 => 'vql',
        ),
        'audio/x-twinvq' => 'vqf',
        'application/x-vrml' => 'vrml',
        'model/vrml' =>
        array(
            0 => 'vrml',
            1 => 'wrl',
            2 => 'wrz',
        ),
        'x-world/x-vrml' =>
        array(
            0 => 'vrml',
            1 => 'wrl',
            2 => 'wrz',
        ),
        'x-world/x-vrt' => 'vrt',
        'application/x-visio' =>
        array(
            0 => 'vsd',
            1 => 'vst',
            2 => 'vsw',
        ),
        'application/wordperfect6.0' =>
        array(
            0 => 'w60',
            1 => 'wp5',
        ),
        'application/wordperfect6.1' => 'w61',
        'audio/wav' => 'wav',
        'audio/x-wav' => 'wav',
        'application/x-qpro' => 'wb1',
        'image/vnd.wap.wbmp' => 'wbmp',
        'application/vnd.xara' => 'web',
        'application/x-123' => 'wk1',
        'windows/metafile' => 'wmf',
        'text/vnd.wap.wml' => 'wml',
        'application/vnd.wap.wmlc' => 'wmlc',
        'text/vnd.wap.wmlscript' => 'wmls',
        'application/vnd.wap.wmlscriptc' => 'wmlsc',
        'application/wordperfect' =>
        array(
            0 => 'wp',
            1 => 'wp5',
            2 => 'wp6',
            3 => 'wpd',
        ),
        'application/x-wpwin' => 'wpd',
        'application/x-lotus' => 'wq1',
        'application/mswrite' => 'wri',
        'application/x-wri' => 'wri',
        'text/scriplet' => 'wsc',
        'application/x-wintalk' => 'wtk',
        'image/x-xbitmap' => 'xbm',
        'image/x-xbm' => 'xbm',
        'image/xbm' => 'xbm',
        'video/x-amt-demorun' => 'xdr',
        'xgl/drawing' => 'xgz',
        'image/vnd.xiff' => 'xif',
        'application/excel' =>
        array(
            0 => 'xl',
            1 => 'xla',
            2 => 'xlb',
            3 => 'xlc',
            4 => 'xld',
            5 => 'xlk',
            6 => 'xll',
            7 => 'xlm',
            8 => 'xls',
            9 => 'xlt',
            10 => 'xlv',
            11 => 'xlw',
        ),
        'application/x-excel' =>
        array(
            0 => 'xla',
            1 => 'xlb',
            2 => 'xlc',
            3 => 'xld',
            4 => 'xlk',
            5 => 'xll',
            6 => 'xlm',
            7 => 'xls',
            8 => 'xlt',
            9 => 'xlv',
            10 => 'xlw',
        ),
        'application/x-msexcel' =>
        array(
            0 => 'xla',
            1 => 'xls',
            2 => 'xlw',
        ),
        'application/vnd.ms-excel' =>
        array(
            0 => 'xlb',
            1 => 'xlc',
            2 => 'xll',
            3 => 'xlm',
            4 => 'xls',
            5 => 'xlw',
        ),
        'audio/xm' => 'xm',
        'application/xml' => 'xml',
        'text/xml' => 'xml',
        'xgl/movie' => 'xmz',
        'application/x-vnd.ls-xpix' => 'xpix',
        'image/xpm' => 'xpm',
        'video/x-amt-showrun' => 'xsr',
        'image/x-xwd' => 'xwd',
        'image/x-xwindowdump' => 'xwd',
        'application/x-compress' => 'z',
        'application/x-zip-compressed' => 'zip',
        'application/zip' => 'zip',
        'multipart/x-zip' => 'zip',
        'text/x-script.zsh' => 'zsh',
    );
*/
    public function get_type_by_filename($filename) {
        $ext = substr(strrchr($filename, '.'), 1);
        $mime =  $this->get_type_by_ext($ext);
        if (is_array($mime)) {
            return $mime[0];
        }
        return $mime;
    }

    public function get_type_by_ext($ext) {

        $ext = strtolower($ext);
        if (!isset($this->type_by_ext[$ext])) {
            return false;
        }

        return $this->type_by_ext[$ext];
    }

/*
    public function get_ext_by_type($type) {

        $type = strtolower($type);
        if (!isset($this->ext_by_type[$type])) {
            return false;
        }

        return $this->ext_by_type[$type];
    }
*/
}
