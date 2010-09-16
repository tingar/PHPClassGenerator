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
	 * Adds or replaces a PHPDoc documentation attribute.
	 * This is for attributes that only can be set once, like @return.
	 *
	 * @param string $key
	 * @param string $value
	 */
	function setPHPDoc($key, $value) {
		$exists = false;
		foreach($this->phpdoc as $doc) {
			if ($key==$doc['type']) {
				$doc['value'] = $value;
				$exists = true;
			}
		}
		if (!$exists) {
			$this->addPHPDoc($key, $value);
		}
	}

	/**
	 * @return string
	 */
	function getComment() {
		$s = "/**\n";
		if ($this->comment) {
			$comment = explode("\n", wordwrap($this->comment, 72));
			foreach($comment as $line) {
				$s .= ' *'.($line ? " $line" : '')."\n";
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
