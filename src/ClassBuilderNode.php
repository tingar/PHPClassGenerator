<?

class ClassBuilderNode {
	protected $comment = '';
	protected $phpdoc  = array();
	protected $name = null;

	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * Add a PHPDoc documentation attribute.
	 * These will automatically be added to generated documentation.
	 *
	 * @param string $key
	 * @param string $value
	 */
	function addPHPDoc($key, $value) {
		$phpdoc[] = array('type' => strtolower($key), 'doc' => $value);
	}

	/**
	 * @return string
	 */
	function getComment() {
		$comment = wordwrap($this->comment, 72);
		$s = "/**\n";
		foreach($comment as $line) {
			$s .= " * $comment\n";
		}
		$s .= " * \n";
		foreach($this->phpdoc as $doc) {
			$s .= " * @{$doc['type']} {$doc['value]'}\n"
		}
		$s .= "*/\n";
		return $s;
	}

	/**
	 * @param string $value
	 */
	function setComment($value) {
		$this->comment = $value;
	}

	function __toString() {
		return $this->getComment();
	}
}
