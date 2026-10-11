<?php

namespace HTMLValidator\Options;

enum ParserType: string
{
    /**
     * HTML parser.
     */
    case Html = 'html';

    /**
     * HTML parser (alias for {@see self::Html}).
     */
    case Html5 = 'html5';

    /**
     * XML parser, will not load external entities.
     */
    case Xml = 'xml';

    /**
     * XML parser, will load external entities.
     */
    case XmlDtd = 'xmldtd';
}
