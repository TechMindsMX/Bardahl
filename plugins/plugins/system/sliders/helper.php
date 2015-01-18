<?php
/**
 * Plugin Helper File
 *
 * @package         Sliders
 * @version         3.5.7
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

// Load common functions
require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';
require_once JPATH_PLUGINS . '/system/nnframework/helpers/tags.php';
require_once JPATH_PLUGINS . '/system/nnframework/helpers/protect.php';

NNFrameworkFunctions::loadLanguage('plg_system_sliders');

/**
 * Plugin that replaces stuff
 */
class plgSystemSlidersHelper
{
	public function __construct(&$params)
	{
		$this->params = $params;

		$this->params->comment_start = '<!-- START: Sliders -->';
		$this->params->comment_end = '<!-- END: Sliders -->';

		$bts = '((?:<[a-zA-Z][^>]*>\s*){0,3})'; // break tags start
		$bte = '((?:\s*<(?:/[a-zA-Z]|br|BR)[^>]*>){0,3})'; // break tags end

		$this->params->tag_open = preg_replace('#[^a-z0-9-_]#si', '', $this->params->tag_open);
		$this->params->tag_close = preg_replace('#[^a-z0-9-_]#si', '', $this->params->tag_close);
		$this->params->tag_link = preg_replace('#[^a-z0-9-_]#si', '', $this->params->tag_link);
		$this->params->tag_delimiter = ($this->params->tag_delimiter == 'space') ? '(?:\s|&nbsp;|&\#160;)' : '=';

		$this->params->regex = '#'
			. $bts
			. '\{(' . $this->params->tag_open . 's?'
			. '((?:-[a-zA-Z0-9-_]+)?)'
			. $this->params->tag_delimiter
			. '((?:[^\}]*?\{[^\}]*?\})*[^\}]*?)|/' . $this->params->tag_close
			. '(?:-[a-z0-9-_]*)?)\}'
			. $bte
			. '#s';
		$this->params->regex_end = '#'
			. $bts
			. '\{/' . $this->params->tag_close
			. '(?:-[a-z0-9-_]+)?\}'
			. $bte
			. '#s';
		$this->params->regex_link = '#'
			. '\{' . $this->params->tag_link
			. '(?:-[a-z0-9-_]+)?' . $this->params->tag_delimiter
			. '([^\}]*)\}'
			. '(.*?)'
			. '\{/' . $this->params->tag_link . '\}'
			. '#s';

		$this->params->protected_tags = array(
			'{' . $this->params->tag_open,
			'{/' . $this->params->tag_close,
			$this->params->tag_link
		);

		$this->ids = array();
		$this->matches = array();
		$this->allitems = array();
		$this->setcount = 0;

		$this->params->disabled_components = array('com_acymailing');

		
	}

	public function onContentPrepare(&$article, &$context)
	{
		NNFrameworkHelper::processArticle($article, $context, $this, 'replaceTags');
	}

	public function onAfterDispatch()
	{
		// only in html
		if (JFactory::getDocument()->getType() !== 'html' && JFactory::getDocument()->getType() !== 'feed')
		{
			return;
		}

		$buffer = JFactory::getDocument()->getBuffer('component');

		if (empty($buffer) || is_array($buffer))
		{
			return;
		}

		// do not load scripts/styles on print page
		if (JFactory::getDocument()->getType() !== 'feed' && !JFactory::getApplication()->input->getInt('print', 0))
		{
			if ($this->params->load_bootstrap_framework)
			{
				JHtml::_('bootstrap.framework');
			}
			JHtml::script('sliders/script.min.js', false, true);
			$script = '
				var nn_sliders_urlscroll = 0;
				var nn_sliders_use_hash = ' . (int) $this->params->use_hash . ';
			';
			JFactory::getDocument()->addScriptDeclaration('/* START: Sliders scripts */ ' . preg_replace('#\n\s*#s', ' ', trim($script)) . ' /* END: Sliders scripts */');
			if ($this->params->load_stylesheet == 2)
			{
				JHtml::stylesheet('sliders/old.min.css', false, true);
			}
			else if ($this->params->load_stylesheet)
			{
				JHtml::stylesheet('sliders/style.min.css', false, true);
			}
		}

		$this->replaceTags($buffer, 'component');

		JFactory::getDocument()->setBuffer($buffer, 'component');
	}

