<?php
/**
 * Element: Geo
 * Displays a multiselectbox of geo locations
 *
 * @package         NoNumber Framework
 * @version         14.9.11
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

class JFormFieldNN_Geo extends JFormField
{
	public $type = 'Geo';
	private $params = null;
	private $db = null;

	protected function getInput()
	{
		$this->params = $this->element->attributes();
		$this->db = JFactory::getDbo();

		if (!is_array($this->value))
		{
			$this->value = explode(',', $this->value);
		}

		$group = $this->get('group', 'countries');
		$options = array();
		foreach ($this->{$group} as $key => $val)
		{
			if (!$val)
			{
				$options[] = JHtml::_('select.option', '-', '&nbsp;', 'value', 'text', true);
			}
			else if ($key['0'] == '-')
			{
				$options[] = JHtml::_('select.option', '-', $val, 'value', 'text', true);
			}
			else
			{
				$val = NNText::prepareSelectItem($val);
				$options[] = JHtml::_('select.option', $key, $val);
			}
		}

		$size = (int) $this->get('size');
		$multiple = $this->get('multiple');

		require_once JPATH_PLUGINS . '/system/nnframework/helpers/html.php';

		return nnHtml::selectlistsimple($options, $this->name, $this->value, $this->id, $size, $multiple);
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}

	public $continents = array(
		'AF' => 'Africa',
		'AS' => 'Asia',
		'EU' => 'Europe',
		'NA' => 'North America',
		'SA' => 'South America',
		'OC' => 'Oceania',
		'AN' => 'Antarctica'
	);

	public $countries = array(
		'AF' => 'Afghanistan',
		'AX' => 'Aland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, The Democratic Republic of the',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote d\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands (Malvinas)',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran, Islamic Republic of',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea, Democratic People\'s Republic of',
		'KR' => 'Korea, Republic of',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia, Federated States of',
		'MD' => 'Moldova, Republic of',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania, United Republic of',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	// Region codes taken from https://documentation.snoobi.com/region-codes
	public $regions = array(
		'--AU'  => '', '-AU' => 'Australia',
		'AU-01' => 'Australia: Australian Capital Territory',
		'AU-02' => 'Australia: New South Wales',
		'AU-03' => 'Australia: Northern Territory',
		'AU-04' => 'Australia: Queensland',
		'AU-05' => 'Australia: South Australia',
		'AU-06' => 'Australia: Tasmania',
		'AU-07' => 'Australia: Victoria',
		'AU-08' => 'Australia: Western Australia',

		'--BR'  => '', '-BR' => 'Brazil',
		'BR-01' => 'Brazil: Acre',
		'BR-02' => 'Brazil: Alagoas',
		'BR-03' => 'Brazil: Amapa',
		'BR-04' => 'Brazil: Amazonas',
		'BR-05' => 'Brazil: Bahia',
		'BR-06' => 'Brazil: Ceara',
		'BR-07' => 'Brazil: Distrito Federal',
		'BR-08' => 'Brazil: Espirito Santo',
		'BR-29' => 'Brazil: Goias',
		'BR-13' => 'Brazil: Maranhao',
		'BR-11' => 'Brazil: Mato Grosso do Sul',
		'BR-14' => 'Brazil: Mato Grosso',
		'BR-15' => 'Brazil: Minas Gerais',
		'BR-16' => 'Brazil: Para',
		'BR-17' => 'Brazil: Paraiba',
		'BR-18' => 'Brazil: Parana',
		'BR-30' => 'Brazil: Pernambuco',
		'BR-20' => 'Brazil: Piaui',
		'BR-21' => 'Brazil: Rio de Janeiro',
		'BR-22' => 'Brazil: Rio Grande do Norte',
		'BR-23' => 'Brazil: Rio Grande do Sul',
		'BR-24' => 'Brazil: Rondonia',
		'BR-25' => 'Brazil: Roraima',
		'BR-26' => 'Brazil: Santa Catarina',
		'BR-27' => 'Brazil: Sao Paulo',
		'BR-28' => 'Brazil: Sergipe',
		'BR-31' => 'Brazil: Tocantins',

		'--CA'  => '', '-CA' => 'Canada',
		'CA-AB' => 'Canada: Alberta',
		'CA-BC' => 'Canada: British Columbia',
		'CA-MB' => 'Canada: Manitoba',
		'CA-NB' => 'Canada: New Brunswick',
		'CA-NL' => 'Canada: Newfoundland',
		'CA-NT' => 'Canada: Northwest Territories',
		'CA-NS' => 'Canada: Nova Scotia',
		'CA-NU' => 'Canada: Nunavut',
		'CA-ON' => 'Canada: Ontario',
		'CA-PE' => 'Canada: Prince Edward Island',
		'CA-QC' => 'Canada: Quebec',
		'CA-SK' => 'Canada: Saskatchewan',
		'CA-YT' => 'Canada: Yukon Territory',

		'--CN'  => '', '-CN' => 'China',
		'CN-01' => 'China: Anhui',
		'CN-22' => 'China: Beijing',
		'CN-33' => 'China: Chongqing',
		'CN-07' => 'China: Fujian',
		'CN-15' => 'China: Gansu',
		'CN-30' => 'China: Guangdong',
		'CN-16' => 'China: Guangxi',
		'CN-18' => 'China: Guizhou',
		'CN-31' => 'China: Hainan',
		'CN-10' => 'China: Hebei',
		'CN-08' => 'China: Heilongjiang',
		'CN-09' => 'China: Henan',
		'CN-12' => 'China: Hubei',
		'CN-11' => 'China: Hunan',
		'CN-04' => 'China: Jiangsu',
		'CN-03' => 'China: Jiangxi',
		'CN-05' => 'China: Jilin',
		'CN-19' => 'China: Liaoning',
		'CN-20' => 'China: Nei Mongol',
		'CN-21' => 'China: Ningxia',
		'CN-06' => 'China: Qinghai',
		'CN-26' => 'China: Shaanxi',
		'CN-25' => 'China: Shandong',
		'CN-23' => 'China: Shanghai',
		'CN-24' => 'China: Shanxi',
		'CN-32' => 'China: Sichuan',
		'CN-28' => 'China: Tianjin',
		'CN-13' => 'China: Xinjiang',
		'CN-14' => 'China: Xizang',
		'CN-29' => 'China: Yunnan',
		'CN-02' => 'China: Zhejiang',

		'--EG'  => '', '-EG' => 'Egypt',
		'EG-01' => 'Egypt: Ad Daqahliyah',
		'EG-02' => 'Egypt: Al Bahr al Ahmar',
		'EG-03' => 'Egypt: Al Buhayrah',
		'EG-04' => 'Egypt: Al Fayyum',
		'EG-05' => 'Egypt: Al Gharbiyah',
		'EG-06' => 'Egypt: Al Iskandariyah',
		'EG-07' => 'Egypt: Al Isma\'iliyah',
		'EG-08' => 'Egypt: Al Jizah',
		'EG-09' => 'Egypt: Al Minufiyah',
		'EG-10' => 'Egypt: Al Minya',
		'EG-11' => 'Egypt: Al Qahirah',
		'EG-12' => 'Egypt: Al Qalyubiyah',
		'EG-13' => 'Egypt: Al Wadi al Jadid',
		'EG-15' => 'Egypt: As Suways',
		'EG-14' => 'Egypt: Ash Sharqiyah',
		'EG-16' => 'Egypt: Aswan',
		'EG-17' => 'Egypt: Asyut',
		'EG-18' => 'Egypt: Bani Suwayf',
		'EG-19' => 'Egypt: Bur Sa\'id',
		'EG-20' => 'Egypt: Dumyat',
		'EG-26' => 'Egypt: Janub Sina\'',
		'EG-21' => 'Egypt: Kafr ash Shaykh',
		'EG-22' => 'Egypt: Matruh',
		'EG-23' => 'Egypt: Qina',
		'EG-27' => 'Egypt: Shamal Sina\'',
		'EG-24' => 'Egypt: Suhaj',

		'--FR'  => '', '-FR' => 'France',
		'FR-C1' => 'France: Alsace',
		'FR-97' => 'France: Aquitaine',
		'FR-98' => 'France: Auvergne',
		'FR-99' => 'France: Basse-Normandie',
		'FR-A1' => 'France: Bourgogne',
		'FR-A2' => 'France: Bretagne',
		'FR-A3' => 'France: Centre',
		'FR-A4' => 'France: Champagne-Ardenne',
		'FR-A5' => 'France: Corse',
		'FR-A6' => 'France: Franche-Comte',
		'FR-A7' => 'France: Haute-Normandie',
		'FR-A8' => 'France: Ile-de-France',
		'FR-A9' => 'France: Languedoc-Roussillon',
		'FR-B1' => 'France: Limousin',
		'FR-B2' => 'France: Lorraine',
		'FR-B3' => 'France: Midi-Pyrenees',
		'FR-B4' => 'France: Nord-Pas-de-Calais',
		'FR-B5' => 'France: Pays de la Loire',
		'FR-B6' => 'France: Picardie',
		'FR-B7' => 'France: Poitou-Charentes',
		'FR-B8' => 'France: Provence-Alpes-Cote d\'Azur',
		'FR-B9' => 'France: Rhone-Alpes',

		'--DE'  => '', '-DE' => 'Germany',
		'DE-01' => 'Germany: Baden-Württemberg',
		'DE-02' => 'Germany: Bayern',
		'DE-16' => 'Germany: Berlin',
		'DE-11' => 'Germany: Brandenburg',
		'DE-03' => 'Germany: Bremen',
		'DE-04' => 'Germany: Hamburg',
		'DE-05' => 'Germany: Hessen',
		'DE-12' => 'Germany: Mecklenburg-Vorpommern',
		'DE-06' => 'Germany: Niedersachsen',
		'DE-07' => 'Germany: Nordrhein-Westfalen',
		'DE-08' => 'Germany: Rheinland-Pfalz',
		'DE-09' => 'Germany: Saarland',
		'DE-13' => 'Germany: Sachsen',
		'DE-14' => 'Germany: Sachsen-Anhalt',
		'DE-10' => 'Germany: Schleswig-Holstein',
		'DE-15' => 'Germany: Thuringen',

		'--IN'  => '', '-IN' => 'India',
		'IN-01' => 'India: Andaman and Nicobar Islands',
		'IN-02' => 'India: Andhra Pradesh',
		'IN-30' => 'India: Arunachal Pradesh',
		'IN-03' => 'India: Assam',
		'IN-34' => 'India: Bihar',
		'IN-05' => 'India: Chandigarh',
		'IN-37' => 'India: Chhattisgarh',
		'IN-06' => 'India: Dadra and Nagar Haveli',
		'IN-32' => 'India: Daman and Diu',
		'IN-07' => 'India: Delhi',
		'IN-33' => 'India: Goa',
		'IN-09' => 'India: Gujarat',
		'IN-10' => 'India: Haryana',
		'IN-11' => 'India: Himachal Pradesh',
		'IN-12' => 'India: Jammu and Kashmir',
		'IN-38' => 'India: Jharkhand',
		'IN-19' => 'India: Karnataka',
		'IN-13' => 'India: Kerala',
		'IN-14' => 'India: Lakshadweep',
		'IN-35' => 'India: Madhya Pradesh',
		'IN-16' => 'India: Maharashtra',
		'IN-17' => 'India: Manipur',
		'IN-18' => 'India: Meghalaya',
		'IN-31' => 'India: Mizoram',
		'IN-20' => 'India: Nagaland',
		'IN-21' => 'India: Orissa',
		'IN-22' => 'India: Pondicherry',
		'IN-23' => 'India: Punjab',
		'IN-24' => 'India: Rajasthan',
		'IN-29' => 'India: Sikkim',
		'IN-25' => 'India: Tamil Nadu',
		'IN-26' => 'India: Tripura',
		'IN-36' => 'India: Uttar Pradesh',
		'IN-39' => 'India: Uttaranchal',
		'IN-28' => 'India: West Bengal',

		'--ID'  => '', '-ID' => 'Indonesia',
		'ID-01' => 'Indonesia: Aceh',
		'ID-02' => 'Indonesia: Bali',
		'ID-33' => 'Indonesia: Banten',
		'ID-03' => 'Indonesia: Bengkulu',
		'ID-34' => 'Indonesia: Gorontalo',
		'ID-04' => 'Indonesia: Jakarta Raya',
		'ID-05' => 'Indonesia: Jambi',
		'ID-30' => 'Indonesia: Jawa Barat',
		'ID-07' => 'Indonesia: Jawa Tengah',
		'ID-08' => 'Indonesia: Jawa Timur',
		'ID-11' => 'Indonesia: Kalimantan Barat',
		'ID-12' => 'Indonesia: Kalimantan Selatan',
		'ID-13' => 'Indonesia: Kalimantan Tengah',
		'ID-14' => 'Indonesia: Kalimantan Timur',
		'ID-35' => 'Indonesia: Kepulauan Bangka Belitung',
		'ID-15' => 'Indonesia: Lampung',
		'ID-29' => 'Indonesia: Maluku Utara',
		'ID-28' => 'Indonesia: Maluku',
		'ID-17' => 'Indonesia: Nusa Tenggara Barat',
		'ID-18' => 'Indonesia: Nusa Tenggara Timur',
		'ID-09' => 'Indonesia: Papua',
		'ID-19' => 'Indonesia: Riau',
		'ID-20' => 'Indonesia: Sulawesi Selatan',
		'ID-21' => 'Indonesia: Sulawesi Tengah',
		'ID-22' => 'Indonesia: Sulawesi Tenggara',
		'ID-31' => 'Indonesia: Sulawesi Utara',
		'ID-24' => 'Indonesia: Sumatera Barat',
		'ID-25' => 'Indonesia: Sumatera Selatan',
		'ID-32' => 'Indonesia: Sumatera Selatan',
		'ID-26' => 'Indonesia: Sumatera Utara',
		'ID-10' => 'Indonesia: Yogyakarta',

		'--IT'  => '', '-IT' => 'Italy',
		'IT-01' => 'Italy: Abruzzi',
		'IT-02' => 'Italy: Basilicata',
		'IT-03' => 'Italy: Calabria',
		'IT-04' => 'Italy: Campania',
		'IT-05' => 'Italy: Emilia-Romagna',
		'IT-06' => 'Italy: Friuli-Venezia Giulia',
		'IT-07' => 'Italy: Lazio',
		'IT-08' => 'Italy: Liguria',
		'IT-09' => 'Italy: Lombardia',
		'IT-10' => 'Italy: Marche',
		'IT-11' => 'Italy: Molise',
		'IT-12' => 'Italy: Piemonte',
		'IT-13' => 'Italy: Puglia',
		'IT-14' => 'Italy: Sardegna',
		'IT-15' => 'Italy: Sicilia',
		'IT-16' => 'Italy: Toscana',
		'IT-17' => 'Italy: Trentino-Alto Adige',
		'IT-18' => 'Italy: Umbria',
		'IT-19' => 'Italy: Valle d\'Aosta',
		'IT-20' => 'Italy: Veneto',

		'--JP'  => '', '-JP' => 'Japan',
		'JP-01' => 'Japan: Aichi',
		'JP-02' => 'Japan: Akita',
		'JP-03' => 'Japan: Aomori',
		'JP-04' => 'Japan: Chiba',
		'JP-05' => 'Japan: Ehime',
		'JP-06' => 'Japan: Fukui',
		'JP-07' => 'Japan: Fukuoka',
		'JP-08' => 'Japan: Fukushima',
		'JP-09' => 'Japan: Gifu',
		'JP-10' => 'Japan: Gumma',
		'JP-11' => 'Japan: Hiroshima',
		'JP-12' => 'Japan: Hokkaido',
		'JP-13' => 'Japan: Hyogo',
		'JP-14' => 'Japan: Ibaraki',
		'JP-15' => 'Japan: Ishikawa',
		'JP-16' => 'Japan: Iwate',
		'JP-17' => 'Japan: Kagawa',
		'JP-18' => 'Japan: Kagoshima',
		'JP-19' => 'Japan: Kanagawa',
		'JP-20' => 'Japan: Kochi',
		'JP-21' => 'Japan: Kumamoto',
		'JP-22' => 'Japan: Kyoto',
		'JP-23' => 'Japan: Mie',
		'JP-24' => 'Japan: Miyagi',
		'JP-25' => 'Japan: Miyazaki',
		'JP-26' => 'Japan: Nagano',
		'JP-27' => 'Japan: Nagasaki',
		'JP-28' => 'Japan: Nara',
		'JP-29' => 'Japan: Niigata',
		'JP-30' => 'Japan: Oita',
		'JP-31' => 'Japan: Okayama',
		'JP-47' => 'Japan: Okinawa',
		'JP-32' => 'Japan: Osaka',
		'JP-33' => 'Japan: Saga',
		'JP-34' => 'Japan: Saitama',
		'JP-35' => 'Japan: Shiga',
		'JP-36' => 'Japan: Shimane',
		'JP-37' => 'Japan: Shizuoka',
		'JP-38' => 'Japan: Tochigi',
		'JP-39' => 'Japan: Tokushima',
		'JP-40' => 'Japan: Tokyo',
		'JP-41' => 'Japan: Tottori',
		'JP-42' => 'Japan: Toyama',
		'JP-43' => 'Japan: Wakayama',
		'JP-44' => 'Japan: Yamagata',
		'JP-45' => 'Japan: Yamaguchi',
		'JP-46' => 'Japan: Yamanashi',

		'--MX'  => '', '-MX' => 'Mexico',
		'MX-01' => 'Mexico: Aguascalientes',
		'MX-03' => 'Mexico: Baja California Sur',
		'MX-02' => 'Mexico: Baja California',
		'MX-04' => 'Mexico: Campeche',
		'MX-05' => 'Mexico: Chiapas',
		'MX-06' => 'Mexico: Chihuahua',
		'MX-07' => 'Mexico: Coahuila de Zaragoza',
		'MX-08' => 'Mexico: Colima',
		'MX-09' => 'Mexico: Distrito Federal',
		'MX-10' => 'Mexico: Durango',
		'MX-11' => 'Mexico: Guanajuato',
		'MX-12' => 'Mexico: Guerrero',
		'MX-13' => 'Mexico: Hidalgo',
		'MX-14' => 'Mexico: Jalisco',
		'MX-15' => 'Mexico: Mexico',
		'MX-16' => 'Mexico: Michoacan de Ocampo',
		'MX-17' => 'Mexico: Morelos',
		'MX-18' => 'Mexico: Nayarit',
		'MX-19' => 'Mexico: Nuevo Leon',
		'MX-20' => 'Mexico: Oaxaca',
		'MX-21' => 'Mexico: Puebla',
		'MX-22' => 'Mexico: Queretaro de Arteaga',
		'MX-23' => 'Mexico: Quintana Roo',
		'MX-24' => 'Mexico: San Luis Potosi',
		'MX-25' => 'Mexico: Sinaloa',
		'MX-26' => 'Mexico: Sonora',
		'MX-27' => 'Mexico: Tabasco',
		'MX-28' => 'Mexico: Tamaulipas',
		'MX-29' => 'Mexico: Tlaxcala',
		'MX-30' => 'Mexico: Veracruz-Llave',
		'MX-31' => 'Mexico: Yucatan',
		'MX-32' => 'Mexico: Zacatecas',

		'--NL'  => '', '-NL' => 'Netherlands',
		'NL-01' => 'Netherlands: Drenthe',
		'NL-16' => 'Netherlands: Flevoland',
		'NL-02' => 'Netherlands: Friesland',
		'NL-03' => 'Netherlands: Gelderland',
		'NL-04' => 'Netherlands: Groningen',
		'NL-05' => 'Netherlands: Limburg',
		'NL-06' => 'Netherlands: Noord-Brabant',
		'NL-07' => 'Netherlands: Noord-Holland',
		'NL-15' => 'Netherlands: Overijssel',
		'NL-09' => 'Netherlands: Utrecht',
		'NL-10' => 'Netherlands: Zeeland',
		'NL-11' => 'Netherlands: Zuid-Holland',

		'--NG'  => '', '-NG' => 'Nigeria',
		'NG-45' => 'Nigeria: Abia',
		'NG-11' => 'Nigeria: Abuja Capital Territory',
		'NG-35' => 'Nigeria: Adamawa',
		'NG-21' => 'Nigeria: Akwa Ibom',
		'NG-25' => 'Nigeria: Anambra',
		'NG-46' => 'Nigeria: Bauchi',
		'NG-52' => 'Nigeria: Bayelsa',
		'NG-26' => 'Nigeria: Benue',
		'NG-27' => 'Nigeria: Borno',
		'NG-22' => 'Nigeria: Cross River',
		'NG-36' => 'Nigeria: Delta',
		'NG-53' => 'Nigeria: Ebonyi',
		'NG-37' => 'Nigeria: Edo',
		'NG-54' => 'Nigeria: Ekiti',
		'NG-47' => 'Nigeria: Enugu',
		'NG-55' => 'Nigeria: Gombe',
		'NG-28' => 'Nigeria: Imo',
		'NG-39' => 'Nigeria: Jigawa',
		'NG-23' => 'Nigeria: Kaduna',
		'NG-29' => 'Nigeria: Kano',
		'NG-24' => 'Nigeria: Katsina',
		'NG-40' => 'Nigeria: Kebbi',
		'NG-41' => 'Nigeria: Kogi',
		'NG-30' => 'Nigeria: Kwara',
		'NG-05' => 'Nigeria: Lagos',
		'NG-56' => 'Nigeria: Nassarawa',
		'NG-31' => 'Nigeria: Niger',
		'NG-16' => 'Nigeria: Ogun',
		'NG-48' => 'Nigeria: Ondo',
		'NG-42' => 'Nigeria: Osun',
		'NG-32' => 'Nigeria: Oyo',
		'NG-49' => 'Nigeria: Plateau',
		'NG-50' => 'Nigeria: Rivers',
		'NG-51' => 'Nigeria: Sokoto',
		'NG-43' => 'Nigeria: Taraba',
		'NG-44' => 'Nigeria: Yobe',
		'NG-57' => 'Nigeria: Zamfara',

		'--PH'  => '', '-PH' => 'Philippines',
		'PH-01' => 'Philippines: Abra',
		'PH-02' => 'Philippines: Agusan del Norte',
		'PH-03' => 'Philippines: Agusan del Sur',
		'PH-04' => 'Philippines: Aklan',
		'PH-05' => 'Philippines: Albay',
		'PH-A1' => 'Philippines: Angeles',
		'PH-06' => 'Philippines: Antique',
		'PH-G8' => 'Philippines: Aurora',
		'PH-A2' => 'Philippines: Bacolod',
		'PH-A3' => 'Philippines: Bago',
		'PH-A4' => 'Philippines: Baguio',
		'PH-A5' => 'Philippines: Bais',
		'PH-A6' => 'Philippines: Basilan City',
		'PH-22' => 'Philippines: Basilan',
		'PH-07' => 'Philippines: Bataan',
		'PH-08' => 'Philippines: Batanes',
		'PH-A7' => 'Philippines: Batangas City',
		'PH-09' => 'Philippines: Batangas',
		'PH-10' => 'Philippines: Benguet',
		'PH-11' => 'Philippines: Bohol',
		'PH-12' => 'Philippines: Bukidnon',
		'PH-13' => 'Philippines: Bulacan',
		'PH-A8' => 'Philippines: Butuan',
		'PH-A9' => 'Philippines: Cabanatuan',
		'PH-B1' => 'Philippines: Cadiz',
		'PH-B2' => 'Philippines: Cagayan de Oro',
		'PH-14' => 'Philippines: Cagayan',
		'PH-B3' => 'Philippines: Calbayog',
		'PH-B4' => 'Philippines: Caloocan',
		'PH-15' => 'Philippines: Camarines Norte',
		'PH-16' => 'Philippines: Camarines Sur',
		'PH-17' => 'Philippines: Camiguin',
		'PH-B5' => 'Philippines: Canlaon',
		'PH-18' => 'Philippines: Capiz',
		'PH-19' => 'Philippines: Catanduanes',
		'PH-B6' => 'Philippines: Cavite City',
		'PH-20' => 'Philippines: Cavite',
		'PH-B7' => 'Philippines: Cebu City',
		'PH-21' => 'Philippines: Cebu',
		'PH-B8' => 'Philippines: Cotabato',
		'PH-B9' => 'Philippines: Dagupan',
		'PH-C1' => 'Philippines: Danao',
		'PH-C2' => 'Philippines: Dapitan',
		'PH-C3' => 'Philippines: Davao City',
		'PH-25' => 'Philippines: Davao del Sur',
		'PH-26' => 'Philippines: Davao Oriental',
		'PH-24' => 'Philippines: Davao',
		'PH-C4' => 'Philippines: Dipolog',
		'PH-C5' => 'Philippines: Dumaguete',
		'PH-23' => 'Philippines: Eastern Samar',
		'PH-C6' => 'Philippines: General Santos',
		'PH-C7' => 'Philippines: Gingoog',
		'PH-27' => 'Philippines: Ifugao',
		'PH-C8' => 'Philippines: Iligan',
		'PH-28' => 'Philippines: Ilocos Norte',
		'PH-29' => 'Philippines: Ilocos Sur',
		'PH-C9' => 'Philippines: Iloilo City',
		'PH-30' => 'Philippines: Iloilo',
		'PH-D1' => 'Philippines: Iriga',
		'PH-31' => 'Philippines: Isabela',
		'PH-32' => 'Philippines: Kalinga-Apayao',
		'PH-D2' => 'Philippines: La Carlota',
		'PH-36' => 'Philippines: La Union',
		'PH-33' => 'Philippines: Laguna',
		'PH-34' => 'Philippines: Lanao del Norte',
		'PH-35' => 'Philippines: Lanao del Sur',
		'PH-D3' => 'Philippines: Laoag',
		'PH-D4' => 'Philippines: Lapu-Lapu',
		'PH-D5' => 'Philippines: Legaspi',
		'PH-37' => 'Philippines: Leyte',
		'PH-D6' => 'Philippines: Lipa',
		'PH-D7' => 'Philippines: Lucena',
		'PH-56' => 'Philippines: Maguindanao',
		'PH-D8' => 'Philippines: Mandaue',
		'PH-D9' => 'Philippines: Manila',
		'PH-E1' => 'Philippines: Marawi',
		'PH-38' => 'Philippines: Marinduque',
		'PH-39' => 'Philippines: Masbate',
		'PH-40' => 'Philippines: Mindoro Occidental',
		'PH-41' => 'Philippines: Mindoro Oriental',
		'PH-42' => 'Philippines: Misamis Occidental',
		'PH-43' => 'Philippines: Misamis Oriental',
		'PH-44' => 'Philippines: Mountain',
		'PH-E2' => 'Philippines: Naga',
		'PH-H3' => 'Philippines: Negros Occidental',
		'PH-46' => 'Philippines: Negros Oriental',
		'PH-57' => 'Philippines: North Cotabato',
		'PH-67' => 'Philippines: Northern Samar',
		'PH-47' => 'Philippines: Nueva Ecija',
		'PH-48' => 'Philippines: Nueva Vizcaya',
		'PH-E3' => 'Philippines: Olongapo',
		'PH-E4' => 'Philippines: Ormoc',
		'PH-E5' => 'Philippines: Oroquieta',
		'PH-E6' => 'Philippines: Ozamis',
		'PH-E7' => 'Philippines: Pagadian',
		'PH-49' => 'Philippines: Palawan',
		'PH-E8' => 'Philippines: Palayan',
		'PH-50' => 'Philippines: Pampanga',
		'PH-51' => 'Philippines: Pangasinan',
		'PH-E9' => 'Philippines: Pasay',
		'PH-F1' => 'Philippines: Puerto Princesa',
		'PH-F2' => 'Philippines: Quezon City',
		'PH-H2' => 'Philippines: Quezon',
		'PH-68' => 'Philippines: Quirino',
		'PH-53' => 'Philippines: Rizal',
		'PH-54' => 'Philippines: Romblon',
		'PH-F3' => 'Philippines: Roxas',
		'PH-55' => 'Philippines: Samar',
		'PH-F4' => 'Philippines: San Carlos',
		'PH-F5' => 'Philippines: San Carlos',
		'PH-F6' => 'Philippines: San Jose',
		'PH-F7' => 'Philippines: San Pablo',
		'PH-F8' => 'Philippines: Silay',
		'PH-69' => 'Philippines: Siquijor',
		'PH-58' => 'Philippines: Sorsogon',
		'PH-70' => 'Philippines: South Cotabato',
		'PH-59' => 'Philippines: Southern Leyte',
		'PH-71' => 'Philippines: Sultan Kudarat',
		'PH-60' => 'Philippines: Sulu',
		'PH-61' => 'Philippines: Surigao del Norte',
		'PH-62' => 'Philippines: Surigao del Sur',
		'PH-F9' => 'Philippines: Surigao',
		'PH-G1' => 'Philippines: Tacloban',
		'PH-G2' => 'Philippines: Tagaytay',
		'PH-G3' => 'Philippines: Tagbilaran',
		'PH-G4' => 'Philippines: Tangub',
		'PH-63' => 'Philippines: Tarlac',
		'PH-72' => 'Philippines: Tawitawi',
		'PH-G5' => 'Philippines: Toledo',
		'PH-G6' => 'Philippines: Trece Martires',
		'PH-64' => 'Philippines: Zambales',
		'PH-65' => 'Philippines: Zamboanga del Norte',
		'PH-66' => 'Philippines: Zamboanga del Sur',
		'PH-G7' => 'Philippines: Zamboanga',

		'--RU'  => '', '-RU' => 'Russian Federation',
		'RU-01' => 'Russian Federation: Adygeya',
		'RU-02' => 'Russian Federation: Aginsky Buryatsky AO',
		'RU-04' => 'Russian Federation: Altaisky krai',
		'RU-05' => 'Russian Federation: Amur',
		'RU-06' => 'Russian Federation: Arkhangel\'sk',
		'RU-07' => 'Russian Federation: Astrakhan\'',
		'RU-08' => 'Russian Federation: Bashkortostan',
		'RU-09' => 'Russian Federation: Belgorod',
		'RU-10' => 'Russian Federation: Bryansk',
		'RU-11' => 'Russian Federation: Buryat',
		'RU-12' => 'Russian Federation: Chechnya',
		'RU-13' => 'Russian Federation: Chelyabinsk',
		'RU-14' => 'Russian Federation: Chita',
		'RU-15' => 'Russian Federation: Chukot',
		'RU-16' => 'Russian Federation: Chuvashia',
		'RU-17' => 'Russian Federation: Dagestan',
		'RU-18' => 'Russian Federation: Evenk',
		'RU-03' => 'Russian Federation: Gorno-Altay',
		'RU-19' => 'Russian Federation: Ingush',
		'RU-20' => 'Russian Federation: Irkutsk',
		'RU-21' => 'Russian Federation: Ivanovo',
		'RU-22' => 'Russian Federation: Kabardin-Balkar',
		'RU-23' => 'Russian Federation: Kaliningrad',
		'RU-24' => 'Russian Federation: Kalmyk',
		'RU-25' => 'Russian Federation: Kaluga',
		'RU-26' => 'Russian Federation: Kamchatka',
		'RU-27' => 'Russian Federation: Karachay-Cherkess',
		'RU-28' => 'Russian Federation: Karelia',
		'RU-29' => 'Russian Federation: Kemerovo',
		'RU-30' => 'Russian Federation: Khabarovsk',
		'RU-31' => 'Russian Federation: Khakass',
		'RU-32' => 'Russian Federation: Khanty-Mansiy',
		'RU-33' => 'Russian Federation: Kirov',
		'RU-34' => 'Russian Federation: Komi',
		'RU-35' => 'Russian Federation: Komi-Permyak',
		'RU-36' => 'Russian Federation: Koryak',
		'RU-37' => 'Russian Federation: Kostroma',
		'RU-38' => 'Russian Federation: Krasnodar',
		'RU-39' => 'Russian Federation: Krasnoyarsk',
		'RU-40' => 'Russian Federation: Kurgan',
		'RU-41' => 'Russian Federation: Kursk',
		'RU-42' => 'Russian Federation: Leningrad',
		'RU-43' => 'Russian Federation: Lipetsk',
		'RU-44' => 'Russian Federation: Magadan',
		'RU-45' => 'Russian Federation: Mariy-El',
		'RU-46' => 'Russian Federation: Mordovia',
		'RU-48' => 'Russian Federation: Moscow City',
		'RU-47' => 'Russian Federation: Moskva',
		'RU-49' => 'Russian Federation: Murmansk',
		'RU-50' => 'Russian Federation: Nenets',
		'RU-51' => 'Russian Federation: Nizhegorod',
		'RU-68' => 'Russian Federation: North Ossetia',
		'RU-52' => 'Russian Federation: Novgorod',
		'RU-53' => 'Russian Federation: Novosibirsk',
		'RU-54' => 'Russian Federation: Omsk',
		'RU-56' => 'Russian Federation: Orel',
		'RU-55' => 'Russian Federation: Orenburg',
		'RU-57' => 'Russian Federation: Penza',
		'RU-58' => 'Russian Federation: Perm\'',
		'RU-59' => 'Russian Federation: Primor\'ye',
		'RU-60' => 'Russian Federation: Pskov',
		'RU-61' => 'Russian Federation: Rostov',
		'RU-62' => 'Russian Federation: Ryazan\'',
		'RU-66' => 'Russian Federation: Saint Petersburg City',
		'RU-63' => 'Russian Federation: Sakha',
		'RU-64' => 'Russian Federation: Sakhalin',
		'RU-65' => 'Russian Federation: Samara',
		'RU-67' => 'Russian Federation: Saratov',
		'RU-69' => 'Russian Federation: Smolensk',
		'RU-70' => 'Russian Federation: Stavropol\'',
		'RU-71' => 'Russian Federation: Sverdlovsk',
		'RU-72' => 'Russian Federation: Tambovskaya oblast',
		'RU-73' => 'Russian Federation: Tatarstan',
		'RU-74' => 'Russian Federation: Taymyr',
		'RU-75' => 'Russian Federation: Tomsk',
		'RU-76' => 'Russian Federation: Tula',
		'RU-79' => 'Russian Federation: Tuva',
		'RU-77' => 'Russian Federation: Tver\'',
		'RU-78' => 'Russian Federation: Tyumen\'',
		'RU-80' => 'Russian Federation: Udmurt',
		'RU-81' => 'Russian Federation: Ul\'yanovsk',
		'RU-82' => 'Russian Federation: Ust-Orda Buryat',
		'RU-83' => 'Russian Federation: Vladimir',
		'RU-84' => 'Russian Federation: Volgograd',
		'RU-85' => 'Russian Federation: Vologda',
		'RU-86' => 'Russian Federation: Voronezh',
		'RU-87' => 'Russian Federation: Yamal-Nenets',
		'RU-88' => 'Russian Federation: Yaroslavl\'',
		'RU-89' => 'Russian Federation: Yevrey',

		'--ES'  => '', '-ES' => 'Spain',
		'ES-51' => 'Spain: Andalucia',
		'ES-52' => 'Spain: Aragon',
		'ES-34' => 'Spain: Asturias',
		'ES-53' => 'Spain: Canarias',
		'ES-39' => 'Spain: Cantabria',
		'ES-55' => 'Spain: Castilla y Leon',
		'ES-54' => 'Spain: Castilla-La Mancha',
		'ES-56' => 'Spain: Catalonia',
		'ES-60' => 'Spain: Comunidad Valenciana',
		'ES-57' => 'Spain: Extremadura',
		'ES-58' => 'Spain: Galicia',
		'ES-07' => 'Spain: Islas Baleares',
		'ES-27' => 'Spain: La Rioja',
		'ES-29' => 'Spain: Madrid',
		'ES-31' => 'Spain: Murcia',
		'ES-32' => 'Spain: Navarra',
		'ES-59' => 'Spain: Pais Vasco',

		'--TR'  => '', '-TR' => 'Turkey',
		'TR-81' => 'Turkey: Adana',
		'TR-02' => 'Turkey: Adiyaman',
		'TR-03' => 'Turkey: Afyon',
		'TR-04' => 'Turkey: Agri',
		'TR-75' => 'Turkey: Aksaray',
		'TR-05' => 'Turkey: Amasya',
		'TR-68' => 'Turkey: Ankara',
		'TR-07' => 'Turkey: Antalya',
		'TR-86' => 'Turkey: Ardahan',
		'TR-08' => 'Turkey: Artvin',
		'TR-09' => 'Turkey: Aydin',
		'TR-10' => 'Turkey: Balikesir',
		'TR-87' => 'Turkey: Bartin',
		'TR-76' => 'Turkey: Batman',
		'TR-77' => 'Turkey: Bayburt',
		'TR-11' => 'Turkey: Bilecik',
		'TR-12' => 'Turkey: Bingol',
		'TR-13' => 'Turkey: Bitlis',
		'TR-14' => 'Turkey: Bolu',
		'TR-15' => 'Turkey: Burdur',
		'TR-16' => 'Turkey: Bursa',
		'TR-17' => 'Turkey: Canakkale',
		'TR-82' => 'Turkey: Cankiri',
		'TR-19' => 'Turkey: Corum',
		'TR-20' => 'Turkey: Denizli',
		'TR-21' => 'Turkey: Diyarbakir',
		'TR-93' => 'Turkey: Duzce',
		'TR-22' => 'Turkey: Edirne',
		'TR-23' => 'Turkey: Elazig',
		'TR-24' => 'Turkey: Erzincan',
		'TR-25' => 'Turkey: Erzurum',
		'TR-26' => 'Turkey: Eskisehir',
		'TR-83' => 'Turkey: Gaziantep',
		'TR-28' => 'Turkey: Giresun',
		'TR-69' => 'Turkey: Gumushane',
		'TR-70' => 'Turkey: Hakkari',
		'TR-31' => 'Turkey: Hatay',
		'TR-32' => 'Turkey: Icel',
		'TR-88' => 'Turkey: Igdir',
		'TR-33' => 'Turkey: Isparta',
		'TR-34' => 'Turkey: Istanbul',
		'TR-35' => 'Turkey: Izmir',
		'TR-46' => 'Turkey: Kahramanmaras',
		'TR-89' => 'Turkey: Karabuk',
		'TR-78' => 'Turkey: Karaman',
		'TR-84' => 'Turkey: Kars',
		'TR-37' => 'Turkey: Kastamonu',
		'TR-38' => 'Turkey: Kayseri',
		'TR-90' => 'Turkey: Kilis',
		'TR-79' => 'Turkey: Kirikkale',
		'TR-39' => 'Turkey: Kirklareli',
		'TR-40' => 'Turkey: Kirsehir',
		'TR-41' => 'Turkey: Kocaeli',
		'TR-71' => 'Turkey: Konya',
		'TR-43' => 'Turkey: Kutahya',
		'TR-44' => 'Turkey: Malatya',
		'TR-45' => 'Turkey: Manisa',
		'TR-72' => 'Turkey: Mardin',
		'TR-48' => 'Turkey: Mugla',
		'TR-49' => 'Turkey: Mus',
		'TR-50' => 'Turkey: Nevsehir',
		'TR-73' => 'Turkey: Nigde',
		'TR-52' => 'Turkey: Ordu',
		'TR-91' => 'Turkey: Osmaniye',
		'TR-53' => 'Turkey: Rize',
		'TR-54' => 'Turkey: Sakarya',
		'TR-55' => 'Turkey: Samsun',
		'TR-63' => 'Turkey: Sanliurfa',
		'TR-74' => 'Turkey: Siirt',
		'TR-57' => 'Turkey: Sinop',
		'TR-80' => 'Turkey: Sirnak',
		'TR-58' => 'Turkey: Sivas',
		'TR-59' => 'Turkey: Tekirdag',
		'TR-60' => 'Turkey: Tokat',
		'TR-61' => 'Turkey: Trabzon',
		'TR-62' => 'Turkey: Tunceli',
		'TR-64' => 'Turkey: Usak',
		'TR-65' => 'Turkey: Van',
		'TR-92' => 'Turkey: Yalova',
		'TR-66' => 'Turkey: Yozgat',
		'TR-85' => 'Turkey: Zonguldak',

		'--GB'  => '', '-GB' => 'United Kingdom',
		'GB-T5' => 'United Kingdom: Aberdeen City',
		'GB-T6' => 'United Kingdom: Aberdeenshire',
		'GB-T7' => 'United Kingdom: Angus',
		'GB-Q6' => 'United Kingdom: Antrim',
		'GB-Q7' => 'United Kingdom: Ards',
		'GB-T8' => 'United Kingdom: Argyll and Bute',
		'GB-Q8' => 'United Kingdom: Armagh',
		'GB-01' => 'United Kingdom: Avon',
		'GB-Q9' => 'United Kingdom: Ballymena',
		'GB-R1' => 'United Kingdom: Ballymoney',
		'GB-R2' => 'United Kingdom: Banbridge',
		'GB-A1' => 'United Kingdom: Barking and Dagenham',
		'GB-A2' => 'United Kingdom: Barnet',
		'GB-A3' => 'United Kingdom: Barnsley',
		'GB-A4' => 'United Kingdom: Bath and North East Somerset',
		'GB-A5' => 'United Kingdom: Bedfordshire',
		'GB-R3' => 'United Kingdom: Belfast',
		'GB-03' => 'United Kingdom: Berkshire',
		'GB-A6' => 'United Kingdom: Bexley',
		'GB-A7' => 'United Kingdom: Birmingham',
		'GB-A8' => 'United Kingdom: Blackburn with Darwen',
		'GB-A9' => 'United Kingdom: Blackpool',
		'GB-X2' => 'United Kingdom: Blaenau Gwent',
		'GB-B1' => 'United Kingdom: Bolton',
		'GB-B2' => 'United Kingdom: Bournemouth',
		'GB-B3' => 'United Kingdom: Bracknell Forest',
		'GB-B4' => 'United Kingdom: Bradford',
		'GB-B5' => 'United Kingdom: Brent',
		'GB-X3' => 'United Kingdom: Bridgend',
		'GB-B6' => 'United Kingdom: Brighton and Hove',
		'GB-B7' => 'United Kingdom: Bristol',
		'GB-B8' => 'United Kingdom: Bromley',
		'GB-B9' => 'United Kingdom: Buckinghamshire',
		'GB-C1' => 'United Kingdom: Bury',
		'GB-X4' => 'United Kingdom: Caerphilly',
		'GB-C2' => 'United Kingdom: Calderdale',
		'GB-C3' => 'United Kingdom: Cambridgeshire',
		'GB-C4' => 'United Kingdom: Camden',
		'GB-X5' => 'United Kingdom: Cardiff',
		'GB-X7' => 'United Kingdom: Carmarthenshire',
		'GB-R4' => 'United Kingdom: Carrickfergus',
		'GB-R5' => 'United Kingdom: Castlereagh',
		'GB-79' => 'United Kingdom: Central',
		'GB-X6' => 'United Kingdom: Ceredigion',
		'GB-C5' => 'United Kingdom: Cheshire',
		'GB-U1' => 'United Kingdom: Clackmannanshire',
		'GB-07' => 'United Kingdom: Cleveland',
		'GB-90' => 'United Kingdom: Clwyd',
		'GB-R6' => 'United Kingdom: Coleraine',
		'GB-X8' => 'United Kingdom: Conwy',
		'GB-R7' => 'United Kingdom: Cookstown',
		'GB-C6' => 'United Kingdom: Cornwall',
		'GB-C7' => 'United Kingdom: Coventry',
		'GB-R8' => 'United Kingdom: Craigavon',
		'GB-C8' => 'United Kingdom: Croydon',
		'GB-C9' => 'United Kingdom: Cumbria',
		'GB-D1' => 'United Kingdom: Darlington',
		'GB-X9' => 'United Kingdom: Denbighshire',
		'GB-D2' => 'United Kingdom: Derby',
		'GB-D3' => 'United Kingdom: Derbyshire',
		'GB-S6' => 'United Kingdom: Derry',
		'GB-D4' => 'United Kingdom: Devon',
		'GB-D5' => 'United Kingdom: Doncaster',
		'GB-D6' => 'United Kingdom: Dorset',
		'GB-R9' => 'United Kingdom: Down',
		'GB-D7' => 'United Kingdom: Dudley',
		'GB-U2' => 'United Kingdom: Dumfries and Galloway',
		'GB-U3' => 'United Kingdom: Dundee City',
		'GB-S1' => 'United Kingdom: Dungannon',
		'GB-D8' => 'United Kingdom: Durham',
		'GB-91' => 'United Kingdom: Dyfed',
		'GB-D9' => 'United Kingdom: Ealing',
		'GB-U4' => 'United Kingdom: East Ayrshire',
		'GB-U5' => 'United Kingdom: East Dunbartonshire',
		'GB-U6' => 'United Kingdom: East Lothian',
		'GB-U7' => 'United Kingdom: East Renfrewshire',
		'GB-E1' => 'United Kingdom: East Riding of Yorkshire',
		'GB-E2' => 'United Kingdom: East Sussex',
		'GB-U8' => 'United Kingdom: Edinburgh',
		'GB-W8' => 'United Kingdom: Eilean Siar',
		'GB-E3' => 'United Kingdom: Enfield',
		'GB-E4' => 'United Kingdom: Essex',
		'GB-U9' => 'United Kingdom: Falkirk',
		'GB-S2' => 'United Kingdom: Fermanagh',
		'GB-V1' => 'United Kingdom: Fife',
		'GB-Y1' => 'United Kingdom: Flintshire',
		'GB-E5' => 'United Kingdom: Gateshead',
		'GB-V2' => 'United Kingdom: Glasgow City',
		'GB-E6' => 'United Kingdom: Gloucestershire',
		'GB-82' => 'United Kingdom: Grampian',
		'GB-17' => 'United Kingdom: Greater London',
		'GB-18' => 'United Kingdom: Greater Manchester',
		'GB-E7' => 'United Kingdom: Greenwich',
		'GB-92' => 'United Kingdom: Gwent',
		'GB-Y2' => 'United Kingdom: Gwynedd',
		'GB-E8' => 'United Kingdom: Hackney',
		'GB-E9' => 'United Kingdom: Halton',
		'GB-F1' => 'United Kingdom: Hammersmith and Fulham',
		'GB-F2' => 'United Kingdom: Hampshire',
		'GB-F3' => 'United Kingdom: Haringey',
		'GB-F4' => 'United Kingdom: Harrow',
		'GB-F5' => 'United Kingdom: Hartlepool',
		'GB-F6' => 'United Kingdom: Havering',
		'GB-20' => 'United Kingdom: Hereford and Worcester',
		'GB-F7' => 'United Kingdom: Herefordshire',
		'GB-F8' => 'United Kingdom: Hertford',
		'GB-V3' => 'United Kingdom: Highland',
		'GB-F9' => 'United Kingdom: Hillingdon',
		'GB-G1' => 'United Kingdom: Hounslow',
		'GB-22' => 'United Kingdom: Humberside',
		'GB-V4' => 'United Kingdom: Inverclyde',
		'GB-X1' => 'United Kingdom: Isle of Anglesey',
		'GB-G2' => 'United Kingdom: Isle of Wight',
		'GB-G3' => 'United Kingdom: Islington',
		'GB-G4' => 'United Kingdom: Kensington and Chelsea',
		'GB-G5' => 'United Kingdom: Kent',
		'GB-G6' => 'United Kingdom: Kingston upon Hull',
		'GB-G7' => 'United Kingdom: Kingston upon Thames',
		'GB-G8' => 'United Kingdom: Kirklees',
		'GB-G9' => 'United Kingdom: Knowsley',
		'GB-H1' => 'United Kingdom: Lambeth',
		'GB-H2' => 'United Kingdom: Lancashire',
		'GB-S3' => 'United Kingdom: Larne',
		'GB-H3' => 'United Kingdom: Leeds',
		'GB-H4' => 'United Kingdom: Leicester',
		'GB-H5' => 'United Kingdom: Leicestershire',
		'GB-H6' => 'United Kingdom: Lewisham',
		'GB-S4' => 'United Kingdom: Limavady',
		'GB-H7' => 'United Kingdom: Lincolnshire',
		'GB-S5' => 'United Kingdom: Lisburn',
		'GB-H8' => 'United Kingdom: Liverpool',
		'GB-H9' => 'United Kingdom: London',
		'GB-84' => 'United Kingdom: Lothian',
		'GB-I1' => 'United Kingdom: Luton',
		'GB-S7' => 'United Kingdom: Magherafelt',
		'GB-I2' => 'United Kingdom: Manchester',
		'GB-I3' => 'United Kingdom: Medway',
		'GB-28' => 'United Kingdom: Merseyside',
		'GB-Y3' => 'United Kingdom: Merthyr Tydfil',
		'GB-I4' => 'United Kingdom: Merton',
		'GB-94' => 'United Kingdom: Mid Glamorgan',
		'GB-I5' => 'United Kingdom: Middlesbrough',
		'GB-V5' => 'United Kingdom: Midlothian',
		'GB-I6' => 'United Kingdom: Milton Keynes',
		'GB-Y4' => 'United Kingdom: Monmouthshire',
		'GB-V6' => 'United Kingdom: Moray',
		'GB-S8' => 'United Kingdom: Moyle',
		'GB-Y5' => 'United Kingdom: Neath Port Talbot',
		'GB-I7' => 'United Kingdom: Newcastle upon Tyne',
		'GB-I8' => 'United Kingdom: Newham',
		'GB-Y6' => 'United Kingdom: Newport',
		'GB-S9' => 'United Kingdom: Newry and Mourne',
		'GB-T1' => 'United Kingdom: Newtownabbey',
		'GB-I9' => 'United Kingdom: Norfolk',
		'GB-V7' => 'United Kingdom: North Ayrshire',
		'GB-T2' => 'United Kingdom: North Down',
		'GB-J2' => 'United Kingdom: North East Lincolnshire',
		'GB-V8' => 'United Kingdom: North Lanarkshire',
		'GB-J3' => 'United Kingdom: North Lincolnshire',
		'GB-J4' => 'United Kingdom: North Somerset',
		'GB-J5' => 'United Kingdom: North Tyneside',
		'GB-J7' => 'United Kingdom: North Yorkshire',
		'GB-J1' => 'United Kingdom: Northamptonshire',
		'GB-J6' => 'United Kingdom: Northumberland',
		'GB-J8' => 'United Kingdom: Nottingham',
		'GB-J9' => 'United Kingdom: Nottinghamshire',
		'GB-K1' => 'United Kingdom: Oldham',
		'GB-T3' => 'United Kingdom: Omagh',
		'GB-V9' => 'United Kingdom: Orkney',
		'GB-K2' => 'United Kingdom: Oxfordshire',
		'GB-Y7' => 'United Kingdom: Pembrokeshire',
		'GB-W1' => 'United Kingdom: Perth and Kinross',
		'GB-K3' => 'United Kingdom: Peterborough',
		'GB-K4' => 'United Kingdom: Plymouth',
		'GB-K5' => 'United Kingdom: Poole',
		'GB-K6' => 'United Kingdom: Portsmouth',
		'GB-Y8' => 'United Kingdom: Powys',
		'GB-K7' => 'United Kingdom: Reading',
		'GB-K8' => 'United Kingdom: Redbridge',
		'GB-K9' => 'United Kingdom: Redcar and Cleveland',
		'GB-W2' => 'United Kingdom: Renfrewshire',
		'GB-Y9' => 'United Kingdom: Rhondda Cynon Taff',
		'GB-L1' => 'United Kingdom: Richmond upon Thames',
		'GB-L2' => 'United Kingdom: Rochdale',
		'GB-L3' => 'United Kingdom: Rotherham',
		'GB-L4' => 'United Kingdom: Rutland',
		'GB-L5' => 'United Kingdom: Salford',
		'GB-L7' => 'United Kingdom: Sandwell',
		'GB-T9' => 'United Kingdom: Scottish Borders',
		'GB-L8' => 'United Kingdom: Sefton',
		'GB-L9' => 'United Kingdom: Sheffield',
		'GB-W3' => 'United Kingdom: Shetland Islands',
		'GB-L6' => 'United Kingdom: Shropshire',
		'GB-M1' => 'United Kingdom: Slough',
		'GB-M2' => 'United Kingdom: Solihull',
		'GB-M3' => 'United Kingdom: Somerset',
		'GB-W4' => 'United Kingdom: South Ayrshire',
		'GB-96' => 'United Kingdom: South Glamorgan',
		'GB-M6' => 'United Kingdom: South Gloucestershire',
		'GB-W5' => 'United Kingdom: South Lanarkshire',
		'GB-M7' => 'United Kingdom: South Tyneside',
		'GB-37' => 'United Kingdom: South Yorkshire',
		'GB-M4' => 'United Kingdom: Southampton',
		'GB-M5' => 'United Kingdom: Southend-on-Sea',
		'GB-M8' => 'United Kingdom: Southwark',
		'GB-N1' => 'United Kingdom: St. Helens',
		'GB-M9' => 'United Kingdom: Staffordshire',
		'GB-W6' => 'United Kingdom: Stirling',
		'GB-N2' => 'United Kingdom: Stockport',
		'GB-N3' => 'United Kingdom: Stockton-on-Tees',
		'GB-N4' => 'United Kingdom: Stoke-on-Trent',
		'GB-T4' => 'United Kingdom: Strabane',
		'GB-87' => 'United Kingdom: Strathclyde',
		'GB-N5' => 'United Kingdom: Suffolk',
		'GB-N6' => 'United Kingdom: Sunderland',
		'GB-N7' => 'United Kingdom: Surrey',
		'GB-N8' => 'United Kingdom: Sutton',
		'GB-Z1' => 'United Kingdom: Swansea',
		'GB-N9' => 'United Kingdom: Swindon',
		'GB-O1' => 'United Kingdom: Tameside',
		'GB-88' => 'United Kingdom: Tayside',
		'GB-O2' => 'United Kingdom: Telford and Wrekin',
		'GB-O3' => 'United Kingdom: Thurrock',
		'GB-O4' => 'United Kingdom: Torbay',
		'GB-Z2' => 'United Kingdom: Torfaen',
		'GB-O5' => 'United Kingdom: Tower Hamlets',
		'GB-O6' => 'United Kingdom: Trafford',
		'GB-41' => 'United Kingdom: Tyne and Wear',
		'GB-Z3' => 'United Kingdom: Vale of Glamorgan',
		'GB-O7' => 'United Kingdom: Wakefield',
		'GB-O8' => 'United Kingdom: Walsall',
		'GB-O9' => 'United Kingdom: Waltham Forest',
		'GB-P1' => 'United Kingdom: Wandsworth',
		'GB-P2' => 'United Kingdom: Warrington',
		'GB-P3' => 'United Kingdom: Warwickshire',
		'GB-P4' => 'United Kingdom: West Berkshire',
		'GB-W7' => 'United Kingdom: West Dunbartonshire',
		'GB-97' => 'United Kingdom: West Glamorgan',
		'GB-W9' => 'United Kingdom: West Lothian',
		'GB-43' => 'United Kingdom: West Midlands',
		'GB-P6' => 'United Kingdom: West Sussex',
		'GB-45' => 'United Kingdom: West Yorkshire',
		'GB-P5' => 'United Kingdom: Westminster',
		'GB-P7' => 'United Kingdom: Wigan',
		'GB-P8' => 'United Kingdom: Wiltshire',
		'GB-P9' => 'United Kingdom: Windsor and Maidenhead',
		'GB-Q1' => 'United Kingdom: Wirral',
		'GB-Q2' => 'United Kingdom: Wokingham',
		'GB-Q3' => 'United Kingdom: Wolverhampton',
		'GB-Q4' => 'United Kingdom: Worcestershire',
		'GB-Z4' => 'United Kingdom: Wrexham',
		'GB-Q5' => 'United Kingdom: York',

		'--US'  => '', '-US' => 'United States',
		'US-AL' => 'United States: Alabama',
		'US-AK' => 'United States: Alaska',
		'US-AS' => 'United States: American Samoa',
		'US-AZ' => 'United States: Arizona',
		'US-AR' => 'United States: Arkansas',
		'US-AA' => 'United States: Armed Forces Americas',
		'US-AE' => 'United States: Armed Forces Europe',
		'US-AP' => 'United States: Armed Forces Pacific',
		'US-CA' => 'United States: California',
		'US-CO' => 'United States: Colorado',
		'US-CT' => 'United States: Connecticut',
		'US-DE' => 'United States: Delaware',
		'US-DC' => 'United States: District of Columbia',
		'US-FM' => 'United States: Federated States of Micronesia',
		'US-FL' => 'United States: Florida',
		'US-GA' => 'United States: Georgia',
		'US-GU' => 'United States: Guam',
		'US-HI' => 'United States: Hawaii',
		'US-ID' => 'United States: Idaho',
		'US-IL' => 'United States: Illinois',
		'US-IN' => 'United States: Indiana',
		'US-IA' => 'United States: Iowa',
		'US-KS' => 'United States: Kansas',
		'US-KY' => 'United States: Kentucky',
		'US-LA' => 'United States: Louisiana',
		'US-ME' => 'United States: Maine',
		'US-MH' => 'United States: Marshall Islands',
		'US-MD' => 'United States: Maryland',
		'US-MA' => 'United States: Massachusetts',
		'US-MI' => 'United States: Michigan',
		'US-MN' => 'United States: Minnesota',
		'US-MS' => 'United States: Mississippi',
		'US-MO' => 'United States: Missouri',
		'US-MT' => 'United States: Montana',
		'US-NE' => 'United States: Nebraska',
		'US-NV' => 'United States: Nevada',
		'US-NH' => 'United States: New Hampshire',
		'US-NJ' => 'United States: New Jersey',
		'US-NM' => 'United States: New Mexico',
		'US-NY' => 'United States: New York',
		'US-NC' => 'United States: North Carolina',
		'US-ND' => 'United States: North Dakota',
		'US-MP' => 'United States: Northern Mariana Islands',
		'US-OH' => 'United States: Ohio',
		'US-OK' => 'United States: Oklahoma',
		'US-OR' => 'United States: Oregon',
		'US-PW' => 'United States: Palau',
		'US-PA' => 'United States: Pennsylvania',
		'US-PR' => 'United States: Puerto Rico',
		'US-RI' => 'United States: Rhode Island',
		'US-SC' => 'United States: South Carolina',
		'US-SD' => 'United States: South Dakota',
		'US-TN' => 'United States: Tennessee',
		'US-TX' => 'United States: Texas',
		'US-UT' => 'United States: Utah',
		'US-VT' => 'United States: Vermont',
		'US-VI' => 'United States: Virgin Islands',
		'US-VA' => 'United States: Virginia',
		'US-WA' => 'United States: Washington',
		'US-WV' => 'United States: West Virginia',
		'US-WI' => 'United States: Wisconsin',
		'US-WY' => 'United States: Wyoming',

		'--VN'  => '', '-VN' => 'Vietnam',
		'VN-43' => 'Vietnam: An Giang',
		'VN-53' => 'Vietnam: Ba Ria-Vung Tau',
		'VN-02' => 'Vietnam: Bac Thai',
		'VN-03' => 'Vietnam: Ben Tre',
		'VN-54' => 'Vietnam: Binh Dinh',
		'VN-55' => 'Vietnam: Binh Thuan',
		'VN-56' => 'Vietnam: Can Tho',
		'VN-05' => 'Vietnam: Cao Bang',
		'VN-44' => 'Vietnam: Dac Lac',
		'VN-45' => 'Vietnam: Dong Nai',
		'VN-20' => 'Vietnam: Dong Nam Bo',
		'VN-46' => 'Vietnam: Dong Thap',
		'VN-57' => 'Vietnam: Gia Lai',
		'VN-11' => 'Vietnam: Ha Bac',
		'VN-58' => 'Vietnam: Ha Giang',
		'VN-51' => 'Vietnam: Ha Noi',
		'VN-59' => 'Vietnam: Ha Tay',
		'VN-60' => 'Vietnam: Ha Tinh',
		'VN-12' => 'Vietnam: Hai Hung',
		'VN-13' => 'Vietnam: Hai Phong',
		'VN-52' => 'Vietnam: Ho Chi Minh',
		'VN-61' => 'Vietnam: Hoa Binh',
		'VN-62' => 'Vietnam: Khanh Hoa',
		'VN-47' => 'Vietnam: Kien Giang',
		'VN-63' => 'Vietnam: Kon Tum',
		'VN-22' => 'Vietnam: Lai Chau',
		'VN-23' => 'Vietnam: Lam Dong',
		'VN-39' => 'Vietnam: Lang Son',
		'VN-64' => 'Vietnam: Lao Cai',
		'VN-24' => 'Vietnam: Long An',
		'VN-48' => 'Vietnam: Minh Hai',
		'VN-65' => 'Vietnam: Nam Ha',
		'VN-66' => 'Vietnam: Nghe An',
		'VN-67' => 'Vietnam: Ninh Binh',
		'VN-68' => 'Vietnam: Ninh Thuan',
		'VN-69' => 'Vietnam: Phu Yen',
		'VN-70' => 'Vietnam: Quang Binh',
		'VN-29' => 'Vietnam: Quang Nam-Da Nang',
		'VN-71' => 'Vietnam: Quang Ngai',
		'VN-30' => 'Vietnam: Quang Ninh',
		'VN-72' => 'Vietnam: Quang Tri',
		'VN-73' => 'Vietnam: Soc Trang',
		'VN-32' => 'Vietnam: Son La',
		'VN-49' => 'Vietnam: Song Be',
		'VN-33' => 'Vietnam: Tay Ninh',
		'VN-35' => 'Vietnam: Thai Binh',
		'VN-34' => 'Vietnam: Thanh Hoa',
		'VN-74' => 'Vietnam: Thua Thien',
		'VN-37' => 'Vietnam: Tien Giang',
		'VN-75' => 'Vietnam: Tra Vinh',
		'VN-76' => 'Vietnam: Tuyen Quang',
		'VN-77' => 'Vietnam: Vinh Long',
		'VN-50' => 'Vietnam: Vinh Phu',
		'VN-78' => 'Vietnam: Yen Bai',
	);
}
