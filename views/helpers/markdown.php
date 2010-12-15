<?php
/**
 * Markdown Helper
 *
 * Markdown is a text-to-HTML conversion tool for web writers by John Gruber. Markdown allows you to write using an easy-to-read, easy-to-write plain text format, then convert it to structurally valid XHTML (or HTML).
 * Learn more about markdown here: http://daringfireball.net/projects/markdown/.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 */
class MarkdownHelper extends AppHelper
{

/**
 * converts markdown to html
 *
 * @param  string $text Text in markdown format
 * @return string parsed $text
 */
	public function transform($text = null)
	{
		if (!isset($this->parser))
		{
			if (!class_exists('Markdown_Parser'))
			{
				App::import('Vendor', 'Flour.MarkdownParser');
			}
			$this->parser = new Markdown_Parser;
		}
		return $this->parser->transform($text);
	}

}