	public function onAfterRender()
	{
		// only in html and feeds
		if (JFactory::getDocument()->getType() !== 'html' && JFactory::getDocument()->getType() !== 'feed')
		{
			return;
		}

		$html = JResponse::getBody();

		if ($html == '')
		{
			return;
		}

		if (strpos($html, '{' . $this->params->tag_open) === false)
		{
			if (strpos($html, 'id="set-nn_sliders') === false)
			{
				// remove style and script if no items are found
				$html = preg_replace('#\s*<' . 'link [^>]*href="[^"]*/(sliders/css|css/sliders)/[^"]*\.css[^"]*"[^>]* />#s', '', $html);
				$html = preg_replace('#\s*<' . 'script [^>]*src="[^"]*/(sliders/js|js/sliders)/[^"]*\.js[^"]*"[^>]*></script>#s', '', $html);
				$html = preg_replace('#/\* START: Sliders .*?/\* END: Sliders [a-z]* \*/\s*#s', '', $html);
			}
		}
		else
		{
			// only do stuff in body
			list($pre, $body, $post) = nnText::getBody($html);
			$this->replaceTags($body, 'body');
			$html = $pre . $body . $post;
		}

		$this->cleanLeftoverJunk($html);

		JResponse::setBody($html);
	}

	function replaceTags(&$string, $area = 'article')
	{
		if (!is_string($string) || $string == '')
		{
			return;
		}

		if ($area == 'component')
		{
			// allow in component?
			if (in_array(JFactory::getApplication()->input->get('option'), $this->params->disabled_components))
			{
				$this->protectTags($string);

				return;
			}
		}

		if (
			strpos($string, '{' . $this->params->tag_open) === false
			&& strpos($string, '{' . $this->params->tag_link) === false
		)
		{
			return;
		}

		$this->protect($string);

		if (JFactory::getApplication()->input->getInt('print', 0))
		{
			// Replace syntax with general html on print pages
			if (preg_match_all($this->params->regex, $string, $matches, PREG_SET_ORDER) > 0)
			{
				foreach ($matches as $match)
				{
					$title = NNText::cleanTitle($match['4']);
					if (strpos($title, '|') !== false)
					{
						list($title, $extra) = explode('|', $title, 2);
					}
					$title = trim($title);
					$name = NNText::cleanTitle($title, 1);
					$title = preg_replace('#<\?h[0-9](\s[^>]* )?>#', '', $title);
					$replace = '<a name="' . $name . '"></a><' . $this->params->title_tag . ' class="nn_sliders-title">' . $title . '</' . $this->params->title_tag . '>';
					$string = str_replace($match['0'], $replace, $string);
				}
			}
			if (preg_match_all($this->params->regex_end, $string, $matches, PREG_SET_ORDER) > 0)
			{
				foreach ($matches as $match)
				{
					$string = str_replace($match['0'], '', $string);
				}
			}
			if (preg_match_all($this->params->regex_link, $string, $matches, PREG_SET_ORDER) > 0)
			{
				foreach ($matches as $match)
				{
					$href = NNText::getURI($match['1']);
					$link = '<a href="' . $href . '">' . $match['2'] . '</a>';
					$string = str_replace($match['0'], $link, $string);
				}
			}
			NNProtect::unprotect($string);

			return;
		}

		$sets = array();
		$setids = array();

		if (preg_match_all($this->params->regex, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				if ($match['2']['0'] == '/')
				{
					array_pop($setids);
					continue;
				}
				end($setids);
				$item = new stdClass;
				$item->orig = $match['0'];
				$item->setid = trim(str_replace('_', '-', $match['3']));
				if (empty($setids) || current($setids) != $item->setid)
				{
					$this->setcount++;
					$setids[$this->setcount . '.'] = $item->setid;
				}
				$item->set = str_replace('--', '-', array_search($item->setid, array_reverse($setids)) . $item->setid);
				$item->title = NNText::cleanTitle($match['4']);
				list($item->pre, $item->post) = NNTags::setSurroundingTags($match['1'], $match['5']);
				if (!isset($sets[$item->set]))
				{
					$sets[$item->set] = array();
				}
				$sets[$item->set][] = $item;
			}
		}

		$urlitem = JFactory::getApplication()->input->getString('slider', '', 'default', 1);
		$urlitem = trim($urlitem);
		if (is_numeric($urlitem))
		{
			$urlitem = '1-' . $urlitem;
		}
		$active_url = '';


		foreach ($sets as $set_id => $items)
		{
			$active_by_url = '';
			$active_by_cookie = '';
			$active = 0;
			foreach ($items as $i => $item)
			{
				$tag = NNTags::getTagValues(str_replace('|alias:', '|alias=', $item->title));
				$item->title = $tag->title;
				$item->alias = isset($tag->alias) ? $tag->alias : '';
				$item->active = 0;

				foreach ($tag->params as $j => $val)
				{
					if (in_array($val, array('active', 'opened', 'open')))
					{
						$active = $i;
						unset($tag->params[$j]);
					}
					else if (in_array($val, array('inactive', 'closed', 'close')))
					{
						$item->active = 0;
						if ($active == $i)
						{
							$active = '';
						}
						unset($tag->params[$j]);
					}
				}
				$item->class = implode(' ', $tag->params);

				$item->set = (int) $set_id;
				$item->count = $i + 1;
				$item->haslink = preg_match('#<a [^>]*>.*?</a>#usi', $item->title);


				$item->title_full = $item->title;
				$item->title = NNText::cleanTitle($item->title, 1);
				if ($item->title == '')
				{
					$item->title = NNText::getAttribute('title', $item->title_full);
					if ($item->title == '')
					{
						$item->title = NNText::getAttribute('alt', $item->title_full);
					}
				}
				$item->title = str_replace(array('&nbsp;', '&#160;'), ' ', $item->title);
				$item->title = preg_replace('#\s+#', ' ', $item->title);

				$item->alias = NNText::createAlias($item->alias ? $item->alias : $item->title);
				$item->alias = $item->alias ?: 'slide';

				$item->id = $this->createId($item->alias);

				$item->matches = NNText::createUrlMatches(array($item->id, $item->title));
				$item->matches[] = ($i + 1) . '';
				$item->matches[] = $item->set . '-' . ($i + 1);

				$item->matches = array_unique($item->matches);
				$item->matches = array_diff($item->matches, $this->matches);
				$this->matches = array_merge($this->matches, $item->matches);

				if ($urlitem != '' && (in_array($urlitem, $item->matches, 1) || in_array(strtolower($urlitem), $item->matches, 1)))
				{
					if (!$item->haslink)
					{
						$active_by_url = $i;
					}
				}
				if ($active == $i && $item->haslink)
				{
					$active++;
				}

				$sets[$set_id][$i] = $item;
				$this->allitems[] = $item;
			}

			if ($active_by_url !== '' && isset($sets[$set_id][$active_by_url]))
			{
				$active = $active_url;
				$active_url = $sets[$set_id][$active_by_url]->id;
			}

			if ($active !== '' && isset($sets[$set_id][$active]))
			{
				$sets[$set_id][$active]->active = 1;
			}
		}

		if (preg_match($this->params->regex_end, $string))
		{
			$mainclass = array();
			$mainclass[] = 'nn_sliders';
			$mainclass[] = 'accordion panel-group';
			if ($this->params->load_stylesheet == 2)
			{
				$mainclass[] = 'oldschool';
			}
			$mainclass = trim(implode(' ', $mainclass));
			foreach ($sets as $items)
			{
				$first = key($items);
				end($items);
				foreach ($items as $i => $item)
				{
					$s = '#' . preg_quote($item->orig, '#') . '#';
					if (@preg_match($s . 'u', $string))
					{
						$s .= 'u';
					}
					if (preg_match($s, $string, $match))
					{
						$html = array();
						$html[] = $item->post;
						$html[] = $item->pre;
						if ($i == $first)
						{
							$html[] = '<div class="' . $mainclass . '" id="set-nn_sliders-' . $items['0']->set . '">';
						}
						else
						{
							$html[] = '</div></div></div>';
						}

						$class = array();
						$class[] = 'accordion-body';
						$class[] = 'collapse';
						if ($item->active)
						{
							$class[] = 'in';
						}

						$html[] = '<div class="' . trim('accordion-group panel ' . ($item->active ? 'active ' : '') . $item->class) . '">';
						$html[] = '<div class="accordion-heading panel-heading">';
						if ($item->haslink)
						{
							$html[] = $item->title_full;
						}
						else
						{
							$href = NNText::getURI($item->id);
							$html[] = '<a class="accordion-toggle" data-toggle="collapse" data-parent="#set-nn_sliders-' . $items['0']->set . '" href="' . $href . '"'
								. '><span class="nn_sliders-toggle-inner">';
							$html[] = $item->title_full;
							$html[] = '</span></a>';
						}
						$html[] = '</div>';
						$html[] = '<div class="' . trim(implode(' ', $class)) . '" id="' . $item->id . '">';
						$html[] = '<div class="accordion-inner panel-body">';

						$html = implode("\n", $html);
						$pos = strpos($string, $match['0']);
						if ($pos !== false)
						{
							$string = substr_replace($string, $html, $pos, strlen($match['0']));
						}
					}
				}
			}
		}

		// closing tag
		if (preg_match_all($this->params->regex_end, $string, $matches, PREG_SET_ORDER) > 0)
		{
			$script = '';
			if ($active_url)
			{
				$script .= 'nnSliders.show(\'' . $active_url . '\');';
			}
			if ($script)
			{
				$script = '<script type="text/javascript">'
					. 'jQuery(document).ready(function(){ '
					. $script
					. ' });'
					. '</script>';
				if ($script)
				{
					$string = preg_replace($this->params->regex_end, $script . '\0', $string, 1);
				}
			}
			foreach ($matches as $match)
			{
				$html = '</div></div></div></div>';
				list($pre, $post) = NNTags::setSurroundingTags($match['1'], $match['2']);
				$html = $pre . $html . $post;
				$string = str_replace($match['0'], $html, $string);
			}
		}

		// link tag
		if (preg_match_all($this->params->regex_link, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$linkitem = 0;
				$names = NNText::createUrlMatches(array($match['1']));
				foreach ($names as $name)
				{
					if (is_numeric($name))
					{
						foreach ($this->allitems as $item)
						{
							if (in_array($name, $item->matches, 1) || in_array((int) $name, $item->matches, 1))
							{
								$linkitem = $item;
								break;
							}
						}
					}
					else
					{
						foreach ($this->allitems as $item)
						{
							if (in_array($name, $item->matches, 1) || in_array(strtolower($name), $item->matches, 1))
							{
								$linkitem = $item;
								break;
							}
						}
					}
					if ($linkitem)
					{
						break;
					}
				}
				if ($linkitem)
				{
					$href = NNText::getURI($linkitem->id);
					$onclick = 'nnSliders.show(this.rel);return false;';
					$link = '<a href="' . $href . '"'
						. ' class="nn_sliders-link nn_sliders-link-' . $linkitem->alias . '"'
						. ' rel="' . $linkitem->id . '"'
						. ' onclick="' . $onclick . '"><span class="nn_sliders-link-inner">' . $match['2'] . '</span></a>';
				}
				else
				{
					$href = NNText::getURI($name);
					$link = '<a href="' . $href . '">' . $match['2'] . '</a>';
				}
				$string = str_replace($match['0'], $link, $string);
			}
		}

		NNProtect::unprotect($string);
	}

	function createId($alias)
	{
		$id = $alias;

		$i = 1;
		while (in_array($id, $this->ids))
		{
			$id = $alias . '-' . ++$i;
		}

		$this->ids[] = $id;

		return $id;
	}

	function protect(&$string)
	{
		NNProtect::protectFields($string);
		NNProtect::protectSourcerer($string);
	}

	function protectTags(&$string)
	{
		NNProtect::protectTags($string, $this->params->protected_tags);
	}

	function unprotectTags(&$string)
	{
		NNProtect::unprotectTags($string, $this->params->protected_tags);
	}

	/**
	 * Just in case you can't figure the method name out: this cleans the left-over junk
	 */
	function cleanLeftoverJunk(&$string)
	{
		$this->unprotectTags($string);

		NNProtect::removeFromHtmlTagContent($string, $this->params->protected_tags);
		NNProtect::removeInlineComments($string, 'Sliders');
	}
}