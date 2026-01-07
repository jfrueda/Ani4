<?php

$ruta_raiz = "../";
$mimetypes = [
    [
      "Extension" => ".aac",
      "Kind of document" => "AAC audio",
      "MIME Type" => "audio/aac"
    ],
    [
      "Extension" => ".abw",
      "Kind of document" => "AbiWord document",
      "MIME Type" => "application/x-abiword"
    ],
    [
      "Extension" => ".arc",
      "Kind of document" => "Archive document (multiple files embedded)",
      "MIME Type" => "application/x-freearc"
    ],
    [
      "Extension" => ".avi",
      "Kind of document" => "AVI: Audio Video Interleave",
      "MIME Type" => "video/x-msvideo"
    ],
    [
      "Extension" => ".azw",
      "Kind of document" => "Amazon Kindle eBook format",
      "MIME Type" => "application/vnd.amazon.ebook"
    ],
    [
      "Extension" => ".bin",
      "Kind of document" => "Any kind of binary data",
      "MIME Type" => "application/octet-stream"
    ],
    [
      "Extension" => ".bmp",
      "Kind of document" => "Windows OS/2 Bitmap Graphics",
      "MIME Type" => "image/bmp"
    ],
    [
      "Extension" => ".bz",
      "Kind of document" => "BZip archive",
      "MIME Type" => "application/x-bzip"
    ],
    [
      "Extension" => ".bz2",
      "Kind of document" => "BZip2 archive",
      "MIME Type" => "application/x-bzip2"
    ],
    [
      "Extension" => ".csh",
      "Kind of document" => "C-Shell script",
      "MIME Type" => "application/x-csh"
    ],
    [
      "Extension" => ".css",
      "Kind of document" => "Cascading Style Sheets (CSS)",
      "MIME Type" => "text/css"
    ],
    [
      "Extension" => ".csv",
      "Kind of document" => "Comma-separated values (CSV)",
      "MIME Type" => "text/csv"
    ],
    [
      "Extension" => ".doc",
      "Kind of document" => "Microsoft Word",
      "MIME Type" => "application/msword"
    ],
    [
      "Extension" => ".docx",
      "Kind of document" => "Microsoft Word (OpenXML)",
      "MIME Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ],
    [
      "Extension" => ".eot",
      "Kind of document" => "MS Embedded OpenType fonts",
      "MIME Type" => "application/vnd.ms-fontobject"
    ],
    [
      "Extension" => ".epub",
      "Kind of document" => "Electronic publication (EPUB)",
      "MIME Type" => "application/epub+zip"
    ],
    [
      "Extension" => ".gz",
      "Kind of document" => "GZip Compressed Archive",
      "MIME Type" => "application/gzip"
    ],
    [
      "Extension" => ".gif",
      "Kind of document" => "Graphics Interchange Format (GIF)",
      "MIME Type" => "image/gif"
    ],
    [
      "Extension" => ".htm\n     .html",
      "Kind of document" => "HyperText Markup Language (HTML)",
      "MIME Type" => "text/html"
    ],
    [
      "Extension" => ".ico",
      "Kind of document" => "Icon format",
      "MIME Type" => "image/vnd.microsoft.icon"
    ],
    [
      "Extension" => ".ics",
      "Kind of document" => "iCalendar format",
      "MIME Type" => "text/calendar"
    ],
    [
      "Extension" => ".jar",
      "Kind of document" => "Java Archive (JAR)",
      "MIME Type" => "application/java-archive"
    ],
    [
      "Extension" => ".jpeg\n     .jpg",
      "Kind of document" => "JPEG images",
      "MIME Type" => "image/jpeg"
    ],
    [
      "Extension" => ".js",
      "Kind of document" => "JavaScript",
      "MIME Type" => "text/javascript, per the following specifications:            https://html.spec.whatwg.org/multipage/#scriptingLanguages      https://html.spec.whatwg.org/multipage/#dependencies:willful-violation      https://datatracker.ietf.org/doc/draft-ietf-dispatch-javascript-mjs/"
    ],
    [
      "Extension" => ".json",
      "Kind of document" => "JSON format",
      "MIME Type" => "application/json"
    ],
    [
      "Extension" => ".jsonld",
      "Kind of document" => "JSON-LD format",
      "MIME Type" => "application/ld+json"
    ],
    [
      "Extension" => ".mid\n     .midi",
      "Kind of document" => "Musical Instrument Digital Interface (MIDI)",
      "MIME Type" => "audio/midi audio/x-midi"
    ],
    [
      "Extension" => ".mjs",
      "Kind of document" => "JavaScript module",
      "MIME Type" => "text/javascript"
    ],
    [
      "Extension" => ".mp3",
      "Kind of document" => "MP3 audio",
      "MIME Type" => "audio/mpeg"
    ],
    [
      "Extension" => ".cda",
      "Kind of document" => "CD audio",
      "MIME Type" => "application/x-cdf"
    ],
    [
      "Extension" => ".mp4",
      "Kind of document" => "MP4 audio",
      "MIME Type" => "video/mp4"
    ],
    [
      "Extension" => ".mpeg",
      "Kind of document" => "MPEG Video",
      "MIME Type" => "video/mpeg"
    ],
    [
      "Extension" => ".mpkg",
      "Kind of document" => "Apple Installer Package",
      "MIME Type" => "application/vnd.apple.installer+xml"
    ],
    [
      "Extension" => ".odp",
      "Kind of document" => "OpenDocument presentation document",
      "MIME Type" => "application/vnd.oasis.opendocument.presentation"
    ],
    [
      "Extension" => ".ods",
      "Kind of document" => "OpenDocument spreadsheet document",
      "MIME Type" => "application/vnd.oasis.opendocument.spreadsheet"
    ],
    [
      "Extension" => ".odt",
      "Kind of document" => "OpenDocument text document",
      "MIME Type" => "application/vnd.oasis.opendocument.text"
    ],
    [
      "Extension" => ".oga",
      "Kind of document" => "OGG audio",
      "MIME Type" => "audio/ogg"
    ],
    [
      "Extension" => ".ogv",
      "Kind of document" => "OGG video",
      "MIME Type" => "video/ogg"
    ],
    [
      "Extension" => ".ogx",
      "Kind of document" => "OGG",
      "MIME Type" => "application/ogg"
    ],
    [
      "Extension" => ".opus",
      "Kind of document" => "Opus audio",
      "MIME Type" => "audio/opus"
    ],
    [
      "Extension" => ".otf",
      "Kind of document" => "OpenType font",
      "MIME Type" => "font/otf"
    ],
    [
      "Extension" => ".png",
      "Kind of document" => "Portable Network Graphics",
      "MIME Type" => "image/png"
    ],
    [
      "Extension" => ".pdf",
      "Kind of document" => "Adobe Portable Document Format (PDF)",
      "MIME Type" => "application/pdf"
    ],
    [
      "Extension" => ".php",
      "Kind of document" => "Hypertext Preprocessor (Personal Home Page)",
      "MIME Type" => "application/x-httpd-php"
    ],
    [
      "Extension" => ".ppt",
      "Kind of document" => "Microsoft PowerPoint",
      "MIME Type" => "application/vnd.ms-powerpoint"
    ],
    [
      "Extension" => ".pptx",
      "Kind of document" => "Microsoft PowerPoint (OpenXML)",
      "MIME Type" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
    ],
    [
      "Extension" => ".rar",
      "Kind of document" => "RAR archive",
      "MIME Type" => "application/vnd.rar"
    ],
    [
      "Extension" => ".rtf",
      "Kind of document" => "Rich Text Format (RTF)",
      "MIME Type" => "application/rtf"
    ],
    [
      "Extension" => ".sh",
      "Kind of document" => "Bourne shell script",
      "MIME Type" => "application/x-sh"
    ],
    [
      "Extension" => ".svg",
      "Kind of document" => "Scalable Vector Graphics (SVG)",
      "MIME Type" => "image/svg+xml"
    ],
    [
      "Extension" => ".swf",
      "Kind of document" => "Small web format (SWF) or Adobe Flash document",
      "MIME Type" => "application/x-shockwave-flash"
    ],
    [
      "Extension" => ".tar",
      "Kind of document" => "Tape Archive (TAR)",
      "MIME Type" => "application/x-tar"
    ],
    [
      "Extension" => ".tif\n     .tiff",
      "Kind of document" => "Tagged Image File Format (TIFF)",
      "MIME Type" => "image/tiff"
    ],
    [
      "Extension" => ".ts",
      "Kind of document" => "MPEG transport stream",
      "MIME Type" => "video/mp2t"
    ],
    [
      "Extension" => ".ttf",
      "Kind of document" => "TrueType Font",
      "MIME Type" => "font/ttf"
    ],
    [
      "Extension" => ".txt",
      "Kind of document" => "Text, (generally ASCII or ISO 8859-n)",
      "MIME Type" => "text/plain"
    ],
    [
      "Extension" => ".vsd",
      "Kind of document" => "Microsoft Visio",
      "MIME Type" => "application/vnd.visio"
    ],
    [
      "Extension" => ".wav",
      "Kind of document" => "Waveform Audio Format",
      "MIME Type" => "audio/wav"
    ],
    [
      "Extension" => ".weba",
      "Kind of document" => "WEBM audio",
      "MIME Type" => "audio/webm"
    ],
    [
      "Extension" => ".webm",
      "Kind of document" => "WEBM video",
      "MIME Type" => "video/webm"
    ],
    [
      "Extension" => ".webp",
      "Kind of document" => "WEBP image",
      "MIME Type" => "image/webp"
    ],
    [
      "Extension" => ".woff",
      "Kind of document" => "Web Open Font Format (WOFF)",
      "MIME Type" => "font/woff"
    ],
    [
      "Extension" => ".woff2",
      "Kind of document" => "Web Open Font Format (WOFF)",
      "MIME Type" => "font/woff2"
    ],
    [
      "Extension" => ".xhtml",
      "Kind of document" => "XHTML",
      "MIME Type" => "application/xhtml+xml"
    ],
    [
      "Extension" => ".xls",
      "Kind of document" => "Microsoft Excel",
      "MIME Type" => "application/vnd.ms-excel"
    ],
    [
      "Extension" => ".xlsx",
      "Kind of document" => "Microsoft Excel (OpenXML)",
      "MIME Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    ],
    [
      "Extension" => ".xml",
      "Kind of document" => "XML",
      "MIME Type" => "application/xml if not readable from casual users (RFC 3023, section 3)\n     text/xml if readable from casual users (RFC 3023, section 3)"
    ],
    [
      "Extension" => ".xul",
      "Kind of document" => "XUL",
      "MIME Type" => "application/vnd.mozilla.xul+xml"
    ],
    [
      "Extension" => ".zip",
      "Kind of document" => "ZIP archive",
      "MIME Type" => "application/zip"
    ],
    [
      "Extension" => ".3gp",
      "Kind of document" => "3GPP audio/video container",
      "MIME Type" => "video/3gpp\n     audio/3gpp if it doesn't contain video"
    ],
    [
      "Extension" => ".3g2",
      "Kind of document" => "3GPP2 audio/video container",
      "MIME Type" => "video/3gpp2\n     audio/3gpp2 if it doesn't contain video"
    ],
    [
      "Extension" => ".7z",
      "Kind of document" => "7-zip archive",
      "MIME Type" => "application/x-7z-compressed"
    ]
];
session_start();
//require $ruta_raiz.'kint.phar';
require_once($ruta_raiz."include/db/ConnectionHandler.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$params = session_name()."=".session_id()."&krd=$krd";
$file = $ruta_raiz.'/bodega/supercore/'.$num.$mimetypes[$ext]['Extension'];

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}