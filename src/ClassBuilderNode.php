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
		$this->phpdoc[] = array('type' => strtolower($key), 'doc' => $value);
	}

	/**
	 * @return string
	 */
	function getComment() {
		$s = "/**\n";
		if ($this->comment) {
			$comment = explode("\n", wordwrap($this->comment, 72));
			foreach($comment as $line) {
				$s .= " * $line\n";
			}
			$s .= " * \n";
		}
		foreach($this->phpdoc as $doc) {
			$s .= " * @{$doc['type']}";
			if ($doc['doc']) {
				$s .= " {$doc['doc']}";
			}
			$s .= "\n";
		}
		$s .= " */\n";
		return $s;
	}

	/**
	 * @param string $value
	 */
	function setComment($value) {
		$this->comment = $value;
	}

	function getName() {
		return $this->name;
	}

	function __toString() {
		return $this->getComment();
	}
}
