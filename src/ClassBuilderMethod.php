<?

class ClassBuilderMethod extends ClassBuilderNode {
	protected $access = ClassBuilder::ACCESS_PUBLIC;
	protected $static = false;
	protected $code = '';
	protected $type = 'void';
	protected $args = array();

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

	/**
	 * Set this method to be a static method.
	 */
	function setStatic() {
		$this->static = true;
	}

	function setType($type) {
		$this->type = $type;
	}

	function setCode($code) {
		$this->code = $code;
	}

	function addArg($type, $name, $default=null, $optional=null) {
		if (!array_key_exists($name, $this->args)) {
			$this->addPHPDoc('param', "$type $name");
		}
		$this->args[$name] = array(
			'type'         => $type,
			'defaultValue' => $default,
			'optional'     => $optional
		);
	}

	function __toString() {
		$funcspec = $this->getComment();

		if (ClassBuilder::ACCESS_PUBLIC!=$this->access) {
			$funcspec .= "{$this->access} ";
		}
		if ($this->static) {
			$funcspec .= 'static ';
		}
		$funcspec .= "function {$this->name}(";
		$firstarg = true;
		foreach($this->args as $name => $arg) {
			if ($firstarg) {
				$firstarg = false;
			} else {
				$funcspec .= ', ';
			}
			// assume built-in types are all lowercase; classes aren't
			if (
				strtolower($arg['type'])!=$arg['type'] &&
				false==strstr($arg['type'], '[]') &&
				false==strstr($arg['type'], '|')
			) {
				$funcspec .= "{$arg['type']} ";
			}
			$funcspec .= $name;
		}
		$funcspec .= ") {\n";
		foreach(explode("\n", $this->code) as $line) {
			$funcspec .= "\t{$line}\n";
		}
		$funcspec .= "}\n";

		return $funcspec;
	}
}
