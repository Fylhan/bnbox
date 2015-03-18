<?php
/*************************************************************************************
 * php.php
 * --------
 * Author: Nigel McNie (nigel@geshi.org)
 * Copyright: (c) 2004 Nigel McNie (http://qbnz.com/highlighter/)
 * Release Version: 1.0.7.20
 * Date Started: 2004/06/20
 *
 * PHP language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2004/11/25 (1.0.3)
 *  -  Added support for multiple object splitters
 *  -  Fixed &new problem
 * 2004/10/27 (1.0.2)
 *  -  Added URL support
 *  -  Added extra constants
 * 2004/08/05 (1.0.1)
 *  -  Added support for symbols
 * 2004/07/14 (1.0.0)
 *  -  First Release
 *
 * TODO (updated 2004/07/14)
 * -------------------------
 * * Make sure the last few function I may have missed
 *   (like eval()) are included for highlighting
 * * Split to several files - php4, php5 etc
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = array (
    'LANG_NAME' => 'PHP',
    'COMMENT_SINGLE' => array(1 => '//', 2 => '#'),
    'COMMENT_MULTI' => array('/*' => '*/'),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array("'", '"'),
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => array(
	    1 => array(
        ),
        2 => array(
            '[b]', '[gras]', '[i]', '[italic]',
            '[s]', '[barre]', '[barré]', '[edit]', '[u]', '[souligne]', '[souligné]', '[kdb]', '[couleur',
            '[bgcouleur', '[color', '[bgcolor', '[lien', '[url', '[email',
            '[bnemail', '[lien]', '[url]', '[email]',
            '[bnemail]', '[size', '[taille', '[tableau', '[ligne', '[cellule', '[tableau]', '[ligne]', '[cellule]', '[abbr', '[acronyme', '[acronym',
			'[centre', '[center', '[gauche', '[left', '[droite', '[right',
			'[justifie', '[justifié', '[justify', '[centre]', '[center]', '[gauche]', '[left]', '[droite]', '[right]',
			'[justifie]', '[justifié]', '[justify]', '[img]', '[image]', '[float', '[flottant',
			'[bordure', '[quote', '[citation', '[q', '[bible',
			'[c', '[code', '[bordure]', '[quote]', '[citation]', '[q]', '[bible]',
			'[c]', '[code]', '[info]', '[question]', '[dfn]', '[secret]',
			'[liste]', '[titre]', '[soustitre]', '[title]', '[subtitle]', '[---]', '[separation]', '[séparation]',
			'[media', '[média', '[media]', '[média]',
			'[/b]', '[/gras]', '[/i]', '[/italic]',
            '[/s]', '[/barre]', '[/barré]', '[/edit]', '[/u]', '[/souligne]', '[/souligné]', '[/kdb]', '[/couleur]',
            '[/bgcouleur]', '[/color]', '[/bgcolor]', '[/lien]', '[/url]', '[/email]',
            '[/bnemail]', '[/size]', '[/taille]', '[/tableau]', '[/ligne]', '[/cellule]', '[/abbr]', '[/acronyme]', '[/acronym]',
			'[/centre]', '[/center]', '[/gauche]', '[/left]', '[/droite]', '[/right]',
			'[/justifie]', '[/justifié]', '[/justify]', '[/img]', '[/image]', '[/float]', '[/flottant]',
			'[/bordure]', '[/quote]', '[/citation]', '[/q]', '[/bible]',
			'[/c]', '[/code]', '[/info]', '[/question]', '[/dfn]', '[/secret]',
			'[/liste]', '[/puce]', '[/titre]', '[/soustitre]', '[/title]', '[/subtitle]', '[/media]', '[/média]',
			'[balise]', '[/balise]', '<balise>', '</balise>',
			'<b>', '<gras>', '<i>', '<italic>',
            '<s>', '<barre>', '<barré>', '<edit>', '<u>', '<souligne>', '<souligné>', '<kdb>', '<couleur',
            '<bgcouleur', '<color', '<bgcolor', '<lien', '<url', '<email',
            '<bnemail', '<lien>', '<url>', '<email>',
            '<bnemail>', '<size', '<taille', '<tableau', '<ligne', '<cellule', '<tableau>', '<ligne>', '<cellule>',
			'<abbr', '<acronyme', '<acronym',
			'<centre', '<center', '<gauche', '<left', '<droite', '<right',
			'<justifie', '<justifié', '<justify', '<centre>', '<center>', '<gauche>', '<left>', '<droite>', '<right>',
			'<justifie>', '<justifié>', '<justify>', '<img>', '<image>', '<float', '<flottant',
			'<bordure', '<quote', '<citation', '<q', '<bible',
			'<c', '<code', '<bordure>', '<quote>', '<citation>', '<q>', '<bible>',
			'<c>', '<code>', '<info>', '<question>', '<dfn>', '<secret>',
			'<liste>', '<titre>', '<soustitre>', '<title>', '<subtitle>', '<--->', '<separation>', '<séparation>',
			'<media', '<média', '<media>', '<média>',
			'</b>', '</gras>', '</i>', '</italic>',
            '</s>', '</barre>', '</barré>', '</edit>', '</u>', '</souligne>', '</souligné>', '</kdb>', '</couleur>',
            '</bgcouleur>', '</color>', '</bgcolor>', '</lien>', '</url>', '</email>',
            '</bnemail>', '</size>', '</taille>', '</tableau>', '</ligne>', '</cellule>', '</abbr>', '</acronyme>', '</acronym>',
			'</centre>', '</center>', '</gauche>', '</left>', '</droite>', '</right>',
			'</justifie>', '</justifié>', '</justify>', '</img>', '</image>', '</float>', '</flottant>',
			'</bordure>', '</quote>', '</citation>', '</q>', '</bible>',
			'</c>', '</code>', '</info>', '</question>', '</dfn>', '</secret>',
			'</liste>', '</puce>', '</titre>', '</soustitre>', '</title>', '</subtitle>', '</media>', '</média>',
            ),
        3 => array(
            'titre', 'title', 'size', 'taille', 'vide'
            )
        ),
    'SYMBOLS' => array(
        '/', '='
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        ),
    'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color: #b1b100;',
			2 => 'color: #000000; font-weight: bold;',
			3 => 'color: #000066;'
			),
		'COMMENTS' => array(
			'MULTI' => 'color: #808080; font-style: italic;'
			),
		'ESCAPE_CHAR' => array(
			0 => 'color: #000099; font-weight: bold;'
			),
		'BRACKETS' => array(
			0 => 'color: #66cc66;'
			),
		'STRINGS' => array(
			0 => 'color: #ff0000;'
			),
		'NUMBERS' => array(
			0 => 'color: #cc66cc;'
			),
		'METHODS' => array(
			),
		'SYMBOLS' => array(
			0 => 'color: #66cc66;'
			),
		'SCRIPT' => array(
			0 => 'color: #00bbdd;',
			1 => 'color: #ddbb00;',
			2 => 'color: #009900;'
			),
		'REGEXPS' => array(
			)
		),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => 'http://www.la-bnbox.fr/{FNAME}',
        4 => ''
        ),
    'OOLANG' => false,
	'OBJECT_SPLITTERS' => array(
		),
	'REGEXPS' => array(
		),
    'STRICT_MODE_APPLIES' => GESHI_ALWAYS,
	'SCRIPT_DELIMITERS' => array(
		0 => array(
			'[' => ']'
			),
		1 => array(
			'<' => '>'
			),
	),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		0 => true,
		1 => true,
        ),
    'TAB_WIDTH' => 4
);

?>
