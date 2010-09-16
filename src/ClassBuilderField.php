<?

class ClassBuilderField extends ClassBuilderNode {
	protected $access  = ClassBuilder::ACCESS_PUBLIC;
	protected $static  = false;
	protected $default = null;

	/**
	 * Set the access permissions of this method.
	 *
	 * @param string $access
	 */
	function setAccess($access) {
		$this->access = $access;

		$documented = false;
		foreach($this->phpdoc as $doc) {
			if ('access'==$doc['type']) {
				$doc['value'] = $access;
				$documented = true;
			}
		}
		if (!$documented) {
			$this->addPHPDoc('access', $access);
		}
	}

	function setStatic() {
		$this->static = true;
	}

	function setDefault($val) {
		$this->default = $val;
	}

	function __construct($name) {
		parent::__construct($name);
		$this->addPHPDoc('var', '');
	}

	function __toString() {
		$s = $this->getComment();

		$s .= "{$this->access} ";

		if ($this->static) {
			$s .= "{$this->static} ";
		}
		$s .= $this->name;
		if ($this->default) {
			$s .= "= {$this->default}";
		}
		return $s.";\n";
	}
}
