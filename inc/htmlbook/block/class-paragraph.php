<?php

namespace Pressbooks\HTMLBook\Block;

/**
 * Based on HTMLBook (Unofficial Draft 16 February 2016)
 *
 * HTML element: <p>
 *
 * Example:
 *
 *     <p>This is a standard paragraph with some <em>emphasized text</em></p>
 *
 * @see https://oreillymedia.github.io/HTMLBook/#_paragraph
 */
class Paragraph {

	/**
	 * @var string
	 */
	protected $tag = 'p';

	/**
	 * @var bool
	 */
	protected $dataTypeRequired = false;

	/**
	 * @var array
	 */
	protected $dataTypes = [];

}
